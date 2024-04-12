<?php

namespace DGTournaments\Http\Requests\Endpoints;

use Illuminate\Foundation\Http\FormRequest;

class StoreSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'url' => 'required',
            'wants_notification' => 'boolean',
            'frequency' => 'required_if:wants_notification,1|in:daily,weekly'
        ];
    }
}
