<?php

namespace DGTournaments\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class DestroyTournamentScheduleRequest extends FormRequest
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
            //
        ];
    }
}
