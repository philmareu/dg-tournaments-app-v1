<?php

namespace DGTournaments\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class DestroySponsorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $sponsor = $this->route('sponsor');

        return $sponsor && $sponsor->user_id == $this->user()->id;
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
