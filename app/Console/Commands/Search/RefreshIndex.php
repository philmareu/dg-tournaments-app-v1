<?php

namespace DGTournaments\Console\Commands\Search;

use AlgoliaSearch\Client;
use Illuminate\Console\Command;

class RefreshIndex extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:refresh {index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear index and update settings';

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
        $this->call('search:clear-index', ['index' => $this->argument('index')]);
        $this->call('search:update-settings', ['index' => $this->argument('index')]);
    }
}
