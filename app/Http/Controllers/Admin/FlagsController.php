<?php

namespace DGTournaments\Http\Controllers\Admin;

use Carbon\Carbon;
use DGTournaments\Models\Flag;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class FlagsController extends Controller
{
    public function postpone(Flag $flag, $days)
    {
        if($flag->update(['review_on' => Carbon::now()->addDays($days)])) return response('ok');

        return response('failed', 500);
    }
}
