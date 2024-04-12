<?php

namespace DGTournaments\Events;

use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TournamentAutoAssigned
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $tournament;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Tournament $tournament, User $user)
    {
        $this->tournament = $tournament;
        $this->user = $user;
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
