<?php

namespace DGTournaments\Mail\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RefundRequestMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $transfer;

    public $amount;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $transfer, $amount)
    {
        $this->user = $user;
        $this->transfer = $transfer;
        $this->amount = $amount;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin.refund');
    }
}
