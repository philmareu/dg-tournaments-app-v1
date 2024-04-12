<?php

namespace DGTournaments\Listeners\EmailNotifications;

use DGTournaments\Events\RatingUpdatedEvent;
use DGTournaments\Mail\User\RatingUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendRatingUpdatedEmail
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
     * @param  RatingUpdatedEvent  $event
     * @return void
     */
    public function handle(RatingUpdatedEvent $event)
    {
//        if($event->user->emailNotificationSettings->where('id', 1)->count())
//        {
//            Mail::to($event->user->email)->send(new RatingUpdated($event->user, $event->old, $event->new));
//        }
    }
}
