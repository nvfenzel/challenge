<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

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
            // 'remember_token' => Str::random(10),
        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
