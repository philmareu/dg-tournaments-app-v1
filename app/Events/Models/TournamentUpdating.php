<?php

namespace DGTournaments\Events\Models;

use DGTournaments\Models\Tournament;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TournamentUpdating
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tournament;

    public $user;

    public $changes;

    public $original;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
        $this->user = auth()->user();
        $this->changes = $tournament->getDirty();
        $this->original = $tournament->getOriginal();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
