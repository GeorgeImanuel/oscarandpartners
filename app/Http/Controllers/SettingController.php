<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Services\Setting\SettingService;
use App\Http\Requests\storeSettingRequest;
use App\Http\Requests\UpdateSettingRequest;

class SettingController extends Controller
{

    private $settingService;
    /**
     * @var SettingService
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    // Mengambil data pertama dari table settings
    $setting = Setting::first(); 

    // Jika belum ada data sama sekali, buat object kosong agar Blade tidak error
    if (!$setting) {
        $setting = new Setting();
    }

    return view('settings.form', compact('setting'));
    }

    /**
     * Create page.
     */
    public function create(Request $request)
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSettingRequest $request)
    {
        $setting = $this->settingService->create($request->all());
        if(!$setting){
            return redirect()->back()->withInput()->with('error', 'Gagal menambah data setting.');
        }
        return redirect()->route('settings.index')->with('success', 'Berhasil menambah data setting.');
    }

    /**
     * Edit Page
     */
    public function edit(string $id)
    {
        $setting = $this->settingService->findOrFail($id);
        return view('settings.form', compact('setting'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSettingRequest $request, string $id)
    {
        $setting = $this->settingService->update($id, $request->all());
        if(!$setting){
            return redirect()->back()->withInput()->with('error', 'Gagal mengubah data setting.');
        }
        return redirect()->route('settings.index')->with('success', 'Berhasil mengubah data setting.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        
    }
}
