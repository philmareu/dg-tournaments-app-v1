<?php

namespace DGTournaments\Http\Requests\Admin;

use DGTournaments\Http\Requests\Request;

class UpdateVideoRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'youtube_video_id' => 'required|unique:videos,youtube_video_id,' . $this->segment(3),
            'event_ids' => 'array',
            'course_ids' => 'array'
        ];
    }
}
