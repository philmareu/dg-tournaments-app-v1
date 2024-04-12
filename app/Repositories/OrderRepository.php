<?php

namespace DGTournaments\Repositories;


use DGTournaments\Billing\Stripe\StripeBilling;
use DGTournaments\Data\Price;
use DGTournaments\Events\OrderPaid;
use DGTournaments\Models\Charge;
use DGTournaments\Models\OrderSponsorship;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use DGTournaments\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderRepository
{
    protected $order;

    protected $billing;

    public function __construct(Order $order, StripeBilling $billing)
    {
        $this->order = $order;
        $this->billing = $billing;
    }

    public function getTournamentOrders(Tournament $tournament)
    {
        return DB::table('orders')
            ->join('order_sponsorships', 'orders.id', '=', 'order_sponsorships.order_id')
            ->join('sponsorships', 'order_sponsorships.sponsorship_id', '=', 'sponsorships.id')
            ->join('transfers', function ($join) {
                $join->on('orders.id', '=', 'transfers.order_id')
                    ->where('transfers.tournament_id', '=', 1);
            })
            ->where('sponsorships.tournament_id', 1)
            ->where('orders.paid', 1)
            ->select('orders.*', DB::raw('(transfers.amount + transfers.fee) as total'))
            ->distinct()
            ->get();
    }

    public function getTournamentOrder(Tournament $tournament, $orderId)
    {
        return DB::table('orders')
            ->join('order_sponsorships', 'orders.id', '=', 'order_sponsorships.order_id')
            ->join('sponsorships', 'order_sponsorships.sponsorship_id', '=', 'sponsorships.id')
            ->join('transfers', function ($join) {
                $join->on('orders.id', '=', 'transfers.order_id')
                    ->where('transfers.tournament_id', '=', 1);
            })
            ->where('sponsorships.tournament_id', 1)
            ->where('orders.id', $orderId)
            ->where('orders.paid', 1)
            ->select([
                'orders.*'
            ])
            ->first();
    }

    public function getTournamentOrderSponsorships(Tournament $tournament, $orderId)
    {
        return DB::table('order_sponsorships')
            ->join('sponsorships', 'order_sponsorships.sponsorship_id', '=', 'sponsorships.id')
            ->where('sponsorships.tournament_id', 1)
            ->where('order_sponsorships.order_id', $orderId)
            ->select([
                'order_sponsorships.*',
                'sponsorships.title'
            ])
            ->get();
    }

    public function getTournamentOrderTransfer(Tournament $tournament, $orderId)
    {
        return DB::table('transfers')
            ->where('tournament_id', 1)
            ->where('order_id', $orderId)
            ->select('transfers.*', DB::raw('(transfers.amount + transfers.fee) as total'))
            ->first();
    }

    public function addSponsorship(Sponsorship $sponsorship, $orderId = null)
    {
        $order = $this->getOrderOrCreateNew($orderId);

        $cartQuantity = $order->sponsorships->where('sponsorship.id', $sponsorship->id)->count();

        if($cartQuantity < $sponsorship->quantity)
        {
            $orderSponsorship = new OrderSponsorship(['cost' => $sponsorship->cost->inDollars()]);
            $orderSponsorship->sponsorship()->associate($sponsorship);
            $order->sponsorships()->save($orderSponsorship);
        }

        return $order;
    }

    /**
     * @param OrderSponsorship $orderSponsorship
     * @return bool|null
     */
    public function removeSponsorship(OrderSponsorship $orderSponsorship)
    {
        if($orderSponsorship->order->paid) return false;

        return $orderSponsorship->delete();
    }

    public function createNewOrder() : Order
    {
        return $this->order->create([
            'unique' => str_random(100)
        ]);
    }

    public function processCustomerDetails($unique, $attributes) : Order
    {
        if(is_array($attributes)) $attributes = collect($attributes);

        $order = $this->getOrderByUnique($unique);
        $order->update($attributes->only(['email', 'first_name', 'last_name'])->toArray());

        return $order;
    }

    public function pay(Order $order, $source, $user = null)
    {
        if(substr($source['source'], 0, 4) == 'card')
        {
            $charge = $this->billing->charge()->create(
                Price::make($order->total->inCents()),
                $order->id,
                $source['source'],
                $user->stripe_customer_id
            );
        }
        else
        {
            $charge = $this->billing->charge()->create(
                Price::make($order->total->inCents()),
                $order->id,
                $source['source']
            );
        }

        $charge = new Charge([
            'charge_id' => $charge->id,
            'status' => $charge->status,
            'amount' => $charge->amount
        ]);

        $order->charges()->save($charge);

        if($charge->status !== 'succeeded') return $order;

        $this->reduceInventory($order);

        return $this->markOrderAsPaid($order);
    }

    /**
     * @param string $orderUnique
     */
    public function getOrderByUnique($orderUnique)
    {
        return $this->order->whereUnique($orderUnique)->wherePaid(0)->first();
    }

    /**
     * @param $orderId
     * @return Order
     */
    public function getOrderOrCreateNew($orderId = null) : Order
    {
        if(is_null($orderId)) return $this->createNewOrder();

        if(is_null($order = $this->getOrderByUnique($orderId))) return $this->createNewOrder();

        return $order;
    }

    public function sortOrderByTournament(Order $order)
    {
        return $order->sponsorships->each();
    }

    public function markOrderAsPaid($order)
    {
        $order->paid = 1;
        $order->save();

        event(new OrderPaid($order));

        return $order;
    }

    public function getUsersOrders(User $user)
    {
        return $this->order
            ->with('charges', 'sponsorships')
            ->whereHas('charges', function($query) {
                $query->where('status', 'succeeded');
            })
            ->where('user_id', $user->id)
            ->get();
    }

    public function reduceInventory($order)
    {
        $order->sponsorships->each(function(OrderSponsorship $orderSponsorship) {
            $orderSponsorship->sponsorship->decrement('quantity');
        });
    }
}