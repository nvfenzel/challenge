<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\User;
use App\Models\Stock;
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

    public function stock()
    {
        $my_player_id = Player::where('user_id', Auth::id())->first()->id;

        if ($my_stock_array = Stock::where('player_id', $my_player_id)->get()->isNotEmpty()) {
            $my_stock_array = Stock::where('player_id', $my_player_id)->get();
            
            $data=[];
            foreach ($my_stock_array as $var) {
                $stock_id = $var->item_id;
                array_push($data,[
                    Items::where('id', $stock_id)->first()
                ]);
            }
            return $data;
        }

        if ($my_stock_array = Stock::where('player_id', $my_player_id)->get()->isEmpty()) {
            return 'El jugador no tiene nada en el inventario';
        }
    }

    public function add_stock(Request $request)
    {
        $request->validate
        ([
            'id' => ['required', 'numeric', 'exists:items,id']
        ]);

        Stock::create([
            'player_id' =>  Player::where('user_id', Auth::id())->first()->id,
            'item_id' => $request->id,
        ]);

        return 'Nuevo item agregado';
    }

    public function show_outfit(Request $request)
    {
        $outfit_bota = Outfit::where('player_id', Auth::id())->first()->bota_id;
        $outfit_arma = Outfit::where('player_id', Auth::id())->first()->arma_id;
        $outfit_armadura = Outfit::where('player_id', Auth::id())->first()->armadura_id;


        $data_outfit=[Items::where('id', $outfit_bota)->first(), Items::where('id', $outfit_arma)->first(), Items::where('id', $outfit_armadura)->first()];

        return $data_outfit;
    }

    public function outfit(Request $request)
    {
        $request->validate
        ([
            'id' => ['required', 'numeric', 'exists:items,id']
        ]);
       
        $my_player_id = Player::where('user_id', Auth::id())->first()->id;

        if ($my_stock_array = Stock::where('player_id', $my_player_id)->get()->isEmpty()) {
            return 'Primero tienes que agregar algo a tu inventario';
        }
        
        if (Items::where('id', $request->id)->first() && Stock::where('player_id', $my_player_id)->where('item_id', $request->id)->first()) {
            if (Items::where('id', $request->id)->first()->type === 'bota') {
                Outfit::updateOrInsert(
                    ['player_id' => $my_player_id],
                    ['bota_id' => $request->id]
                );
                return 'Bota cargada!';
            }
            if (Items::where('id', $request->id)->first()->type === 'armadura') {
                Outfit::updateOrInsert(
                    ['player_id' => $my_player_id],
                    ['armadura_id' => $request->id]
                );
                return 'Armadura cargada!';
            }
            if (Items::where('id', $request->id)->first()->type === 'arma') {
                Outfit::updateOrInsert(
                    ['player_id' => $my_player_id],
                    ['arma_id' => $request->id]
                );
                return 'Arma cargada!';
            }
        } else{
            return 'No lo tengo en mi inventario, primero debo agragarlo a mi inventario para poder usarlo';
        }


    }

    public function attack(Request $request)
    {
        $request->validate([
            'id' => ['required', 'numeric', 'exists:players,id'],
            'type' => ['required', 'string', 'in:cuerpo,distancia,ulti']
            ]);

        $request->type === 'cuerpo' ? $type_attack_value = 1 : '';
        $request->type === 'distancia' ? $type_attack_value = 0.8 : '';
        $request->type === 'ulti' ? $type_attack_value = 2 : '';

        $player_id = Player::where('user_id', Auth::id())->first()->id;

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

        if (Player::where('id', $request->id)->first()->id === $player_id) {
            return 'No te podes atacar vos mismo';
        }
        
        if (Player::where('id', $request->id)->first()->status === 'inactivo') {
            return 'Ese usuario no está disponible';
        } 

        if (Player::where('id', $request->id)->first()->status === 'activo'){

            if (LastAttack::where('player_id', $player_id)->doesntExist() && $request->type === 'ulti') {
                return 'No podés utilizar el ULTI si no has atacado cuerpo a cuerpo primero';
            }  

            if (LastAttack::where('player_id', $player_id)->first()->type_attack !== 'cuerpo' && $request->type === 'ulti') {
                return 'Tienes que atacar primero al cuerpo para utilizar el ULTI';
            }

            LastAttack::updateOrInsert(
                ['player_id' => $player_id],
                ['type_attack' => $request->type]
            );

            $life_player = Player::where('id', $request->id)->first()->life;

            $player_outfit = Outfit::where('player_id', $request->id)->first();

            $user_outfit = Outfit::where('player_id', $player_id)->first();

            $bota_id_player = $player_outfit->bota_id;
            $arma_id_player = $player_outfit->arma_id;
            $armadura_id_player = $player_outfit->armadura_id;
            
            $bota_id_player !== null ? $bota_defense = Items::where('id', $bota_id_player)->first()->pt_defense : $bota_defense = 0;
            $arma_id_player !== null ? $arma_defense = Items::where('id', $arma_id_player)->first()->pt_defense : $arma_defense = 0;
            $armadura_id_player !== null ? $armadura_defense = Items::where('id', $armadura_id_player)->first()->pt_defense : $armadura_defense = 0;

            $total_pt_defense = $bota_defense + $arma_defense + $armadura_defense + 5;
            
            $bota_id_user = $user_outfit->bota_id;
            $arma_id_user = $user_outfit->arma_id;
            $armadura_id_user = $user_outfit->armadura_id;
            
            $bota_id_user !== null ? $bota_attack = Items::where('id', $bota_id_user)->first()->pt_attack : $bota_attack = 0;
            $arma_id_user !== null ? $arma_attack = Items::where('id', $arma_id_user)->first()->pt_attack : $arma_attack = 0;
            $armadura_id_user !== null ? $armadura_attack = Items::where('id', $armadura_id_user)->first()->pt_attack : $armadura_attack = 0;

            $total_pt_attack = ($bota_attack + $arma_attack + $armadura_attack + 5)*$type_attack_value;

            if ($total_pt_defense >= $total_pt_attack) {
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