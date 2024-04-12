<?php

namespace DGTournaments\Mail\User;

use DGTournaments\Models\Tournament;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TournamentAutoAssignedMailable extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $tournament;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('New Tournament')
            ->markdown('emails.user.tournament.assigned');
    }
}
