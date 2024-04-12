<?php

namespace DGTournaments\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTournamentRequest extends FormRequest
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
            'additional_course_information' => '',
            'image' => 'image',
            'hashtag' => 'max:60',
            'course_ids' => 'array',
            'latitude' => 'numeric',
            'longitude' => 'numeric'
        ];
    }
}
