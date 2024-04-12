<?php

use DGTournaments\Models\Upload;
use Illuminate\Database\Seeder;

class UploadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Upload::class)->create([
            'filename' => '2scd7Ugf0VqaPrrhIGR0Cvph5otY0g0L938ykVV5.jpeg'
        ]);

        factory(Upload::class)->create([
            'filename' => 'lPitFqzgDlTgS5QKkLgH1GPfzK4pSBPG4TFoMelY.jpeg'
        ]);
    }
}
