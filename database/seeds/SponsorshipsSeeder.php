<?php

use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\Tournament;
use Illuminate\Database\Seeder;

class SponsorshipsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(range(1, 40))->each(function() {
            factory(Sponsorship::class)->create([
                'tournament_id' => Tournament::inRandomOrder()->first()
            ]);
        });
    }
}
