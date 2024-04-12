<?php

namespace DGTournaments\Console\Commands\Search;

use AlgoliaSearch\Client;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RemovePastEntries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:remove-past';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove past tournaments and sponsorships';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new Client(config('scout.algolia.id'), config('scout.algolia.secret'));

        $index = $client->initIndex('tournaments');

        $tournaments = $index->search('', [
            'filters' => 'end_timestamp < ' . Carbon::now()->subDay()->format('U'),
            'attributesToRetrieve' => [
                'objectID'
            ],
            'hitsPerPage' => 200
        ]);

        collect($tournaments['hits'])->each(function($result) use ($index) {
            $index->deleteObject($result['objectID']);
        });
    }
}
