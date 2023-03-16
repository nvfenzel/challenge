<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Player;

class LastAttack extends Model
{
    use HasFactory;


    protected $fillable = [
        'player_id',
        'type_attack',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }
}
