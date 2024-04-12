<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Http\Requests\Endpoints\CreateRefundRequest;
use DGTournaments\Mail\Admin\RefundRequestMailable;
use DGTournaments\Models\Transfer;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ManageOrderEndpointController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function refund(CreateRefundRequest $request, Transfer $transfer)
    {
        Mail::to('admin@dgtournaments.com')
            ->send(new RefundRequestMailable($request->user(), $transfer, $request->amount));

        return response('Success', 200);
    }
}
