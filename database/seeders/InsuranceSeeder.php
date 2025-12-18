<?php

namespace Database\Seeders;

use App\Models\Insurance;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InsuranceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Insurance::insert([
            [
                'name' => 'PT Asuransi Sinar Mas',
            ],
            [
                'name' => 'PT Prudential Life Assurance',
            ],
            [
                'name' => 'PT Asuransi Allianz Life Indonesia',
            ],
            [
                'name' => 'PT AXA Mandiri Financial Services',
            ],
            [
                'name' => 'PT Asuransi Jiwa Manulife Indonesia',
            ],
            [
                'name' => 'PT Asuransi Astra Buana',
            ],
            [
                'name' => 'PT Asuransi BRI Life',
            ],
            [
                'name' => 'PT Asuransi Jiwasraya (Persero)',
            ],
            [
                'name' => 'PT Asuransi Tugu Pratama Indonesia',
            ],
            [
                'name' => 'PT Asuransi Tokio Marine Indonesia',
            ],
            [
                'name' => 'PT Asuransi Zurich Indonesia',
            ],
            [
                'name' => 'PT Asuransi Generali Indonesia',
            ],
            [
                'name' => 'PT Asuransi Mega Pratama',
            ],
            [
                'name' => 'PT Asuransi Jiwa Sequis Life',
            ],
            [
                'name' => 'PT Asuransi Jiwa Sinarmas MSIG',
            ],
        ]);
    }
}
