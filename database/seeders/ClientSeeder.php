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
                'user_id'        => $user->id,
                'relation_id'    => Relation::inRandomOrder()->first()->id,
                'name'           => $user->name,
                'phone'          => $user->phone ?? fake()->phoneNumber(),
                'email'          => $user->email,
                'address'        => fake()->address(),
                'id_card_number' => fake()->numerify('################'),
            ]);
        };
    }
}
