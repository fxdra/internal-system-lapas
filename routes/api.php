<?php

use App\Http\Controllers\Api\LaporanController;

use App\Http\Controllers\MutasiController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\MutasiScenarioController;
use App\Http\Controllers\KamarController;
use App\Http\Controllers\TrackingPhotoController;

use App\Models\Device;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataWbpController;
use App\Http\Controllers\Api\BonWbpController;
use App\Http\Controllers\Api\KomandanJagaController;
use App\Http\Controllers\Api\FcmController;


use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::get('/register-backoffice', [AuthController::class, 'index']);
Route::post('/register-backoffice', [AuthController::class, 'register'])->name('x.store');



// notifikasi
Route::get('/bon/latest', [BonWbpController::class, 'latest']);


Route::post(
    '/tracking/upload',
    [
        TrackingPhotoController::class,
        'store'
    ]
);


// Semua route berikut hanya bisa diakses setelah login (token valid)
// 🔐 PROTECTED
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/fcm-token', [FcmController::class, 'store']);
    // Bon wbp
    Route::post('/wbps/bon', [DataWbpController::class, 'bonWbp']);


    // close bonWbp
    Route::post('/close-bon/{id}', [BonWbpController::class, 'closeBon']);


    // tracking
    Route::post('/tracking/store', [BonWbpController::class, 'storeTracking']);



    // LIST APPROVAL
    Route::get('/bon-approval', [BonWbpController::class, 'approvalList']);

    Route::get('/bon-aktif', [BonWbpController::class, 'getApproveBon']);

    Route::get('/bon-cancel', [BonWbpController::class, 'getCancelBon']);

    // APPROVE
    Route::post('/bon/{id}/approve', [BonWbpController::class, 'approve']);

    // REJECT
    Route::post('/bon/{id}/reject', [BonWbpController::class, 'reject']);

    // semua log (latest 100)
    Route::get('/bon-logs', [BonWbpController::class, 'getLogBon']);

    // log per BON
    Route::get('/bon-logs/{bonWbpId}', [BonWbpController::class, 'showDataLog']);

    Route::get('/data-kamar', [LaporanController::class, 'dataKamar']);
    Route::get('/statistik-data', [LaporanController::class, 'dataStatistikWbp']);
    Route::post('/refresh-token', [LaporanController::class, 'refreshToken']);
    Route::post('/logout', [LaporanController::class, 'logoutApi']);
    Route::get('/scan-kamar/{barcode}', [LaporanController::class, 'scanKamar']);
    Route::post('/kamar/update-status/{id}', [LaporanController::class, 'updateStatusKamar']);

    // Mutasi
    Route::get('/mutasi', [MutasiController::class, 'index']);
    Route::get('/riwayat-mutasi', [MutasiController::class, 'riwayatMutasi']);
    Route::get('/data-mutasi', [MutasiScenarioController::class, 'snapshotApp']);
    Route::post('/mutasi/store', [MutasiController::class, 'store']);
    Route::post('/mutasi/update-kamar', [MutasiController::class, 'updateKamar']);
    Route::post('/mutasi/update-alasan', [MutasiController::class, 'updateAlasan']);

    Route::post('/wbp/upload-foto', [LaporanController::class, 'uploadFotoWbp']);


    Route::post('/scenario/simulate', [MutasiScenarioController::class, 'simulate']);
    Route::get('/scenario/{id}', [MutasiScenarioController::class, 'result']);



    // ================= PETUGAS =================
    Route::get('/bon', [BonWbpController::class, 'index']);
    Route::post('/bon', [BonWbpController::class, 'store']);
    Route::get('/bon/{id}', [BonWbpController::class, 'show']);
    Route::post('/bon/{id}/tracking', [BonWbpController::class, 'tracking']);
    Route::post('/bon/{id}/selesai', [KomandanJagaController::class, 'selesai']);
});



// Endpoint laporan perpustakaan
Route::post('/update-pengunjung-status', [LaporanController::class, 'updateStatus']);
Route::get('/kategori', [LaporanController::class, 'kategori']);
Route::post('/buku', [LaporanController::class, 'store']);

Route::get('/data-wbp', [LaporanController::class, 'dataWbp']);
Route::post('/pengunjung-perpustakaan', [LaporanController::class, 'createPengunjung']);
Route::get('/notifikasi', [LaporanController::class, 'getNotifikasi']);
Route::post('/login-kplp', [LaporanController::class, 'loginApi']);

Route::post('/login-petugas', [LaporanController::class, 'loginPetugas']);


Route::post('/track', [TrackingController::class, 'store']);
Route::get('/track/devices', [TrackingController::class, 'devices']);

Route::get('/devices', function () {
    return Device::with(['logs', 'uploads'])->get();
});

Route::get('/wbp-pending', [LaporanController::class, 'pending']);

Route::get('/get-kamar', [LaporanController::class, 'kamarGet']);
