<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Currency\CurrencyService;
// Tambahkan Model untuk helper pencarian ID saat update
use App\Models\Currency; 

class CurrencyController extends Controller
{
    private $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    public function index()
    {
        $currencies = $this->currencyService->all();
        return view('currencies.index', compact('currencies'));
    }

    public function create()
    {
        return view('currencies.form');
    }

    public function store(Request $request)
    {
        // 1. Tambahkan validasi 'value'
        $data = $request->validate([
            'code'  => 'required|string|max:10|unique:currencies,code',
            'name'  => 'required|string|max:255',
            'value' => 'required|numeric|min:0', // Wajib ada untuk hitung-hitungan
        ]);

        // 2. Gunakan Service (jangan model langsung)
        $this->currencyService->create($data);

        return redirect()->route('currencies.index')->with('success', 'Mata uang berhasil ditambahkan.');
    }

    public function edit(string $code)
    {
        // Cari berdasarkan kolom 'code' manual karena service->find() biasanya cari ID
        $currency = Currency::where('code', $code)->first();
        
        if (!$currency) {
            return redirect()->route('currencies.index')->with('error', 'Mata uang tidak ditemukan.');
        }

        return view('currencies.form', compact('currency'));
    }

    public function update(Request $request, $id)
    {
        // 1. Cari Data (Berdasarkan Code dari URL)
        $currency = Currency::where('code', $id)->first();

        if (!$currency) {
            return redirect()->route('currencies.index')->with('error', 'Data tidak ditemukan.');
        }

        // 2. VALIDASI
        $data = $request->validate([
            // FIX: Tambahkan parameter ke-4 ',code' 
            // Artinya: Cek unik di kolom 'code', KECUALI data yang punya 'code' = $currency->code
            'code'  => 'required|string|max:10|unique:currencies,code,' . $currency->code . ',code', 
            'name'  => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
        ]);

        try {
            // 3. UPDATE MANUAL
            // Kita update manual field-nya agar lebih aman jika Model tidak mengenali Primary Key
            Currency::where('code', $id)->update([
                'code' => $data['code'],
                'name' => $data['name'],
                'value' => $data['value'],
            ]);

            return redirect()->route('currencies.index')
                ->with('success', 'Mata uang berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal update data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(string $code)
    {
        $currency = Currency::where('code', $code)->first();
        if ($currency) {
            $this->currencyService->delete($currency->id);
        }
        return redirect()->route('currencies.index')->with('success', 'Mata uang berhasil dihapus.');
    }
}