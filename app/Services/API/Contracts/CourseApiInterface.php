<?php

namespace DGTournaments\Services\API\Contracts;


use DGTournaments\Services\API\Responses\CoursesResponse;

interface CourseApiInterface
{
    public function getCourses() : CoursesResponse;
}