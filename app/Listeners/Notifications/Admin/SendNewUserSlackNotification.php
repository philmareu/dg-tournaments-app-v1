<?php

namespace DGTournaments\Listeners\Notifications\Admin;

use DGTournaments\Events\NewUserActivated;
use DGTournaments\Models\User\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\App;

class SendNewUserSlackNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewUserActivated  $event
     * @return void
     */
    public function handle(NewUserActivated $event)
    {
        User::find(1)->notify(new \DGTournaments\Notifications\NewUserActivated($event->user));
    }
}
