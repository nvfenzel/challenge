<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Items;
use App\Models\Player;
use App\Models\Bota;
use App\Models\Arma;
use App\Models\Armadura;
use App\Models\Outfit;
use App\Models\Stock;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        User::factory()->create([
            'email' => 'admin@admin.com',
            'account' => 'is_admin',
            'password' => '$2y$10$sehHW0Sg455QM/th6ccvoe3CqxXN2Nu2NuCFI/hLdajB57sp1DKz6'
        ]);

        User::factory()->create([
            'email' => 'user@user.com',
            'account' => 'is_user',
            'password' => '$2y$10$p1e/XVjb5Et6G7mMKYZ6K.NALxe6ftlZSl4/UaIcc3AscB18hkjAW'
        ]);

        User::factory(20)->create();

        Player::factory()->create([
            'status' => fake()->randomElement(['activo', 'inactivo']),
            'type' => 'human',
            'user_id' => 2,
        ]);

        $users = User::count();

        for ($i=3; $i < $users; $i++) { 
          Player::factory()->create([
                'status' => fake()->randomElement(['activo', 'inactivo']),
                'type' => fake()->randomElement(['zombie', 'human']),
                'user_id' => $i,
          ]);
          }

          Items::factory(40)->create();

          $items = Items::all();

          foreach ($items as $item) {
            if ($item->type === 'bota') 
            {
                Bota::insert([
                    'bota_id' => $item->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            if ($item->type === 'arma') 
            {
                Arma::insert([
                    'arma_id' => $item->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
            if ($item->type === 'armadura') 
            {
                Armadura::insert([
                    'armadura_id' => $item->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
          
          }


          for ($i=0; $i < 50; $i++) { 
            
            $count_players = Player::count();
            $count_items = Items::count();
            
            Stock::factory()->create([
                'player_id' => rand(1,$count_players),
                'item_id' => rand(1,$count_items),
              ]);
            }

          $players = Player::all();

          foreach ($players as $player) 
          {
            $armadura = Armadura::get()->random(1);
            $arma = Arma::get()->random(1);
            $bota = Bota::get()->random(1);

            Outfit::insert([
                'player_id' => $player->id,
                'armadura_id' => fake()->randomElement([null, $armadura[0]->id]),
                'arma_id' => fake()->randomElement([null, $arma[0]->id]),
                'bota_id' => fake()->randomElement([null, $bota[0]->id]),
                'created_at' => now(),
                'updated_at' => now()
            ]);
          }


    }
}
