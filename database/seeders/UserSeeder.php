<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $owner = User::create([
            'username' => 'owner',
            'name'     => 'Owner User',
            'email'    => 'owner@example.com',
            'password' => bcrypt('password'),
        ]);
        $owner->assignRole('owner');

        $admin = User::create([
            'username' => 'admin',
            'name'     => 'Administrator',
            'email'    => 'admin@example.com',
            'password' => bcrypt('password'),
        ]); 
        $admin->assignRole('admin');

        $finance = User::create([
            'username' => 'finance',
            'name'     => 'Finance User',
            'email'    => 'finance@example.com',        
            'password' => bcrypt('password'),
        ]);
        $finance->assignRole('finance');

        $client = User::create([
            'username' => '08123/Budi',
            'name'     => 'Budi Client',
            'email'    => 'budi@example.com',
            'password' => bcrypt('password'),
        ]);
        $client->assignRole('client');
    }
}
