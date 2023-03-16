<?php

namespace App\Http\Controllers;

use App\Models\Api;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\LastAttack;
use App\Models\Outfit;
use App\Models\Player;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ApiController extends Controller
{
   public function register(Request $request)
   {
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'type' => ['required', 'string', 'max:255', 'in:human,zombie'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed'],
    ]);

    $user_create = User::create([
        'name' => $request->name,
        'type' => $request->type,
        'email' => $request->email,
        'password' => Hash::make($request->password),
    ]);

    Player::create([
        'user_id' => $user_create->id,
        'type' => $request->type,
    ]);

    $id_player = Player::where('user_id', $user_create->id)->first()->id;

    Outfit::create([
        'player_id' => $id_player
    ]);

    LastAttack::create([
        'player_id' => $id_player
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return response()->json($user->createToken($request->email)->plainTextToken);
   }

   public function login(Request $request)
   {

    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return response()->json($user->createToken($request->email)->plainTextToken);
   }
}
