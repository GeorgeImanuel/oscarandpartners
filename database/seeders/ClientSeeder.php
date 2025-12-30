<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Client;
use App\Models\Relation;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::role('client')->get();
                
        foreach ($users as $user) {
            Client::create([
                'user_id' => $user->id,
                'relation_id' => fake()->boolean(70) ? (Relation::inRandomOrder()->first()?->id ?? null) : null,
                'id_name' => $user->name,
                'id_number' => fake()->optional()->numerify('################'),
                'id_address' => fake()->optional()->address(),
            ]);
        }
    }
}
