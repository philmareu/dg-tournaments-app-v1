<?php namespace DGTournaments\Services\Pdga\Http;

trait Query {

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @return array
     */
    protected function getParameter($key)
    {
        return $this->parameters[$key];
    }

    /**
     * @return array
     */
    protected function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $query
     * @return array
     */
    protected function addParameter(array $parameter)
    {
        return $this->parameters = array_merge($this->parameters, $parameter);
    }

    /**
     * @param array $query
     * @return array
     */
    protected function addParameters(array $parameters)
    {
        // Does this work
        return $this->parameters = array_merge($this->parameters, $parameters);
    }

    /**
     * @param $authorization
     * @return string
     */
    protected function buildRequestQuery(Auth $authorization)
    {
        return $authorization->getAuthorization();
    }
}