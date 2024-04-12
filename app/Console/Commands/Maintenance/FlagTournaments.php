<?php

namespace DGTournaments\Console\Commands\Maintenance;

use Carbon\Carbon;
use DGTournaments\Models\Tournament;
use Illuminate\Console\Command;

class FlagTournaments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:flag-tournaments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check all the upcoming tournaments and flag if needed';

    protected $tournament;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Tournament $tournament)
    {
        parent::__construct();
        $this->tournament = $tournament;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $tournaments = $this->tournament
            ->where('start', '>', Carbon::now()->subDay())
            ->get();

        $tournaments->each(function(Tournament $tournament) {

            $existingFlags = $tournament->flags;
            
            if(! $tournament->hasLatLng() && $existingFlags->where('id', 1)->isEmpty())
                $tournament->flags()->attach(1, ['notes' => 'Added from maintenance script']);

            if($tournament->courses->isEmpty() && $existingFlags->where('id', 2)->isEmpty())
                $tournament->flags()->attach(2, [
                    'notes' => 'Added from maintenance script',
                    'review_on' => $tournament->start->subMonths(3)
                ]);

            if(is_null($tournament->registration->url) && $existingFlags->where('id', 3)->isEmpty())
                $tournament->flags()->attach(3, [
                    'notes' => 'Added from maintenance script',
                    'review_on' => $tournament->start->subMonths(3)
                ]);

            $terms = [
                'Ladies',
                'ladies',
                'Women',
                'women',
                'WGE',
                'wge',
                'Diva',
                'diva',
                'chick',
                'Chick',
                'girl',
                'Girl',
                'Honeys',
                'honeys',
                'Miss ',
                'miss ',
                'Queen',
                'queen'
            ];

            if(str_contains($tournament->name, $terms)
                && $existingFlags->where('id', 4)->isEmpty()
                && $tournament->specialEventTypes->where('slug', 'women-only')->isEmpty())
                $tournament->flags()->attach(4, ['notes' => 'Added from maintenance script']);

            $terms = [
                'Junior',
                'junior',
                'kids',
                'Kids',
                'Youth',
                'youth'
            ];

            if(str_contains($tournament->name, $terms)
                && $existingFlags->where('id', 5)->isEmpty()
                && $tournament->specialEventTypes->where('slug', 'junior-only')->isEmpty())
                $tournament->flags()->attach(5, ['notes' => 'Added from maintenance script']);

        });
    }
}
