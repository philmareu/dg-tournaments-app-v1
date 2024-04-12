<?php

namespace DGTournaments\Events;

use DGTournaments\Models\Registration;;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TournamentRegistrationUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $registration;

    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration->load('tournament');
        $this->user = auth()->user();
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
