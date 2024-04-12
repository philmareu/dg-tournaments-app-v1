<?php

namespace DGTournaments\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class TournamentCourse extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return $this->load(['tournament.managers'])->toArray();
    }
}
