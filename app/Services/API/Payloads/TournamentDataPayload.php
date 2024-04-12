<?php

namespace DGTournaments\Services\API\Payloads;


use Carbon\Carbon;
use DGTournaments\Models\Classes;
use DGTournaments\Models\Format;
use DGTournaments\Models\TournamentFormat;
use DGTournaments\Models\PdgaClass;
use DGTournaments\Models\PdgaTier;
use DGTournaments\Services\API\Exceptions\PayloadInvalidException;
use DGTournaments\Services\API\Exceptions\PayloadValuesInvalidException;
use Illuminate\Support\Collection;

class TournamentDataPayload extends Payload
{
    protected $keys = [
        'id',
        'name',
        'city',
        'state_province',
        'country',
        'start', // Carbon
        'end', // Carbon
        'pdga_tiers', // Collection of Tiers
        'classes', // Collection of Classes
        'format', // Format
        'latitude',
        'longitude',
        'email',
        'director',
        'updated_at'
    ];

    protected $nullable = [
        'state_province',
        'latitude',
        'longitude'
    ];

    public function verifyPayload()
    {
        $this->each(function($value, $key) {
            if(! in_array($key, $this->keys))
            {
                $message = "The key '$key' was not found";
                throw new PayloadValuesInvalidException($message);
            }
        });

        foreach ($this->nullable as $key)
        {
            if($this->get($key) == '') $this->offsetSet($key, null);
        }

        if(! $this->get('format') instanceof Format) throw new PayloadInvalidException('Format must be and instance of Format');

        if(! $this->get('start') instanceof Carbon) throw new PayloadInvalidException('Start must be and instance of Carbon');

        if(! $this->get('end') instanceof Carbon) throw new PayloadInvalidException('End must be and instance of Carbon');

        if(! $this->get('updated_at') instanceof Carbon) throw new PayloadInvalidException('Updated field must be and instance of Carbon');

        $this->get('pdga_tiers')->each(function($tier) {
            if(! $tier instanceof PdgaTier) throw new PayloadInvalidException('Tier must be and instance of Tier');
        });

        $this->get('classes')->each(function($class) {
            if(! $class instanceof Classes) throw new PayloadInvalidException('Class must be and instance of Class');
        });
    }

    public function tiers() : Collection
    {
        return $this->get('pdga_tiers');
    }

    public function classes() : Collection
    {
        return $this->get('classes');
    }
}