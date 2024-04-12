<?php

namespace DGTournaments\Http\Requests\Endpoints\Tournament;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTournamentRegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $registration = $this->route('registration');

        return $registration && $this->user()->hasAccessToTournament($registration->tournament_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'opens_at' => 'required|date_format:n-j-Y',
            'closes_at' => 'nullable|date_format:n-j-Y',
            'url' => 'nullable|url|max:255'
        ];
    }
}
