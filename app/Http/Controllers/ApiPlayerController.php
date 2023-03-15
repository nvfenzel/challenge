<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\User;
use App\Models\Items;

use Illuminate\Support\Facades\Auth;

class ApiPlayerController extends Controller
{
    
    public function auth()
    {
        return (Player::where('user_id', Auth::id())->first()->status);
        // return Auth::user();
    }

}