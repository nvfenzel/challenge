<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Player;
use App\Models\Bota;
use App\Models\Armadura;
use App\Models\Arma;

class Outfit extends Model
{
    use HasFactory;

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function arma()
    {
        return $this->hasOne(Arma::class);
    }

    public function armadura()
    {
        return $this->hasOne(Armadura::class);
    }

    public function bota()
    {
        return $this->hasOne(Bota::class);
    }

}
