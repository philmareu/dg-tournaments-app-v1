<?php

namespace DGTournaments\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerPack extends Model
{
    protected $fillable = [
        'title',
        'description'
    ];

    protected $touches = [
        'tournament'
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function items()
    {
        return $this->hasMany(PlayerPackItem::class, 'player_pack_id');
    }
}
