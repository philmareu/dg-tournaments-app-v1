<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use Carbon\Carbon;
use DGTournaments\Http\Requests\Manager\DestroyTournamentScheduleRequest;
use DGTournaments\Http\Requests\Manager\StoreTournamentScheduleRequest;
use DGTournaments\Http\Requests\Manager\UpdateTournamentScheduleRequest;
use DGTournaments\Models\Schedule;
use DGTournaments\Models\Tournament;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class ScheduleEndpointController extends Controller
{
    public function get(Tournament $tournament)
    {
        return $tournament->schedule->sortBy(function($item) {
            return Carbon::createFromFormat('Y-m-dg:i A', $item->date->format('Y-m-d') . $item->start->format('g:i A'))->format('U');
        })->groupBy(function($item) {
            return $item->date->format('Y-m-d');
        });
    }

    public function store(StoreTournamentScheduleRequest $request, Tournament $tournament)
    {
        $tournament->schedule()->create($this->buildAttributeArray($request->all()));

        return $tournament->schedule->groupedByDay();
    }

    public function update(UpdateTournamentScheduleRequest $request, Schedule $schedule)
    {
        $schedule->update($this->buildAttributeArray($request->all()));

        return $schedule->tournament->schedule->groupedByDay();
    }

    public function destroy(DestroyTournamentScheduleRequest $request, Schedule $schedule)
    {
        $schedule->delete();

        return $schedule->tournament->schedule->groupedByDay();
    }

    private function buildAttributeArray($attributes)
    {
        return [
            'date' => Carbon::createFromFormat('n-j-Y', $attributes['date']),
            'start' => is_null($attributes['start']) ? null : Carbon::createFromFormat('g:i A', $attributes['start']),
            'end' => is_null($attributes['end']) ? null : Carbon::createFromFormat('g:i A', $attributes['end']),
            'summary' => $attributes['summary'],
            'location' => $attributes['location']
        ];
    }

}
