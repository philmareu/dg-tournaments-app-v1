<?php

namespace DGTournaments\Services\Pdga\Helpers;


use Carbon\Carbon;
use DGTournaments\Models\Classes;
use DGTournaments\Models\Format;
use DGTournaments\Models\PdgaTier;
use DGTournaments\Services\API\Contracts\PayloadBuilderInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class PdgaTournamentPayloadBuilder implements PayloadBuilderInterface
{
    /**
     * @var
     */
    protected $tournamentApiData;

    public function __construct($tournamentApiData)
    {
        $this->tournamentApiData = $tournamentApiData;
    }
    
    static public function make($tournamentApiData)
    {
        return new static($tournamentApiData);
    }

    public function payload()
    {
        return [
            'id' => $this->tournamentApiData['tournament_id'],
            'name' => $this->tournamentApiData['tournament_name'],
            'city' => $this->tournamentApiData['city'],
            'state_province' => $this->tournamentApiData['state_prov'],
            'country' => $this->tournamentApiData['country'],
            'start' => Carbon::createFromFormat('Y-m-d', $this->tournamentApiData['start_date'])->setTime(8, 00),
            'end' => Carbon::createFromFormat('Y-m-d', $this->tournamentApiData['end_date'])->setTime(8, 00),
            'pdga_tiers' => $this->getTiers(),
            'classes' => $this->getClasses(),
            'format' => $this->getFormat(),
            'latitude' => $this->tournamentApiData['latitude'],
            'longitude' => $this->tournamentApiData['longitude'],
            'email' => $this->tournamentApiData['event_email'],
            'updated_at' => Carbon::createFromFormat('Y-m-d', $this->tournamentApiData['last_modified']),
            'director' => $this->tournamentApiData['tournament_director']
        ];
    }

    private function getTiers()
    {
        $pdgaTier = new PdgaTier();

        return $pdgaTier->all()->filter(function(PdgaTier $pdgaTier) {
            return strpos($this->tournamentApiData['tier'], $pdgaTier->code) !== false;
        });
    }

    private function getClasses()
    {
        $classes = new Classes;

        return $classes->all()->filter(function(Classes $class) {
            return strpos($this->tournamentApiData['class'], $class->title) !== false;
        });
    }

    private function getFormat()
    {
        $format = Format::where(['code' => $this->tournamentApiData['format']])->first();

        if(is_null($format))
        {
            Log::info('New Format:');
            Log::info($this->tournamentApiData);
        }

        return $format;
    }
}