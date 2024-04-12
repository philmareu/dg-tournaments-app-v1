<?php

namespace DGTournaments\Providers;

use DGTournaments\Models\SponsorLevel;
use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\TournamentCourse;
use DGTournaments\Models\Sponsor;
use DGTournaments\Policies\Director\TournamentCoursePolicy;
use DGTournaments\Policies\Director\TournamentPolicy;
use DGTournaments\Policies\Manager\SponsorLevelPolicy;
use DGTournaments\Policies\Manager\SponsorshipProductPolicy;
use DGTournaments\Policies\Manager\TournamentSponsorPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
