<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Services\Currency\CurrencyService;

class CurrencyController extends Controller
{
    private $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = $this->currencyService->all();
        return view('currencies.index', compact('currencies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('currencies.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:3|unique:currencies,code',
            'name' => 'required|string|max:255',
        ]);

        Currency::create($request->only(['code', 'name']));

        return redirect()->route('currencies.index')->with('success', 'Mata uang berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $code)
    {
        $currency = $this->currencyService->find($code);
        
        if (!$currency) {
            return redirect()->route('currencies.index')->with('error', 'Mata uang tidak ditemukan.');
        }

        return view('currencies.form', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $code)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $currency = $this->currencyService->find($code);
        
        if (!$currency) {
            return redirect()->route('currencies.index')->with('error', 'Mata uang tidak ditemukan.');
        }

        $currency->update($request->only(['name']));

        return redirect()->route('currencies.index')->with('success', 'Mata uang berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $code)
    {
        try {
            $currency = $this->currencyService->find($code);
            
            if (!$currency) {
                return redirect()->route('currencies.index')->with('error', 'Mata uang tidak ditemukan.');
            }

            $currency->delete();

            return redirect()->route('currencies.index')->with('success', 'Mata uang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus mata uang: ' . $e->getMessage());
        }
    }
}
