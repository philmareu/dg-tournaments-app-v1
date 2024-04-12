<?php

namespace DGTournaments\Helpers;


use Carbon\Carbon;

class AlgoliaQueryStringBuilder
{
    protected $query = [
        'q' => '',
        'p' => 0
    ];

    public function setIndex($index)
    {
        $this->query['idx'] = $index;

        return $this;
    }

    public function setGeo($north, $east, $south, $west)
    {
        $this->query['nR']['latitude'] = [
            '<' => [
                $north
            ],
            '>' => [
                $south
            ]
        ];

        $this->query['nR']['longitude'] = [
            '<' => [
                $east
            ],
            '>' => [
                $west
            ]
        ];

        return $this;
    }

    public function setEarliestDate(Carbon $date)
    {
        $this->query['nR']['year_month']['>='][0] = $date->format('Ym');

        return $this;
    }

    public function setLatestDate(Carbon $date)
    {
        $this->query['nR']['year_month']['<='][0] = $date->format('Ym');

        return $this;
    }

    public function setClasses(array $classes)
    {
        $this->query['dFR']['classes.title'] = $classes;

        return $this;
    }

    public function setFormat(array $formats)
    {
        $this->query['dFR']['format.title'] = $formats;

        return $this;
    }

    public function setPdgaTier(array  $tiers)
    {
        $this->query['dFR']['pdga_tiers.code'] = $tiers;

        return $this;
    }

    public function setSanctioned(array $sanctioned)
    {
        $this->query['dFR']['sanctioned'] = $sanctioned;

        return $this;
    }

    public function setSpecialEventTypes(array  $specialEventTypes)
    {
        $this->query['dFR']['special_event_types.title'] = $specialEventTypes;

        return $this;
    }

    public function buildQueryString()
    {
        return http_build_query($this->query);
    }
}