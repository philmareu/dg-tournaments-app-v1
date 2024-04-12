<?php namespace DGTournaments\Repositories\Api;

use Carbon\Carbon;
use DGTournaments\Events\TournamentAutoAssigned;
use DGTournaments\Mail\User\TournamentAutoAssignedMailable;
use DGTournaments\Models\DataSource;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\User\User;
use DGTournaments\Services\API\Payloads\TournamentDataPayload;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class TournamentRepository
{
    protected $tournament;

    protected $fields = [
        'name',
        'latitude',
        'longitude'
    ];

    protected $dateFields = [
        'start',
        'end'
    ];

    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * @param $existingTournaments
     * @param $freshListOfTournaments
     */
    public function removeUnlisted(DataSource $dataSource, $freshListOfTournaments)
    {
        $dataSource->tournaments->filter(function (Tournament $tournament) use ($freshListOfTournaments) {
            return $freshListOfTournaments->where('id', $tournament->data_source_tournament_id)->isEmpty();
        })->each(function (Tournament $tournament) {
            $tournament->delete();
        });
    }

    /**
     * @param DataSource $dataSource
     * @param $freshListOfTournaments
     */
    public function updateExisting(Collection $freshListOfTournaments, DataSource $dataSource)
    {
        $currentTournaments = $this->tournament
            ->where('data_source_id', $dataSource->id)
            ->whereIn('data_source_tournament_id', $freshListOfTournaments->pluck('id'))
            ->withTrashed()
            ->get();

        $currentTournaments->each(function(Tournament $currentTournament) use ($freshListOfTournaments) {

            $apiTournament = $freshListOfTournaments->where('id', $currentTournament->data_source_tournament_id)->first();

            if(! is_null($apiTournament)) $this->updateTournament($currentTournament, $apiTournament);
        });
    }

    /**
     * @param DataSource $dataSource
     * @param $freshListOfTournaments
     */
    public function createNew(DataSource $dataSource, $freshListOfTournaments)
    {
        $candidates = $this->extractNonExisting($freshListOfTournaments, $dataSource);

        $candidates->each(function (TournamentDataPayload $tournament) use ($dataSource) {

            $this->createNewTournament($dataSource, $tournament);
        });
    }

    public function extractNonExisting(Collection $tournaments, DataSource $dataSource)
    {
        return $tournaments->filter(function (TournamentDataPayload $tournament) use ($dataSource) {
            return is_null($this->tournament
                ->where('data_source_tournament_id', $tournament->get('id'))
                ->where('data_source_id', $dataSource->id)
                ->first());
        });
    }

    public function createNewTournament(DataSource $dataSource, TournamentDataPayload $tournament)
    {
        $newTournament = new Tournament(
            array_merge(
                [
                    'slug' => str_slug($tournament['name'])
                ],
                $tournament->only(['name', 'city', 'state_province', 'country', 'start', 'end', 'latitude', 'longitude', 'director'])->toArray()
            )
        );

        $newTournament->format()->associate($tournament->get('format'));
        $newTournament->save();

        $newTournament->dataSource()->associate($dataSource);
        $newTournament->pdgaTiers()->sync($tournament->tiers()->pluck('id'));
        $newTournament->classes()->sync($tournament->classes()->pluck('id'));
        $newTournament->data_source_tournament_id = $tournament['id'];
        $newTournament->authorization_email = $tournament['email'];
        $newTournament->save();

        $user = User::where('email', $tournament['email'])->first();
        if(! is_null($user))
        {
            $user->managing()->attach($newTournament->id);
            Mail::to($user->email)->send(new TournamentAutoAssignedMailable($newTournament));

            event(new TournamentAutoAssigned($newTournament, $user));
        }

        return $newTournament;
    }

    /**
     * @param Tournament $currentTournament
     * @param $apiTournament
     */
    function updateTournament(Tournament $currentTournament, TournamentDataPayload $apiTournament)
    {
        $currentTournament->restore();
        $currentTournament->timestamps = false;
        $currentTournament->update(
            array_merge(
                [
                    'slug' => str_slug($apiTournament['name'])
                ],
                $apiTournament->only(['name', 'city', 'state_province', 'country', 'director'])->toArray())
        );

        if ($currentTournament->start->format('Y-m-d') != $apiTournament['start']->format('Y-m-d'))
            $dates['start'] = $apiTournament['start'];

        if ($currentTournament->end->format('Y-m-d') != $apiTournament['end']->format('Y-m-d'))
            $dates['end'] = $apiTournament['end'];

        if(isset($dates)) $currentTournament->update($dates);

        $currentTournament->format()->associate($apiTournament->get('format'));
        $currentTournament->pdgaTiers()->sync($apiTournament->tiers()->pluck('id'));
        $currentTournament->classes()->sync($apiTournament->classes()->pluck('id'));
        $currentTournament->authorization_email = $apiTournament['email'];
        $currentTournament->save();
    }
}