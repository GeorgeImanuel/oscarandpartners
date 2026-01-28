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
                'relation_id' => Relation::inRandomOrder()->first()?->id ?? Relation::factory()->create()->id,
                'id_name' => $user->name,
                'id_address' => fake()->address(),
            ]);
        }
    }
}
