<?php

namespace DGTournaments\Console\Commands;

use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use Illuminate\Console\Command;

class AutoAssignTournaments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournaments:auto-assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically assign tournaments to users';

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
        $users = User::all();

        $users->each(function(User $user) {
            $tournaments = Tournament::where('authorization_email', $user->email)->get();
            $user->managing()->sync($tournaments->pluck('id')->merge($user->managing->pluck('id')));
        });
    }
}
