<?php

namespace DGTournaments\Models;

use DGTournaments\Events\PlayerPackItemSaved;
use Illuminate\Database\Eloquent\Model;

class PlayerPackItem extends Model
{
    protected $fillable = [
        'title'
    ];

    protected $touches = [
        'playerPack'
    ];

    protected $dispatchesEvents = [
        'created' => PlayerPackItemSaved::class,
        'updated' => PlayerPackItemSaved::class
    ];

    public function playerPack()
    {
        return $this->belongsTo(PlayerPack::class);
    }
}
