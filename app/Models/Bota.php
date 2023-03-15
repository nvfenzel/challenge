<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Outfit;
use App\Models\Items;

class Bota extends Model
{
    use HasFactory;

    public function outfit()
    {
        return $this->belongsTo(Outfits::class);
    }

    public function item()
    {
        return $this->belongsTo(Items::class);
    }
}
