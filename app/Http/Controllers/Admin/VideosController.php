<?php

namespace DGTournaments\Http\Controllers\Admin;

use Alaouy\Youtube\Facades\Youtube;
use Carbon\Carbon;
use DGTournaments\Http\Requests\Admin\CreateVideoRequest;
use DGTournaments\Http\Requests\Admin\UpdateVideoRequest;
use DGTournaments\Models\Tournament;
use DGTournaments\Models\Video;
use Illuminate\Http\Request;

use DGTournaments\Http\Requests;
use DGTournaments\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class VideosController extends Controller
{
    protected $video;

    public function __construct(Video $video)
    {
        $this->video = $video;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $videos = $this->video->with(['courses', 'events'])->get();

        return view('admin.videos.index')
            ->with('videos', $videos);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.videos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateVideoRequest $request)
    {
        $video = $this->video->create($request->only('youtube_video_id'));

        if($request->has('event_ids')) $video->events()->sync($request->event_ids);
        if($request->has('course_ids')) $video->courses()->sync($request->course_ids);

        $this->updateVideoMeta($video);

        return redirect()->route('admin.videos.index');
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        return view('admin.videos.edit')
            ->with('video', $video);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateVideoRequest $request, Video $video)
    {
        $video->update($request->only('youtube_video_id'));

        if($request->has('event_ids')) $video->events()->sync($request->event_ids);
        else $video->events()->sync([]);

        if($request->has('course_ids')) $video->courses()->sync($request->course_ids);
        else $video->courses()->sync([]);

        $this->updateVideoMeta($video);

        return redirect()->route('admin.videos.edit', $video->id)->with('success', 'Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        $video->delete();

        return response()->json(['status' => 'ok']);
    }

    /**
     * @param Video $video
     */
    private function updateVideoMeta(Video $video)
    {
        $meta = Youtube::getVideoInfo($video->youtube_video_id);

        $publishedAt = Carbon::parse($meta->snippet->publishedAt);

        $video->update([
            'published_at' => $publishedAt,
            'title' => $meta->snippet->title
        ]);
    }
}
