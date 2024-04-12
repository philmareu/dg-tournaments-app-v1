<?php

namespace DGTournaments\Events;

use DGTournaments\Models\Follow;
use DGTournaments\Models\Tournament;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Auth;

class TournamentFollowed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $follow;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Follow $follow)
    {
        $this->follow = $follow;
        $this->user = Auth::user();
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
