<?php

namespace DGTournaments\Console\Commands;

use Carbon\Carbon;
use DGTournaments\Events\Registration\RegistrationClosesSoon;
use DGTournaments\Events\Registration\RegistrationIsOpen;
use DGTournaments\Events\Registration\RegistrationOpensSoon;
use DGTournaments\Listeners\Activity\SavesActivities;
use DGTournaments\Models\Activity;
use DGTournaments\Models\Registration;
use Illuminate\Console\Command;

class CheckUpcomingRegistrations extends Command
{
    use SavesActivities;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registration:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for upcoming registrations and create activities';

    protected $registration;

    protected $activity;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Registration $registration, Activity $activity)
    {
        parent::__construct();

        $this->registration = $registration;
        $this->activity = $activity;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // check for registrations coming up in 3 days
        $registrations = $this->registration
            ->where('opens_at', '>', Carbon::now()->subDay(1))
            ->where('opens_at', '<=', Carbon::now()->addDays(3))
            ->get();

        // check for registrations that are now open

        $tomorrow = $registrations->filter(function (Registration $registration) {
            return $registration->opens_at->isTomorrow();
        })->filter(function (Registration $registration) {
            $activity = $this->activity
                ->where('type', 'tournament.registration.opens_soon')
                ->where('resource_id', $registration->tournament->id)
                ->first();

            return is_null($activity);
        })->each(function (Registration $registration) {
            event(new RegistrationOpensSoon($registration));
        });

        $open = $registrations->filter(function (Registration $registration) {
            return $registration->opens_at->isPast();
        })->filter(function (Registration $registration) {
            $activity = $this->activity
                ->where('type', 'tournament.registration.is_open')
                ->where('resource_id', $registration->tournament->id)
                ->first();

            return is_null($activity);
        })->each(function (Registration $registration) {
            event(new RegistrationIsOpen($registration));
        });

        $registrations = $this->registration
            ->where('closes_at', '>', Carbon::now()->subDay(1))
            ->where('closes_at', '<=', Carbon::now()->addDays(3))
            ->get();

        $closesSoon = $registrations->filter(function (Registration $registration) {
            return $registration->closes_at->isTomorrow();
        })->filter(function (Registration $registration) {
            $activity = $this->activity
                ->where('type', 'tournament.registration.closes_soon')
                ->where('resource_id', $registration->tournament->id)
                ->first();

            return is_null($activity);
        })->each(function (Registration $registration) {
            event(new RegistrationClosesSoon($registration));
        });

        // check for registrations that closed
    }
}
