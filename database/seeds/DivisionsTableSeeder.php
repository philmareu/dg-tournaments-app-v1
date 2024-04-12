<?php

use DGTournaments\Models\Classes;
use DGTournaments\Models\Division;
use Illuminate\Database\Seeder;

class DivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Division::class)->create([
            'title' => 'MPO',
            'class_id' => 1
        ]);
    }
}
