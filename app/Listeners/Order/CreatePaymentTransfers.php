<?php

namespace DGTournaments\Listeners\Order;

use DGTournaments\Billing\Stripe\StripeBilling;
use DGTournaments\Data\Price;
use DGTournaments\Events\OrderPaid;
use DGTournaments\Mail\Managers\NewOrderPaidMailable;
use DGTournaments\Models\OrderSponsorship;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\Transfer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class CreatePaymentTransfers implements ShouldQueue
{
    protected $billing;

    protected $tournament;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(StripeBilling $billing, Tournament $tournament, Transfer $transfer)
    {
        $this->billing = $billing;
        $this->tournament = $tournament;
    }

    /**
     * Handle the event.
     *
     * @param  OrderPaid  $event
     * @return void
     */
    public function handle(OrderPaid $event)
    {
        $event->order->sponsorships->sortSponsorshipsByTournament()->each(function (Collection $orderSponsorships, $tournamentId) use ($event) {
            $tournament = $this->tournament->find($tournamentId);
            $charge = $event->order->charges->where('status', 'succeeded')->first();
            $total = $orderSponsorships->reduce(function($carry, OrderSponsorship $orderSponsorship) {
                return $carry + $orderSponsorship->cost->inCents();
            });

            $fee = round($total * .029 + 30);
            $remaining = $total - $fee;

            $stripeTransfer = $this->billing->transfer()->create(new Price($remaining), $tournament->stripeAccount->stripe_user_id, $charge->charge_id);

            $transfer = new Transfer([
                'destination' => $stripeTransfer->destination,
                'fee' => $fee,
                'amount' => $remaining
            ]);

            $transfer->tr_id = $stripeTransfer->id;
            $transfer->tournament()->associate($tournament);
            $transfer->order()->associate($event->order);
            $transfer->save();

            $tournament->transfers()->save($transfer);
            $orderSponsorships->each(function(OrderSponsorship $orderSponsorship) use($transfer) {
                $orderSponsorship->transfer()->associate($transfer);
                $orderSponsorship->save();
            });

            Mail::to($tournament->managers)
                ->send(new NewOrderPaidMailable($transfer));
        });
    }
}
