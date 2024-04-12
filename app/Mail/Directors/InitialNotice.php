<?php

namespace DGTournaments\Mail\Directors;

use DGTournaments\Models\Player;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InitialNotice extends Mailable
{
    use Queueable, SerializesModels;

    public $tournaments;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($tournaments)
    {
        $this->tournaments = $tournaments;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from('phil@dgtournaments.com', 'Phil Mareu')
            ->subject('Introducing Disc Golf Tournaments')
            ->markdown('emails.directors.initial');
    }
}
