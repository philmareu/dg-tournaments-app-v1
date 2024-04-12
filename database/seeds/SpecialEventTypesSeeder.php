<?php

use DGTournaments\Models\SpecialEventType;
use Illuminate\Database\Seeder;

class SpecialEventTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(SpecialEventType::class)->create([
            'id' => 1,
            'title' => 'Women\'s Only',
            'slug' => 'women-only'
        ]);

        factory(SpecialEventType::class)->create([
            'id' => 2,
            'title' => 'Charity/Fundraiser',
            'slug' => 'charity'
        ]);

        factory(SpecialEventType::class)->create([
            'id' => 3,
            'title' => 'Junior\'s Only',
            'slug' => 'junior-only'
        ]);
    }
}
