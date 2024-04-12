<?php

namespace DGTournaments\Http\Requests\Manager;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlayerPackItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $playerPackItem = $this->route('playerPackItem');

        return $playerPackItem && $this->user()->hasAccessToTournament($playerPackItem->playerPack->tournament_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255'
        ];
    }
}
