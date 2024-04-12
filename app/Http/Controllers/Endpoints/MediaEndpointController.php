<?php

namespace DGTournaments\Http\Controllers\Endpoints;

use DGTournaments\Events\MediaSaved;
use DGTournaments\Http\Requests\Endpoints\DestroyMediaRequest;
use DGTournaments\Http\Requests\Endpoints\StoreMediaRequest;
use DGTournaments\Http\Requests\Endpoints\UpdateMediaRequest;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\Upload;
use Illuminate\Http\Request;
use DGTournaments\Http\Controllers\Controller;

class MediaEndpointController extends Controller
{
    protected $upload;

    public function __construct(Upload $upload)
    {
        $this->upload = $upload;
    }

    public function store(StoreMediaRequest $request, Tournament $tournament)
    {
        $upload = $this->upload->find($request->uploaded_id);
        $upload->update(['title' => $request->title]);

        $tournament->media()->save($upload);
        $tournament->touch();

        event(new MediaSaved($tournament));

        return $tournament->media;
    }

    public function update(UpdateMediaRequest $request, Tournament $tournament)
    {
        $tournament->media()->detach($request->id);
        $upload = $this->upload->find($request->uploaded_id);
        $upload->update(['title' => $request->title]);

        $tournament->media()->save($upload);
        $tournament->touch();

        return $tournament->media;
    }

    public function destroy(DestroyMediaRequest $request, Tournament $tournament, $uploadId)
    {
        $tournament->media()->detach($uploadId);
        $tournament->touch();

        return $tournament->media;
    }
}
