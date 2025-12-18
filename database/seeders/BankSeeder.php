<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Bank::insert(
            [
                ['kode_bank' => '002', 'nama_bank' => 'Bank Rakyat Indonesia (BRI)', 'alias' => 'BRI', 'is_active' => true],
                ['kode_bank' => '008', 'nama_bank' => 'Bank Mandiri', 'alias' => 'Mandiri', 'is_active' => true],
                ['kode_bank' => '009', 'nama_bank' => 'Bank Negara Indonesia (BNI)', 'alias' => 'BNI', 'is_active' => true],
                ['kode_bank' => '014', 'nama_bank' => 'Bank Central Asia (BCA)', 'alias' => 'BCA', 'is_active' => true],
                ['kode_bank' => '011', 'nama_bank' => 'Bank Danamon', 'alias' => 'Danamon', 'is_active' => true],
                ['kode_bank' => '013', 'nama_bank' => 'Bank Permata', 'alias' => 'Permata', 'is_active' => true],
                ['kode_bank' => '016', 'nama_bank' => 'Bank Maybank Indonesia', 'alias' => 'Maybank', 'is_active' => true],
                ['kode_bank' => '019', 'nama_bank' => 'Panin Bank', 'alias' => 'Panin', 'is_active' => true],
                ['kode_bank' => '022', 'nama_bank' => 'CIMB Niaga', 'alias' => 'CIMB', 'is_active' => true],
                ['kode_bank' => '023', 'nama_bank' => 'Bank UOB Indonesia', 'alias' => 'UOB', 'is_active' => true],
                ['kode_bank' => '028', 'nama_bank' => 'Bank OCBC NISP', 'alias' => 'OCBC', 'is_active' => true],
                ['kode_bank' => '031', 'nama_bank' => 'Citibank', 'alias' => 'Citi', 'is_active' => true],

                ['kode_bank' => '032', 'nama_bank' => 'JPMorgan Chase Bank', 'alias' => 'JPMorgan', 'is_active' => true],
                ['kode_bank' => '033', 'nama_bank' => 'Bank of America', 'alias' => 'BofA', 'is_active' => true],
                ['kode_bank' => '036', 'nama_bank' => 'Bank China Construction Bank Indonesia', 'alias' => 'CCB', 'is_active' => true],

                ['kode_bank' => '037', 'nama_bank' => 'Bank Artha Graha Internasional', 'alias' => 'Artha Graha', 'is_active' => true],
                ['kode_bank' => '042', 'nama_bank' => 'Bank Mizuho Indonesia', 'alias' => 'Mizuho', 'is_active' => true],
                ['kode_bank' => '050', 'nama_bank' => 'Standard Chartered Bank', 'alias' => 'SCB', 'is_active' => true],
                ['kode_bank' => '052', 'nama_bank' => 'Bank ABN AMRO (historical/if needed)', 'alias' => 'ABN AMRO', 'is_active' => false],

                ['kode_bank' => '053', 'nama_bank' => 'Bank Capital Indonesia', 'alias' => 'Capital', 'is_active' => true],
                ['kode_bank' => '054', 'nama_bank' => 'Bank BNP Paribas Indonesia', 'alias' => 'BNP', 'is_active' => true],
                ['kode_bank' => '061', 'nama_bank' => 'Bank ANZ Indonesia (historical/if needed)', 'alias' => 'ANZ', 'is_active' => false],

                ['kode_bank' => '110', 'nama_bank' => 'Bank BJB', 'alias' => 'BJB', 'is_active' => true],
                ['kode_bank' => '111', 'nama_bank' => 'Bank DKI', 'alias' => 'Bank DKI', 'is_active' => true],
                ['kode_bank' => '112', 'nama_bank' => 'Bank BPD DIY', 'alias' => 'BPD DIY', 'is_active' => true],
                ['kode_bank' => '113', 'nama_bank' => 'Bank Jateng', 'alias' => 'Bank Jateng', 'is_active' => true],
                ['kode_bank' => '114', 'nama_bank' => 'Bank Jatim', 'alias' => 'Bank Jatim', 'is_active' => true],
                ['kode_bank' => '115', 'nama_bank' => 'Bank Jambi', 'alias' => 'Bank Jambi', 'is_active' => true],
                ['kode_bank' => '116', 'nama_bank' => 'Bank Sumut', 'alias' => 'Bank Sumut', 'is_active' => true],
                ['kode_bank' => '117', 'nama_bank' => 'Bank Sumbar', 'alias' => 'Bank Nagari', 'is_active' => true],
                ['kode_bank' => '118', 'nama_bank' => 'Bank Riau Kepri', 'alias' => 'BRK', 'is_active' => true],
                ['kode_bank' => '119', 'nama_bank' => 'Bank Sumsel Babel', 'alias' => 'Bank Sumsel Babel', 'is_active' => true],
                ['kode_bank' => '120', 'nama_bank' => 'Bank Lampung', 'alias' => 'Bank Lampung', 'is_active' => true],
                ['kode_bank' => '121', 'nama_bank' => 'Bank Kalsel', 'alias' => 'Bank Kalsel', 'is_active' => true],
                ['kode_bank' => '122', 'nama_bank' => 'Bank Kaltimtara', 'alias' => 'Bank Kaltimtara', 'is_active' => true],
                ['kode_bank' => '123', 'nama_bank' => 'Bank Kalteng', 'alias' => 'Bank Kalteng', 'is_active' => true],
                ['kode_bank' => '124', 'nama_bank' => 'Bank Sulselbar', 'alias' => 'Bank Sulselbar', 'is_active' => true],
                ['kode_bank' => '125', 'nama_bank' => 'Bank SulutGo', 'alias' => 'Bank SulutGo', 'is_active' => true],
                ['kode_bank' => '126', 'nama_bank' => 'Bank NTB Syariah', 'alias' => 'NTB Syariah', 'is_active' => true],
                ['kode_bank' => '127', 'nama_bank' => 'Bank Bali (historical/if needed)', 'alias' => 'Bank Bali', 'is_active' => false],
                ['kode_bank' => '128', 'nama_bank' => 'Bank NTT', 'alias' => 'Bank NTT', 'is_active' => true],
                ['kode_bank' => '129', 'nama_bank' => 'Bank Maluku Malut', 'alias' => 'Bank Maluku', 'is_active' => true],
                ['kode_bank' => '130', 'nama_bank' => 'Bank Papua', 'alias' => 'Bank Papua', 'is_active' => true],

                ['kode_bank' => '200', 'nama_bank' => 'Bank Tabungan Negara (BTN)', 'alias' => 'BTN', 'is_active' => true],
                ['kode_bank' => '212', 'nama_bank' => 'Bank BTPN', 'alias' => 'BTPN', 'is_active' => true],
                ['kode_bank' => '213', 'nama_bank' => 'Bank BTPN Syariah', 'alias' => 'BTPN Syariah', 'is_active' => true],

                ['kode_bank' => '426', 'nama_bank' => 'Bank Mega', 'alias' => 'Mega', 'is_active' => true],
                ['kode_bank' => '441', 'nama_bank' => 'Bank Bukopin (historical/if needed)', 'alias' => 'Bukopin', 'is_active' => false],
                ['kode_bank' => '451', 'nama_bank' => 'Bank Syariah Indonesia', 'alias' => 'BSI', 'is_active' => true],

                ['kode_bank' => '484', 'nama_bank' => 'Bank KEB Hana Indonesia', 'alias' => 'Hana', 'is_active' => true],
                ['kode_bank' => '494', 'nama_bank' => 'Bank Raya Indonesia', 'alias' => 'Bank Raya', 'is_active' => true],
                ['kode_bank' => '503', 'nama_bank' => 'Bank Nationalnobu', 'alias' => 'Nobu', 'is_active' => true],
                ['kode_bank' => '506', 'nama_bank' => 'Bank Mega Syariah', 'alias' => 'Mega Syariah', 'is_active' => true],
            ]
        );
    }
}
