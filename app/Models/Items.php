<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bota;
use App\Models\Armadura;
use App\Models\Arma;


class Items extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'pt_defense',
        'pt_attack',
        'type',
    ];

    public function arma()
    {
        return $this->hasMany(Arma::class);
    }

    public function armadura()
    {
        return $this->hasMany(Armadura::class);
    }

    public function bota()
    {
        return $this->hasMany(Bota::class);
    }
}
