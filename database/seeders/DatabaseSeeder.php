<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(ObjectTypeSeeder::class);
        $this->call(PaymentTypeSeeder::class);
        $this->call(CardStatusesSeeder::class);
        $this->call(PaymentTypeSeeder::class);
        $this->call(CollectorTypeSeeder::class);
        $this->call(BankSeeder::class);
        $this->call(InsuranceSeeder::class);
    }
}
