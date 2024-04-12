<?php

namespace DGTournaments\Mail\User;

use DGTournaments\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RatingUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    public $old;

    public $new;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $old, $new)
    {
        $this->user = $user;
        $this->old = $old;
        $this->new = $new;
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
            ->subject('PDGA rating updated')
            ->markdown('emails.user.notifications.rating');
    }
}
