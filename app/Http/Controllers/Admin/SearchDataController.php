<?php

namespace DGTournaments\Http\Controllers\Admin;

use DGTournaments\Models\Course;
use DGTournaments\Models\Tournament;
use Illuminate\Http\Request;

use DGTournaments\Http\Requests;
use DGTournaments\Http\Controllers\Controller;

class SearchDataController extends Controller
{
    public function events()
    {
        return Tournament::get();
    }

    public function courses()
    {
        return Course::get(['course_id', 'course_name', 'city', 'state_province', 'country']);
    }
}
