<?php

namespace DGTournaments\Http\Requests\Manager;

use DGTournaments\Models\SpecialEventType;
use DGTournaments\Models\Tournament;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateInformationRequest extends FormRequest
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
            'start' => 'required|date_format:n-j-Y',
            'end' => 'required|date_format:n-j-Y',
            'city' => 'required|max:255',
            'state_province' => 'nullable|max:255',
            'country' => 'required|max:255',
            'director' => 'required|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|max:255',
            'description' => 'max:1000',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'format_id' => 'exists:formats,id',
            'poster_id' => 'exists:uploads,id',
            'special_event_type_ids' => 'exists:special_event_types,id',
            'class_ids' => 'exists:classes,id',
            'timezone' => 'required|timezone',
            'paypal' => 'nullable|email'
        ];
    }

    public function messages()
    {
        return [
            'stripe_account_id.required_if' => 'Please select a Stripe account'
        ];
    }
}
