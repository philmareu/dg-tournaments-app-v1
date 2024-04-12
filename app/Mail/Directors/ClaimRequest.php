<?php

namespace DGTournaments\Mail\Directors;

use DGTournaments\Models\Tournament;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ClaimRequest extends Mailable implements ShouldQueue
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
        $this->tournament = $tournament->load('claimRequest');
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
            ->subject('Claim Tournament Page')
            ->markdown('emails.directors.claim.requested');
    }
}
