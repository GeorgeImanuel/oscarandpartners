<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'owner', 'guard_name' => 'web', 'display_name' => 'owner']);
        Role::create(['name' => 'admin', 'guard_name' => 'web', 'display_name' => 'admin']);
        Role::create(['name' => 'finance', 'guard_name' => 'web', 'display_name' => 'finance']);
        Role::create(['name' => 'client', 'guard_name' => 'web', 'display_name' => 'client']);
    }
}
