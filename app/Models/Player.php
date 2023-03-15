<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Outfit;
use App\Models\LastAttack;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'life',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function outfit()
    {
        return $this->hasOne(Outfit::class);
    }

    public function lastAttack()
    {
        return $this->hasOne(LastAttack::class);
    }
}
