<?php

use DGTournaments\Models\PdgaTier;
use Illuminate\Database\Seeder;

class PdgaTierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(PdgaTier::class)->create([
            'id' => 1,
            'title' => 'Major',
            'code' => 'M'
        ]);

        factory(PdgaTier::class)->create([
            'id' => 2,
            'title' => 'National Tour',
            'code' => 'NT'
        ]);

        factory(PdgaTier::class)->create([
            'id' => 3,
            'title' => 'A',
            'code' => 'A'
        ]);

        factory(PdgaTier::class)->create([
            'id' => 4,
            'title' => 'B',
            'code' => 'B'
        ]);

        factory(PdgaTier::class)->create([
            'id' => 5,
            'title' => 'C',
            'code' => 'C'
        ]);
    }
}
