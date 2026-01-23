<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\RelationController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\CardController;
use App\Http\Controllers\CoverageController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\ObjectController;
use App\Http\Controllers\ObjectOccupationController;
use App\Http\Controllers\InterestController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ReportController;
use App\Models\Relation;
use App\Models\Payment;

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
    Route::get('user-profile', [UserController::class, 'profile'])->name('user.profile');
    Route::put('user-profile', [UserController::class, 'profileUpdate'])->name('profile.update');  
    
    Route::resource('relations', RelationController::class);

    Route::get('/cards/this-month', [CardController::class, 'thisMonth'])->name('cards.thisMonth');
    Route::get('/cards/next-month', [CardController::class, 'nextMonth'])->name('cards.nextMonth');
    Route::get('/cards/report-by-relation', [CardController::class, 'reportByRelation'])->name('cards.reportByRelation');
    Route::get('/cards/report-by-object', [CardController::class, 'reportByObject'])->name('cards.reportByObject');
    Route::resource('cards', CardController::class)->parameters(['cards' => 'productionCard']);
    Route::get('cards/{productionCard}/preview', [CardController::class, 'preview'])->name('cards.preview');

        // PINDAHKAN KE ATAS resource
    Route::get('/payments/overdue', [PaymentController::class, 'overdue'])->name('payments.overdue');

    // Route resource diletakkan di bawahnya
    Route::resource('payments', PaymentController::class);
    Route::post('payments/{payment}/update-installments', [PaymentController::class, 'updateInstallments'])->name('payments.updateInstallments');
    Route::put('payments/{payment}/client-pay', [PaymentController::class, 'updateClientPay'])->name('payments.update_client_pay');
    Route::put('payments/{payment}/insurance-pay', [PaymentController::class, 'updateInsurancePay'])->name('payments.update_insurance_pay');


    // Add missing resources referenced in sidebar
    Route::resource('insurances', InsuranceController::class);
    Route::resource('policies', PolicyController::class);
    Route::resource('currencies', CurrencyController::class);
    Route::resource('coverages', CoverageController::class);
    Route::resource('objects', ObjectController::class);
    Route::resource('object-occupations', ObjectOccupationController::class);
    Route::resource('interests', InterestController::class);
    Route::resource('clients', ClientController::class);
    Route::resource('users', UserController::class);
    Route::resource('claims', ClaimController::class);

    Route::get('/reports/evaluation', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.evaluation');
    Route::get('/reports/evaluation/data', [App\Http\Controllers\ReportController::class, 'evaluation'])->name('reports.data');
});


Route::view('sample-page', 'admin.pages.sample-page')->name('sample-page');

Route::prefix('dashboard')->group(function () {
    // Route::view('/', 'admin.dashboard.default')->name('index');
    Route::view('default', 'admin.dashboard.default')->name('dashboard.index');
});


Route::view('default-layout', 'multiple.default-layout')->name('default-layout');
Route::view('compact-layout', 'multiple.compact-layout')->name('compact-layout');
Route::view('modern-layout', 'multiple.modern-layout')->name('modern-layout');
