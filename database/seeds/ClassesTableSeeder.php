<?php

use DGTournaments\Models\Classes;
use Illuminate\Database\Seeder;

class ClassesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Classes::class)->create([
            'title' => 'Pro'
        ]);

        factory(Classes::class)->create([
            'title' => 'Am'
        ]);
    }
}
