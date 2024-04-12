<?php

namespace DGTournaments\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class StoreTournamentSponsorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $sponsorship = $this->route('sponsorship');

        return $sponsorship && $this->user()->hasAccessToTournament($sponsorship->tournament_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sponsor_id' => 'required|exists:sponsors,id'
        ];
    }
}
