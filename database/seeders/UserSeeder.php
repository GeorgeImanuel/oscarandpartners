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
        $endang = User::create([
            'username' => 'endang',
            'name'     => 'Endang',
            'email'    => 'endang@example.com',
            'password' => Hash::make('password'),
        ]);
        $endang->assignRole('finance');

        $peppy = User::create([
            'username' => 'peppy',
            'name'     => 'Peppy',
            'email'    => 'peppy@example.com',
            'password' => Hash::make('password'),
        ]);
        $peppy->assignRole('owner');

        $christin = User::create([
            'username' => 'christin',
            'name'     => 'Christin',
            'email'    => 'christin@example.com',
            'password' => Hash::make('password'),
        ]);
        $christin->assignRole('admin');

        $joshua = User::create([
            'username' => 'joshua',
            'name'     => 'Joshua',
            'email'    => 'joshua@example.com',
            'password' => Hash::make('password'),
        ]);
        $joshua->assignRole('admin');

        $moegi = User::create([
            'username' => 'moegi',
            'name'     => 'Moegi',
            'email'    => 'moegi@example.com',
            'password' => Hash::make('password'),
        ]);
        $moegi->assignRole('admin');

        $oscar = User::create([
            'username' => 'oscar',
            'name'     => 'Oscar',
            'email'    => 'oscar@example.com',
            'password' => Hash::make('password'),
        ]);
        $oscar->assignRole('owner');

        User::factory()
            ->count(10)
            ->create()
            ->each(function ($user) {
                $user->assignRole('client');
            });
    }
}
