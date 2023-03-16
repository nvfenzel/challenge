<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\User;
use App\Models\Items;

class ApiAdminController extends Controller
{
    public function autorization(Request $request)
    {

        $request->validate([
            'id' => ['required', 'exists:players,id'],
            'status' => ['required', 'in:activo,inactivo'],
        ]);

        if ($request->status === 'activo' || $request->status ===  'inactivo')
        {
            $player_update = Player::where('id', $request->id)->first();
            
            Player::where('id', $request->id)
            ->update(['status' => $request->status]);
            
            $data_user = Player::where('id', $request->id)->first();
            
            return "Se cambió al jugador " . $data_user->user->name . " a estado: " . $request->status;
        }else{
            return 'Los estados válidos son activo o inactivo';
        }
    }

    public function players()
    {
        return Player::all();
    }

    public function item(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:items'],
            'pt_defense' => ['required', 'numeric', 'min:0', 'max:100'],
            'pt_attack' => ['required', 'numeric', 'min:0', 'max:100'],
            'type' => ['required'],
        ]);
    
        $item_create = Items::create([
            'name' => $request->name,
            'pt_defense' => $request->pt_defense,
            'pt_attack' => $request->pt_attack,
            'type' => $request->type,
        ]);
        
        return $item_create;
    }

    public function items()
    {
        return Items::all();
    }

    public function edit(Request $request)
    {
        $request->validate([
            'name' => [ 'string', 'max:255', 'unique:items'],
            'pt_defense' => [ 'numeric', 'min:0', 'max:100'],
            'pt_attack' => [ 'numeric', 'min:0', 'max:100'],
            'id' => [ 'numeric', 'in:items,id'],
        ]);

            $items_update = Items::where('id', $request->id)->first();
            
            $request->name ? Items::where('id', $request->id)->update(['name'=> $request->name,]) : '';

            $request->pt_defense ? Items::where('id', $request->id)->update(['pt_defense'=> $request->pt_defense,]): '';

            $request->pt_attack ? Items::where('id', $request->id)->update(['pt_attack'=> $request->pt_attack,]): '';

            $request->type ? Items::where('id', $request->id)->update(['type'=> $request->type,]) : '';

            $data_item = Items::where('id', $request->id)->first();
            
            return "Se actualizó el siguiente item: " . $data_item ;
    }
}
