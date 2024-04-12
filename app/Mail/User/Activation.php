<?php

namespace DGTournaments\Mail\User;

use DGTournaments\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Activation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $link;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $link)
    {
        $this->user = $user;
        $this->link = $link;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Activate your DG Tournaments Account')
            ->markdown('emails.user.activation');
    }
}
