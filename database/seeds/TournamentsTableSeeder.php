<?php

use DGTournaments\Models\Sponsorship;
use DGTournaments\Models\Division;
use DGTournaments\Models\PdgaTier;
use DGTournaments\Models\SpecialEventType;
use DGTournaments\Models\StripeAccount;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\Classes;
use DGTournaments\Models\Format;
use DGTournaments\Models\PlayerPack;
use DGTournaments\Models\PlayerPackItem;
use DGTournaments\Models\Schedule;
use DGTournaments\Models\Upload;
use DGTournaments\Models\User\User;
use Illuminate\Database\Seeder;

class TournamentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = Classes::all();
        $pdgaTiers = PdgaTier::all();
        $formats = Format::all();
        $specialEventTypes = SpecialEventType::all();

        $names = [
            'Windstorm Open',
            'Green Meadow Sunshine Series',
            'Frozen Lake Open',
            'Trees Be Damned Breaker Series',
            'Windy Brush Open',
            '2018 Free the Disc Series Opener'
        ];

        foreach ($names as $name)
        {
            $tournament = factory(Tournament::class)->create([
                'name' => $name,
                'slug' => str_slug($name),
                'poster_id' => null
            ]);

            $tournament->format()->associate($formats->random());
            $tournament->specialEventTypes()->saveMany($specialEventTypes->random(rand(1, 2)));
            $tournament->classes()->saveMany($classes->random(rand(1, 2)));
            $tournament->save();
        }

//        $tournament = factory(Tournament::class)->create([
//            'name' => 'DGT Open Unsanctioned',
//            'slug' => 'dgt-open-unsanctioned',
//            'poster_id' => null
//        ]);
//
//        $tournament->managers()->save(User::find(1));
//        $tournament->stripeAccount()->associate(StripeAccount::find(1))->save();
//        $tournament->format()->associate($formats->random());
//        $tournament->specialEventTypes()->saveMany($specialEventTypes->random(rand(0, 2)));
//        $tournament->classes()->saveMany($classes->random(rand(1, 3)));
//        $tournament->save();

//        $playerPack = factory(TournamentPlayerPack::class)->make();
//        $tournament->playerPacks()->save($playerPack);
//        $playerPack->items()->save(factory(TournamentPlayerPackItem::class)->make());
//        $playerPack->items()->save(factory(TournamentPlayerPackItem::class)->make());
//        $playerPack->items()->save(factory(TournamentPlayerPackItem::class)->make());
//        $tournament->schedule()->save(factory(TournamentSchedule::class)->create());
//        $tournament->schedule()->save(factory(TournamentSchedule::class)->create());
//        $tournament->schedule()->save(factory(TournamentSchedule::class)->create());
//        $tournament->schedule()->save(factory(TournamentSchedule::class)->create());
//        $tournament->sponsorships()->save(factory(Sponsorship::class)->create());
//        $tournament->sponsorships()->save(factory(Sponsorship::class)->create());

//        factory(Tournament::class, 40)->create(['format_id' => rand(1, 5)])->each(function(Tournament $tournament) use ($formats, $pdgaTiers, $specialEventTypes, $classes) {
//            $tournament->format()->associate($formats->first());
//            $tournament->pdgaTiers()->save($pdgaTiers->first());
//            $tournament->specialEventTypes()->save($specialEventTypes->first());
//            $tournament->classes()->save($classes->first());
//            $tournament->save();
//        });
    }
}
