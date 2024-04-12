<?php

namespace DGTournaments\Http\Requests\Endpoints\Tournament;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSponsorshipRequest extends FormRequest
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
            'title' => 'required|max:255',
            'tier' => 'required|integer|max:100',
            'description' => 'max:1000',
            'quantity' => 'required|integer|max:255',
            'cost_in_dollars' => 'required|numeric|max:10000000'
        ];
    }
}
