<?php

namespace DGTournaments\Notifications;

use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\App;

class TournamentClaimedNotification extends Notification
{
    use Queueable;

    public $user;

    public $tournament;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Tournament $tournament)
    {
        $this->user = $user;
        $this->tournament = $tournament;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return App::environment('production') ? ['slack'] : [];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage)
            ->content($this->user->name . ' (' . $this->user->email . ')' . ' has successfully claimed tournament');
    }
}
