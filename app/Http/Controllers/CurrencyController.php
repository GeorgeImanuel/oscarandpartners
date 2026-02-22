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

    public function edit(Currency $currency)
    {
        return view('currencies.form', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $data = $request->validate([
            // Validasi Unique mengabaikan code milik currency ini sendiri
            'code'  => 'required|string|max:10|unique:currencies,code,' . $currency->code . ',code', 
            'name'  => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
        ]);

        try {
            // Update langsung lewat instance model yang sudah ditemukan
            $currency->update($data);

            return redirect()->route('currencies.index')
                ->with('success', 'Mata uang berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal update data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Currency $currency)
    {
        try {
            $this->currencyService->delete($currency->code);
            
            return redirect()->route('currencies.index')
                ->with('success', 'Mata uang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }
}