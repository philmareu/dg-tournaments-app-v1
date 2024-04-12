<?php

use DGTournaments\Models\Charge;
use DGTournaments\Models\Order;
use DGTournaments\Models\OrderSponsorship;
use DGTournaments\Models\Sponsor;
use DGTournaments\Models\User\User;
use DGTournaments\Models\StripeAccount;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'name' => 'Dev',
            'email' => 'dev@dev.com',
            'password' => bcrypt('password'),
            'activated' => 1,
            'is_admin' => 1
        ]);

        $user->stripeAccounts()->save(factory(StripeAccount::class)->make([
            'display_name' => 'Test Account 1',
            'stripe_user_id' => env('STRIPE_TEST_ACCOUNT_1_ACCOUNT')
        ]));
        $user->stripeAccounts()->save(factory(StripeAccount::class)->make([
            'display_name' => 'Test Account 2',
            'stripe_user_id' => env('STRIPE_TEST_ACCOUNT_2_ACCOUNT')
        ]));

//        $user->sponsors()->save(factory(Sponsor::class)->create());
//        $user->sponsors()->save(factory(Sponsor::class)->create());
//        $user->sponsors()->save(factory(Sponsor::class)->create());
//
//        $order = factory(Order::class)->states('paid')->create(['user_id' => $user->id]);
//        $order->sponsorships()->saveMany(factory(OrderSponsorship::class, 2)->create());
//        factory(Charge::class)->states('successful')->create(['order_id' => $order->id]);
//
//        $order = factory(Order::class)->states('paid')->create(['user_id' => $user->id]);
//        $order->sponsorships()->saveMany(factory(OrderSponsorship::class, 2)->create());
//        factory(Charge::class)->states('successful')->create(['order_id' => $order->id]);
    }
}
