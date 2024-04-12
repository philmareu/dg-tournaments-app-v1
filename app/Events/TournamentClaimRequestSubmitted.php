<?php

namespace DGTournaments\Events;

use DGTournaments\Models\ClaimRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TournamentClaimRequestSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $claimRequest;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(ClaimRequest $claimRequest)
    {
        $claimRequest->load('tournament', 'user');

        $this->claimRequest = $claimRequest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
