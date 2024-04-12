<?php

namespace DGTournaments\Listeners\EmailNotifications;

use DGTournaments\Events\Registration\RegistrationIsOpen;
use DGTournaments\Mail\User\RegistrationIsOpenMailable;
use DGTournaments\Models\Follow;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendRegistrationOpenEmail
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
     * @param  RegistrationIsOpen  $event
     * @return void
     */
    public function handle(RegistrationIsOpen $event)
    {
        $event->registration->tournament->followers->filter(function (Follow $follow) {
            return (bool) $follow->user->emailNotificationSettings->where('id', 2)->count();
        })->each(function(Follow $follow) use ($event) {
            Mail::to($follow->user->email)
                ->send(new RegistrationIsOpenMailable($event->registration));
        });
    }
}
