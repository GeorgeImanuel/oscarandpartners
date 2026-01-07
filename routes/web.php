<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CoverageController;
use App\Http\Controllers\ObjectController;
use App\Http\Controllers\ObjectTypeController;
use App\Http\Controllers\PartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

@include_once('admin_web.php');

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('index');

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'auth'])->name('auth');

Route::group(['middleware' => ['auth:web']], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', function () {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        return view('dashboard');
    })->name('dashboard');  
    
    Route::resource('relations', RelationController::class);

    Route::get('/cards/this-month', [CardController::class, 'thisMonth'])->name('cards.thisMonth');
    Route::get('/cards/next-month', [CardController::class, 'nextMonth'])->name('cards.nextMonth');
    Route::get('/cards/report-by-relation', [CardController::class, 'reportByRelation'])->name('cards.reportByRelation');
    Route::get('/cards/report-by-object', [CardController::class, 'reportByObject'])->name('cards.reportByObject');
    Route::resource('cards', CardController::class)->parameters(['cards' => 'productionCard']);
    Route::get('cards/{productionCard}/preview', [CardController::class, 'preview'])->name('cards.preview');

    // Add missing resources referenced in sidebar
    Route::resource('insurances', InsuranceController::class);
    Route::resource('policies', PolicyController::class);
    Route::resource('coverages', CoverageController::class);
    Route::resource('objects', ObjectController::class);
    Route::resource('object-types', ObjectTypeController::class);
    Route::resource('parts', PartController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('users', UserController::class);
    Route::resource('settings', SettingController::class);
});


Route::view('sample-page', 'admin.pages.sample-page')->name('sample-page');

Route::prefix('dashboard')->group(function () {
    // Route::view('/', 'admin.dashboard.default')->name('index');
    Route::view('default', 'admin.dashboard.default')->name('dashboard.index');
});


Route::view('default-layout', 'multiple.default-layout')->name('default-layout');
Route::view('compact-layout', 'multiple.compact-layout')->name('compact-layout');
Route::view('modern-layout', 'multiple.modern-layout')->name('modern-layout');
