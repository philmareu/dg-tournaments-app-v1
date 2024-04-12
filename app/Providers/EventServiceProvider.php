<?php

namespace DGTournaments\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            \DGTournaments\Listeners\Operations\AutoAssignUserToTournaments::class,
            \DGTournaments\Listeners\Operations\SetupNewUser::class
        ],
        \DGTournaments\Events\RatingUpdatedEvent::class => [
            \DGTournaments\Listeners\EmailNotifications\SendRatingUpdatedEmail::class,
            \DGTournaments\Listeners\Notifications\CreateRatingUpdatedNotification::class,
            \DGTournaments\Listeners\Activity\CreatePdgaRatingUpdatedActivity::class
        ],
        \DGTournaments\Events\NewUserActivated::class => [
            \DGTournaments\Listeners\Notifications\Admin\SendNewUserSlackNotification::class
        ],
        \DGTournaments\Events\TournamentFollowed::class => [
            \DGTournaments\Listeners\Activity\CreateTournamentFollowedActivity::class,
        ],
        \DGTournaments\Events\TournamentUnfollowed::class => [
            \DGTournaments\Listeners\Activity\CreateTournamentUnfollowedActivity::class
        ],
        \DGTournaments\Events\TournamentClaimRequestSubmitted::class => [
            \DGTournaments\Listeners\EmailNotifications\SendClaimRequestEmailToTournament::class,
            \DGTournaments\Listeners\EmailNotifications\SendClaimRequestConfirmationEmail::class,
            \DGTournaments\Listeners\Activity\CreateClaimSubmittedActivity::class,
        ],
        \DGTournaments\Events\TournamentClaimApproved::class => [
            \DGTournaments\Listeners\Notifications\Admin\SendTournamentClaimedNotification::class,
            \DGTournaments\Listeners\Notifications\Admin\SendApprovedEmailToClaimRequester::class,
            \DGTournaments\Listeners\Activity\CreateClaimRequestApprovedActivity::class,
            \DGTournaments\Listeners\Operations\DeleteClaimRequest::class
        ],
        \DGTournaments\Events\TournamentSubmitted::class => [
            \DGTournaments\Listeners\EmailNotifications\SendTournamentSubmittedConfirmationEmail::class,
            \DGTournaments\Listeners\Activity\CreateTournamentSubmittedActivity::class
        ],
        \DGTournaments\Events\TournamentRegistrationUpdated::class => [
            \DGTournaments\Listeners\Activity\CreateTournamentRegistrationUpdatedActivity::class
        ],
        \DGTournaments\Events\ScheduleSaved::class => [
            \DGTournaments\Listeners\Activity\CreateScheduleItemSavedActivity::class
        ],
        \DGTournaments\Events\LinkSaved::class => [
            \DGTournaments\Listeners\Activity\CreateLinkSavedActivity::class
        ],
        \DGTournaments\Events\MediaSaved::class => [
            \DGTournaments\Listeners\Activity\CreateMediaSavedActivity::class
        ],
        \DGTournaments\Events\PlayerPackItemSaved::class => [
            \DGTournaments\Listeners\Activity\CreatePlayerPackItemSavedActivity::class
        ],
        \DGTournaments\Events\CourseCreated::class => [
            \DGTournaments\Listeners\Activity\CreateCourseCreatedActivity::class
        ],
        \DGTournaments\Events\TournamentCourseCreated::class => [
            \DGTournaments\Listeners\Activity\CreateTournamentCourseCreatedActivity::class,
            \DGTournaments\Listeners\Operations\CheckTournamentLatLng::class
        ],
        \DGTournaments\Events\OrderPaid::class => [
            \DGTournaments\Listeners\EmailNotifications\SendOrderConfirmationEmail::class,
            \DGTournaments\Listeners\Order\CreatePaymentTransfers::class,
        ],
        \DGTournaments\Events\Models\SponsorshipCreated::class => [
            \DGTournaments\Listeners\Activity\CreateSponsorshipCreatedActivity::class,
        ],
        \DGTournaments\Events\Models\TournamentCreated::class => [
            \DGTournaments\Listeners\Activity\SetDefaultFlags::class,
        ],
        \DGTournaments\Events\Models\TournamentUpdating::class => [
            \DGTournaments\Listeners\Activity\CreateTournamentUpdatingActivity::class,
        ],
        \DGTournaments\Events\TournamentAutoAssigned::class => [
            \DGTournaments\Listeners\Activity\CreateTournamentAutoAssignedActivity::class
        ],
        \DGTournaments\Events\Registration\RegistrationOpensSoon::class => [
            \DGTournaments\Listeners\Activity\CreateTournamentRegistrationOpensSoonActivity::class
        ],
        \DGTournaments\Events\Registration\RegistrationIsOpen::class => [
            \DGTournaments\Listeners\Activity\CreateTournamentRegistrationIsOpenActivity::class,
            \DGTournaments\Listeners\EmailNotifications\SendRegistrationOpenEmail::class
        ],
        \DGTournaments\Events\Registration\RegistrationClosesSoon::class => [
            \DGTournaments\Listeners\Activity\CreateTournamentRegistrationClosesSoonActivity::class
        ]
    ];

    /**
     * Register any other events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
