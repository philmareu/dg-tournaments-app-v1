<?php

use DGTournaments\Models\Format;
use Illuminate\Database\Seeder;

class FormatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Format::class)->create([
            'id' => 1,
            'title' => 'Singles',
            'code' => 'S'
        ]);

        factory(Format::class)->create([
            'id' => 2,
            'title' => 'Doubles',
            'code' => 'D'
        ]);

        factory(Format::class)->create([
            'id' => 3,
            'title' => 'University',
            'code' => 'U'
        ]);

        factory(Format::class)->create([
            'id' => 4,
            'title' => 'Team',
            'code' => 'T'
        ]);

        factory(Format::class)->create([
            'id' => 5,
            'title' => 'Match Play',
            'code' => 'P'
        ]);
    }
}
