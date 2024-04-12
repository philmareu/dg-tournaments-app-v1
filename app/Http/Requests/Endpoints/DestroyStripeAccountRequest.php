<?php

namespace DGTournaments\Http\Requests\Endpoints;

use Illuminate\Foundation\Http\FormRequest;

class DestroyStripeAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $stripeAccount = $this->route('stripeAccount');

        return $stripeAccount && $this->user()->id == $stripeAccount->user_id;
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
