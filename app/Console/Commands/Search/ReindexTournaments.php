<?php

namespace DGTournaments\Console\Commands\Search;

use DGTournaments\Models\Tournament;
use Illuminate\Console\Command;

class ReindexTournaments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex-tournaments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push all valid tournaments from DB to search engine';

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
        $this->call('search:clear-index', ['index' => 'tournaments']);
        $this->call('scout:import', ['model' => Tournament::class]);
    }
}
