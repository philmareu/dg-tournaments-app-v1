<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FlagTypesSeeder::class);
        $this->call(FormatsTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(UploadsTableSeeder::class);
        $this->call(PdgaTierSeeder::class);
        $this->call(ClassesTableSeeder::class);
        $this->call(DivisionsTableSeeder::class);
        $this->call(SpecialEventTypesSeeder::class);
        $this->call(TournamentsTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(EmailNotificationTypesSeeder::class);
        $this->call(OrdersSeeder::class);
    }
}
