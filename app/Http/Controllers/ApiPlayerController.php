<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\User;
use App\Models\Items;
use App\Models\Outfit;
use App\Models\Bota;
use App\Models\Arma;
use App\Models\Armadura;
use App\Models\LastAttack;

use Illuminate\Support\Facades\Auth;

class ApiPlayerController extends Controller
{
    
    public function active()
    {
        return Player::where('status', 'activo')->get();
    }

    public function attack(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric'],
            'type' => ['required', 'string', 'in:cuerpo,distancia,ulti']
            ]);

        $request->type === 'cuerpo' ? $type_attack_value = 1 : '';
        $request->type === 'distancia' ? $type_attack_value = 0.8 : '';
        $request->type === 'ulti' ? $type_attack_value = 2 : '';

        if (Player::where('id', $request->id)->first()->life < 0) {
            Player::where('id', $request->id)
            ->update([
                'life' => 0,
                'status' => 'inactivo'
            ]);
            return 'No se puede atacar a este jugador';
        }

        if (Player::where('user_id', Auth::id())->first()->ulti === 'denegado' && $request->type === 'ulti') {
            return 'No te tenes permitido utilizar el ULTI';
        }

        if (Player::where('id', $request->id)->first()->id === Player::where('user_id', Auth::id())->first()->id) {
            return 'No te podes atacar vos mismo';
        }
        
        if (Player::where('id', $request->id)->first()->status === 'inactivo') {
            return 'Ese usuario no está disponible';
        } 

        if (Player::where('id', $request->id)->first()->status === 'activo'){

            if (LastAttack::where('player_id', Auth::id())->doesntExist() && $request->type === 'ulti') {
                return 'No podés utilizar el ULTI si no has atacado cuerpo a cuerpo primero';
            }  

            if (LastAttack::where('player_id', Auth::id())->first()->type_attack !== 'cuerpo' && $request->type === 'ulti') {
                return 'Tienes que atacar primero al cuerpo para utilizar el ULTI';
            }

            LastAttack::updateOrInsert(
                ['player_id' => Auth::id()],
                ['type_attack' => $request->type]
            );

            $life_player = Player::where('id', $request->id)->first()->life;

            $player_outfit = Outfit::where('player_id', $request->id)->first();

            $user_outfit = Outfit::where('player_id', Player::where('user_id', Auth::id())->first()->id)->first();

            $bota_id_player = $player_outfit->bota_id;
            $arma_id_player = $player_outfit->arma_id;
            $armadura_id_player = $player_outfit->armadura_id;
            
            $bota_id_player !== null ? $bota_defense = Items::where('id', $bota_id_player)->first()->pt_defense : $bota_defense = 5;
            $arma_id_player !== null ? $arma_defense = Items::where('id', $arma_id_player)->first()->pt_defense : $arma_defense = 5;
            $armadura_id_player !== null ? $armadura_defense = Items::where('id', $armadura_id_player)->first()->pt_defense : $armadura_defense = 5;

            $total_pt_defense = $bota_defense + $arma_defense + $armadura_defense + 5;
            
            $bota_id_user = $user_outfit->bota_id;
            $arma_id_user = $user_outfit->arma_id;
            $armadura_id_user = $user_outfit->armadura_id;
            
            $bota_id_user !== null ? $bota_attack = Items::where('id', $bota_id_user)->first()->pt_attack : $bota_attack = 5;
            $arma_id_user !== null ? $arma_attack = Items::where('id', $arma_id_user)->first()->pt_attack : $arma_attack = 5;
            $armadura_id_user !== null ? $armadura_attack = Items::where('id', $armadura_id_user)->first()->pt_attack : $armadura_attack = 5;

            $total_pt_attack = ($bota_attack + $arma_attack + $armadura_attack + 5)*$type_attack_value;

            if ($total_pt_defense > $total_pt_attack) {
                if (($life_player-1) <= 0) {
                    Player::where('id', $request->id)
                    ->update([
                        'life' => $life_player-1,
                        'status' => 'inactivo'
                    ]);
                    return Player::where('id', $request->id)->first();
                } else {
                    Player::where('id', $request->id)
                    ->update([
                        'life' => $life_player-1,
                    ]);
                    return Player::where('id', $request->id)->first();
                }
            } else{
                if (($life_player-1) <= 0) {
                    Player::where('id', $request->id)
                    ->update([
                        'life' => $life_player + $total_pt_defense - $total_pt_attack,
                        'status' => 'inactivo'
                    ]);
                    return Player::where('id', $request->id)->first();
                } else {
                    Player::where('id', $request->id)
                    ->update([
                        'life' => $life_player + $total_pt_defense - $total_pt_attack,
                    ]);
                    return Player::where('id', $request->id)->first();
                }
            }
        }
    }
}