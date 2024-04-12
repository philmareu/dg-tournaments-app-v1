<?php

namespace DGTournaments\Console\Commands;

use Carbon\Carbon;
use DGTournaments\Models\DataSource;
use DGTournaments\Models\Tournament;
use DGTournaments\Repositories\Api\TournamentRepository;
use DGTournaments\Services\API\Payloads\TournamentDataPayload;
use DGTournaments\Services\API\Responses\TournamentsResponse;
use DGTournaments\Services\API\TournamentApi;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class UpdateTournaments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournaments:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update tournaments from data sources';

    protected $tournamentRepository;

    protected $dataSource;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TournamentRepository $tournamentRepository, DataSource $dataSource)
    {
        parent::__construct();
        $this->dataSource = $dataSource;
        $this->tournamentRepository = $tournamentRepository;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->dataSource->whereType('tournament')->get()->each(function(DataSource $dataSource) {

            $this->info('Data Source: ' . $dataSource->title);
            $freshListOfTournaments = $this->getTournaments($dataSource)->getPayloads();

            $this->info($freshListOfTournaments->count() . ' total');
            $this->info('Removing unlisted tournaments');
            $this->tournamentRepository->removeUnlisted($dataSource, $freshListOfTournaments);

            $this->info('Updating existing tournaments');
            $this->tournamentRepository->updateExisting($freshListOfTournaments, $dataSource);

            $this->info('Creating new tournaments');
            $this->tournamentRepository->createNew($dataSource, $freshListOfTournaments);

            $dataSource->update(['retrieved_at' => Carbon::now()]);
        });
    }

    /**
     * @param DataSource $dataSource
     */
    private function getTournaments(DataSource $dataSource) : TournamentsResponse
    {
        if (Cache::has($dataSource->slug . '.api.tournaments')) {
            dump('From cache ... ');
            $tournaments = Cache::get($dataSource->slug . '.api.tournaments');
        } else {
            $from = Carbon::createFromFormat('Y-m-d', config('services.pdga.from'));
            $to = Carbon::createFromFormat('Y-m-d', config('services.pdga.to'));

            $tournaments = TournamentApi::make($dataSource)->getTournamentsByRange($from, $to);
            Cache::put($dataSource->slug . '.api.tournaments', $tournaments, 120);
        }

        return $tournaments;
    }
}
