<?php

namespace DGTournaments\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class StorePlayerPackItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $playerPack = $this->route('playerPack');

        return $playerPack && $this->user()->hasAccessToTournament($playerPack->tournament_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required:max:255'
        ];
    }
}
