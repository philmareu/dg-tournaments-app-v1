<?php

namespace DGTournaments\Services\API\Payloads;


use DGTournaments\Services\API\Exceptions\PayloadValuesInvalidException;

class CourseDataPayload extends Payload
{
    protected $keys = [
        'id',
        'name',
        'address',
        'address_2',
        'city',
        'state_province',
        'country',
        'description',
        'directions',
        'length',
        'latitude',
        'longitude'
    ];

    protected $nullable = [
        'address_2',
        'length',
        'state_province'
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
    }
}