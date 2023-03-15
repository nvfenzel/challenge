<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Player;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'item_id',
    ];

    public function player_stock()
    {
        return $this->belongsTo(Player::class);
    }
}
