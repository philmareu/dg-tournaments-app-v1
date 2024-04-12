<?php

use DGTournaments\Models\User\UserEmailNotificationType;
use Illuminate\Database\Seeder;

class EmailNotificationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(UserEmailNotificationType::class)->create([
            'label' => 'PDGA rating updates'
        ]);

        factory(UserEmailNotificationType::class)->create([
            'label' => 'Important updates from tournaments I follow'
        ]);
    }
}
