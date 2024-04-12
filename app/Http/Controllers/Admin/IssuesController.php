<?php

namespace DGTournaments\Http\Controllers\Admin;

use DGTournaments\Http\Requests\Admin\StoreIssueRequest;
use DGTournaments\Http\Requests\Admin\UpdateIssueRequest;
use DGTournaments\Models\Issue;
use DGTournaments\Models\Tournament;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IssuesController extends Controller
{
    protected $issue;

    public function __construct(Issue $issue)
    {
        $this->issue = $issue;
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('admin.tournaments.issues.index')
            ->with('issues', $this->issue->all()->load('tournament'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreIssueRequest $request, Tournament $tournament)
    {
        $issue = new Issue($request->only('issue'));
        $issue->tournament()->associate($tournament);
        $issue->user()->associate(Auth::user());
        $issue->save();

        return redirect()->route('tournaments.edit', $tournament->tournament_id)->with('success', 'Issue Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     */
    public function edit(Issue $issue)
    {
        return view('admin.tournaments.issues.edit')
            ->with('issue', $issue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateIssueRequest $request, Issue $issue)
    {
        $issue->update($request->all());
        $issue->user()->associate(Auth::user())->save();

        return redirect()->route('tournaments.edit', $issue->tournament->tournament_id)->with('success', 'Issue Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        $issue->delete();

        return redirect()->route('tournaments.edit', $issue->tournament->tournament_id)->with('success', 'Issue Deleted');
    }
}
