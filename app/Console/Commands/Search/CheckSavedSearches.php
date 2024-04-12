<?php

namespace DGTournaments\Console\Commands\Search;

use DGTournaments\Mail\User\SavedSearchFoundNewTournamentsMailable;
use DGTournaments\Models\Activity;
use DGTournaments\Repositories\SearchRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckSavedSearches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:check-saved-searches';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Find new tournaments and send out notifications';

    protected $searchRepository;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SearchRepository $searchRepository)
    {
        parent::__construct();
        $this->searchRepository = $searchRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Email
        $this->searchRepository->getTournamentNotifications()->each(function($tournamentNotification) {

            Mail::to($tournamentNotification['user']->email)
                ->send(new SavedSearchFoundNewTournamentsMailable($tournamentNotification));
        });

        // Activity
        $this->searchRepository->getTournamentActivities()->each(function($tournamentNotification) {

            $activity = new Activity([
                'type' => 'searches.tournaments.new',
                'data' => json_encode($tournamentNotification['tournaments'])
            ]);

            $tournamentNotification['user']->activities()->save($activity);
            $tournamentNotification['user']->feed()->save($activity);

            // This should mark all of them
            $this->searchRepository->updateSearches($tournamentNotification['user']);
        });
    }
}
