<?php

namespace DGTournaments\Http\Requests\Endpoints;

use Illuminate\Foundation\Http\FormRequest;

class DestroySearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $search = $this->route('search');

        return $search && $this->user()->searches->where('id', $search->id)->isNotEmpty();
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
