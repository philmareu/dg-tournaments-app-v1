<?php

namespace DGTournaments\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTournamentScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $schedule = $this->route('schedule');

        return $schedule && $this->user()->hasAccessToTournament($schedule->tournament_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date' => 'required|date_format:n-j-Y',
            'start' => 'nullable|date_format:g:i A|required_with:end',
            'end' => 'nullable|date_format:g:i A',
            'summary' => 'required|max:255'
        ];
    }
}
