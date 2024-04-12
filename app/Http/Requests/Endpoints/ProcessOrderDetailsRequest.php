<?php

namespace DGTournaments\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessOrderDetailsRequest extends FormRequest
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
            'unique' => 'required|exists:orders',
            'email' => 'required|email|max:100' . ($this->request->has('create_account') ? '|unique:users' : ''),
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'password' => 'required_if:create_account,1|min:6',
        ];
    }
}
