<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Http\Requests\Endpoints\DestroySearchRequest;
use DGTournaments\Http\Requests\Endpoints\StoreSearchRequest;
use DGTournaments\Http\Requests\Endpoints\UpdateSearchRequest;
use DGTournaments\Models\Search;
use DGTournaments\Repositories\SearchRepository;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserSearchEndpointController extends Controller
{
    protected $searchRepository;

    public function __construct(SearchRepository $searchRepository)
    {
        $this->middleware('auth');
        $this->searchRepository = $searchRepository;
    }

    public function store(StoreSearchRequest $request)
    {
        $this->searchRepository->saveSearch(Auth::user(), $request->all());

        return Auth::user()->load('searches')->searches;
    }

    public function update(UpdateSearchRequest $request, Search $search)
    {
        $search->title = $request->title;
        $search->frequency = $request->frequency;
        $search->wants_notification = $request->has('wants_notification');
        $search->save();

        return $request->user()->load('searches')->searches;
    }

    public function destroy(DestroySearchRequest $request, Search $search)
    {
        $search->delete();

        return $request->user()->load('searches')->searches;
    }
}
