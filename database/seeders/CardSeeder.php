<?php

namespace Database\Seeders;

use App\Models\Policy;
use App\Models\Card;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $policies = Policy::limit(5)->get();
        foreach ($policies as $policy) {
            Card::factory()->create([
                'policy_id' => $policy->id,
            ]);
        }
    }
}
