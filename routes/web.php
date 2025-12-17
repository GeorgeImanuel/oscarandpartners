<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\ProductionCardController;
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

    // Production Cards Routes
    Route::prefix('kartuproduksi')->group(function () {
        Route::get('view', [ProductionCardController::class, 'index'])->name('cards.view');
        Route::get('create', [ProductionCardController::class, 'create'])->name('cards.create');
        Route::post('store', [ProductionCardController::class, 'store'])->name('cards.store');
        Route::get('detail/{productionCard}', [ProductionCardController::class, 'show'])->name('cards.detail');
        Route::get('edit/{productionCard}', [ProductionCardController::class, 'edit'])->name('cards.edit');
        Route::put('update/{productionCard}', [ProductionCardController::class, 'update'])->name('cards.update');
        Route::delete('delete/{productionCard}', [ProductionCardController::class, 'destroy'])->name('cards.destroy');
    });
});


Route::view('sample-page', 'admin.pages.sample-page')->name('sample-page');

Route::prefix('dashboard')->group(function () {
    // Route::view('/', 'admin.dashboard.default')->name('index');
    Route::view('default', 'admin.dashboard.default')->name('dashboard.index');
});

Route::view('default-layout', 'multiple.default-layout')->name('default-layout');
Route::view('compact-layout', 'multiple.compact-layout')->name('compact-layout');
Route::view('modern-layout', 'multiple.modern-layout')->name('modern-layout');
