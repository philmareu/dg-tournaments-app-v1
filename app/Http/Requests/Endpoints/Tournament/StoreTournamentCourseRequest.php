<?php

namespace DGTournaments\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class StoreTournamentCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $tournament = $this->route('tournament');

        return $tournament && $this->user()->hasAccessToTournament($tournament->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255',
            'holes' => 'required|numeric|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|max:255',
            'city' => 'required|max:255',
            'state_province' => 'nullable|max:255',
            'country' => 'required|max:255',
            'course_id' => 'nullable|exists:courses,id',
            'directions' => 'nullable|max:2000',
            'notes' => 'nullable|max:1000'
        ];
    }
}
