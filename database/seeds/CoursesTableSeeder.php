<?php

use DGTournaments\Models\Course;
use DGTournaments\Models\Tournament;
use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tournaments = DGTournaments\Models\Tournament::all();

        $tournaments->each(function(Tournament $tournament) {
            factory(Course::class, 3)->create([
                'latitude' => $tournament->latitude + (rand(0, 10) / 10),
                'longitude' => $tournament->longitude + (rand(0, 10) / 10)
            ]);
        });
    }
}
