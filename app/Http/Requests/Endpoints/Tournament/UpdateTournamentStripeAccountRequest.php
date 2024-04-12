<?php

namespace DGTournaments\Http\Requests\Endpoints\Tournament;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTournamentStripeAccountRequest extends FormRequest
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
            'stripe_account_id' => 'required|in:' . $this->user()->stripeAccounts()->pluck('id')->implode(',')
        ];
    }
}
