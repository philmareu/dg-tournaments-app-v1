<?php

namespace DGTournaments\Mail\User;

use DGTournaments\Models\UserReferral;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendReferral extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $referral;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(UserReferral $referral)
    {
        $this->referral = $referral;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('admin@dgtournaments.com', 'DG Tournaments')
            ->subject($this->referral->referredBy->name . ' invited you to check out DG Tournaments')
            ->markdown('emails.user.referral');
    }
}
