<?php

namespace DGTournaments\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;

class SavedSearchFoundNewTournamentsMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $tournamentNotification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tournamentNotification)
    {
        $this->tournamentNotification = $tournamentNotification;
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
            ->subject('New Tournaments')
            ->markdown('emails.user.searches.new_tournaments');
    }
}
