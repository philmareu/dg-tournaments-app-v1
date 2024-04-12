<?php

use DGTournaments\Models\FlagType;
use Illuminate\Database\Seeder;

class FlagTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(FlagType::class)->create([
            'id' => 1,
            'title' => 'Needs Lat/Lng'
        ]);

        factory(FlagType::class)->create([
            'id' => 2,
            'title' => 'Needs Course'
        ]);

        factory(FlagType::class)->create([
            'id' => 3,
            'title' => 'Needs Registration Link'
        ]);

        factory(FlagType::class)->create([
            'id' => 4,
            'title' => 'Potential Women\'s only event'
        ]);

        factory(FlagType::class)->create([
            'id' => 5,
            'title' => 'Potential Junior\'s only event'
        ]);
    }
}
