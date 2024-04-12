<?php

namespace DGTournaments\Http\Controllers;

use DGTournaments\Http\Requests\StoreFeedbackRequest;
use DGTournaments\Models\Feedback;
use Illuminate\Support\Facades\Mail;

class FeedbacksController extends Controller
{
    protected $feedback;

    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    public function index()
    {
        return view('pages.feedback');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFeedbackRequest $request)
    {
        $feedback = $this->feedback->create([
            'email' => $request->email,
            'feedback' => nl2br($request->feedback)
        ]);

        Mail::to('admin@dgtournaments.com')->send(new \DGTournaments\Mail\Admin\Feedback($feedback));

        return redirect('contact-us')->with('success', 'Thanks for your feedback!');
    }
}
