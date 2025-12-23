<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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
            'password' => Hash::make('password'),
        ]);
        $owner->assignRole('owner');

        $admin = User::create([
            'username' => 'admin',
            'name'     => 'Administrator',
            'email'    => 'admin@example.com',
            'password' => Hash::make('password'),
        ]); 
        $admin->assignRole('admin');

        $finance = User::create([
            'username' => 'finance',
            'name'     => 'Finance User',
            'email'    => 'finance@example.com',        
            'password' => Hash::make('password'),
        ]);
        $finance->assignRole('finance');

        for ($i = 1; $i <= 10; $i++) {
            $dummyClient = User::create([
                'username' => sprintf('client%02d', $i),
                'name'     => 'Client User ' . $i,
                'email'    => 'client' . $i . '@example.com',
                'password' => Hash::make('password'),
            ]);
            $dummyClient->assignRole('client');
        }
    }
}
