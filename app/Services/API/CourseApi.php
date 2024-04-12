<?php
/**
 * Created by PhpStorm.
 * User: philmareu
 * Date: 7/25/17
 * Time: 11:41 AM
 */

namespace DGTournaments\Services\API;


use DGTournaments\Models\DataSource;
use DGTournaments\Services\API\Contracts\CourseApiInterface;
use DGTournaments\Services\API\Responses\CoursesResponse;

class CourseApi implements CourseApiInterface
{
    protected $channelApi;

    public function __construct(DataSource $dataSource)
    {
        $apiClass = $dataSource->api_class;
        $this->channelApi = new $apiClass;
    }

    static public function make(DataSource $dataSource)
    {
        return new static($dataSource);
    }

    public function getCourses() : CoursesResponse
    {
        return $this->channelApi->getCourses();
    }
}