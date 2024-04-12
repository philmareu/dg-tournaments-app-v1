<?php

namespace DGTournaments\Helpers;


use Carbon\Carbon;
use function GuzzleHttp\Psr7\parse_query;

class AlgoliaQuery
{
    protected $queryString;

    protected $parameters;

    public function __construct($queryString)
    {
        $this->queryString = $queryString;
        $this->setParameters();
    }

    public function parametersArray()
    {
        return $this->parameters;
    }

    public function north()
    {
        return $this->checkIfExists($this->getNumericRefinement('latitude')['<'][0]);
    }

    public function east()
    {
        return $this->checkIfExists($this->getNumericRefinement('longitude')['<'][0]);
    }

    public function south()
    {
        return $this->checkIfExists($this->getNumericRefinement('latitude')['>'][0]);
    }

    public function west()
    {
        return $this->checkIfExists($this->getNumericRefinement('longitude')['>'][0]);
    }

    public function formats()
    {
        return $this->checkIfExists($this->getDisjunctiveFacetsRefinement('format.title'));
    }

    public function classes()
    {
        return $this->checkIfExists($this->getDisjunctiveFacetsRefinement('classes.title'));
    }

    public function from()
    {
        $stringArray = parse_query($this->queryString);

        if(! isset($stringArray['nR[year_month][>=][0]'])) return null;

        return Carbon::createFromFormat('Ym', $stringArray['nR[year_month][>=][0]'])->startOfMonth();
    }

    public function to()
    {
        $stringArray = parse_query($this->queryString);

        if(! isset($stringArray['nR[year_month][<=][0]'])) return null;

        return Carbon::createFromFormat('Ym', $stringArray['nR[year_month][<=][0]'])->endOfMonth();
    }

    public function pdgaTiers()
    {
        return $this->checkIfExists($this->getDisjunctiveFacetsRefinement('pdga_tiers.code'));
    }

    public function specialEventTypes()
    {
        return $this->checkIfExists($this->getDisjunctiveFacetsRefinement('special_event_types.title'));
    }

    public function sanctioned()
    {
        return $this->checkIfExists($this->getDisjunctiveFacetsRefinement('sanctioned'));
    }

    public function setParameters()
    {
        parse_str($this->queryString, $parameters);

        $this->parameters = $parameters;
    }

    public function getNumericRefinement($facet)
    {
        if(isset($this->parameters['nR'][$facet])) return $this->parameters['nR'][$facet];

        return null;
    }

    private function checkIfExists($value)
    {
        if(! is_null($value)) return $value;

        return null;
    }

    public function getDisjunctiveFacetsRefinement($facet)
    {
        if(isset($this->parameters['dFR'][$facet])) return $this->parameters['dFR'][$facet];

        return null;
    }
}