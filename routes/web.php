<?php

use App\Http\Controllers\{BukuController, AdminController, AntrianController, BeritaController, BulettinController, DataKritikController, DataKunjunganController, DataProfileController, DataWbpController, HomeController, ImportFileController, InformasiLayananController, KegiatanController, KritikController, OcrController, PengunjungController, SettingController, TitipanController, ConverterController, KamarController, HpGasbanController, ManajemenPenggunaController, MutasiController, MutasiScenarioController, ProdukController, PetugasKplpController, RaziaController, RaziaGabunganController, PenerimaanWBPController, PemindahanWBPController, TrackingPhotoController, KomjaController};
use App\Http\Controllers\Api\LaporanController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PengunjungPerpustakaanController;
use App\Http\Controllers\PerpustakaanController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;


Route::get('/getlist', [AuthController::class, 'getList']);
// generate-update-barcode
Route::get('/scan/{uuid}', [KamarController::class, 'show']);
Route::get('/kamar-wbp', [KamarController::class, 'getKamar']);
Route::get('/generate-update-barcode', [LaporanController::class, 'resetUuidAndRegenerateQr']);

Route::prefix('api')->middleware('throttle:30,1')->group(function () {
    Route::get('/laporan-perpustakaan', [LaporanController::class, 'index']);
    Route::get('/top-pengunjung', [LaporanController::class, 'topPengunjung']);
    Route::get('/top-buku', [LaporanController::class, 'topBuku']);
    Route::get('/data-buku', [LaporanController::class, 'dataBuku']);
    Route::get('/data-pengunjung-perpustakaan', [LaporanController::class, 'dataPengunjungPerpustakaan']);
    Route::get('/data-pengembalian', [LaporanController::class, 'getPengembalian']);
    Route::get('/data-peminjaman', [LaporanController::class, 'getPeminjaman']);
    Route::get('/kamars/generate', [LaporanController::class, 'generateKamarBulkSync']);
    Route::get('/generate-missing-kamar', [LaporanController::class, 'generateMissingKamars']);
});
// barcode scan

/*
|--------------------------------------------------------------------------
| WEB LAPAS BANCEUY
|--------------------------------------------------------------------------
*/
// Admin
Route::get('/admin-login', [AdminController::class, 'loginView']);
Route::post('/admin-login', [AdminController::class, 'submitFormAdmin']);
Route::post('/save-location', [HomeController::class, 'store'])->name('save.location');
Route::get('/', [HomeController::class, 'index']);
Route::get('/banceuy-kunjungan', [HomeController::class, 'kunjungan']);
Route::get('/banceuy-profile', [HomeController::class, 'profile']);
Route::get('/banceuy-tentang-kami', [HomeController::class, 'about']);
Route::get('/berita/search', [HomeController::class, 'search']);
// Berita
Route::get('/berita', [HomeController::class, 'berita']);
Route::get('/berita/{slug}', [HomeController::class, 'showBerita']);
// Informasi Layanan
Route::get('/informasi-layanan', [HomeController::class, 'informasi']);
// Kegiatan
Route::get('/banceuy-kegiatan', [HomeController::class, 'kegiatan'])->name('kegiatan');
// bulettin
Route::get('/bulettin', [HomeController::class, 'bulettinIndex'])->name('bulettin');
Route::get('/tatap-muka', [AntrianController::class, 'index']);
Route::get('/daftar-kunjungan', [PengunjungController::class, 'index']);
Route::get('/kunjungan/result/{id}', [PengunjungController::class, 'result'])->name('result.kunjungan');
Route::post('/daftar-pengunjung', [PengunjungController::class, 'store']);
// Kuota
Route::get('/api/kuota', [PengunjungController::class, 'getKuota']);
// OCR Validasi
Route::post('/ocr/ktp', [OcrController::class, 'ocrKtp'])->name('ocr.ktp');
// Cek Antrian
Route::get('/cek-antrian-pengunjung', [PengunjungController::class, 'cekAntrian'])->name('cek-antrian');
Route::get('/cek-antrian', [PengunjungController::class, 'antrian']);
// Perpustakaan
Route::get('/perpustakaan-banceuy', [PerpustakaanController::class, 'index']);
// Titip Barang
Route::get('/titip-barang-pengunjung', [TitipanController::class, 'titipBarang'])->name('titip-barang');
Route::get('/titip-barang', [TitipanController::class, 'titip']);
Route::post('/daftar-titip-barang', [TitipanController::class, 'store']);
Route::get('/result/titip-barang/{id}', [TitipanController::class, 'result'])->name('result.titip_barang');
// Kritik & Saran
Route::get('/kritik-saran', [KritikController::class, 'index']);
Route::post('/kritik-saran', [KritikController::class, 'store']);
// route OCR (kalau kamu sudah punya, pakai itu aja)
Route::post('/ocr-ktp', [KritikController::class, 'ocrKtp'])->name('ocr.ktp');
Route::get('/admin/kunjungan/{id}/detail', [PengunjungController::class, 'detail'])->name('kunjungan.detail');

/*
|--------------------------------------------------------------------------
| DATA WBP dan DATA MUTASI UNTUK GIATJA, BIMPAS, REGISTRASI DAN KAMTIB
|--------------------------------------------------------------------------
*/
Route::middleware('admin')->group(function () {
    //Dashboard
    Route::get('/admin-banceuy', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/admin/notifications', [AdminController::class, 'getNotifications']);
    Route::get('/logout', [AdminController::class, 'logout']);
    //Data WBP & Data Mutasi
    Route::get('/admin-banceuy/scenario-snapshot', [MutasiScenarioController::class, 'snapshot'])->name('admin.scenario.snapshot');
    Route::get('/admin-banceuy/laporan-data-wbp', [DataWbpController::class, 'index']);
    Route::get('/search-wbp', [DataWbpController::class, 'search']);
    Route::get('/admin-banceuy/kamar/all', [DataWbpController::class, 'kamarAll']);
    Route::get('/api/blok', [DataWbpController::class, 'getBlok'])->name('api.getBlok');
    Route::get('/api/sel', [DataWbpController::class, 'getSel'])->name('api.getSel');
    Route::get('/admin-banceuy/wbp/filter', [DataWbpController::class, 'filter']);
    Route::get('/admin-banceuy/wbp/{id}/detail', [DataWbpController::class, 'detail']);

    /*
    |--------------------------------------------------------------------------
    | FULL ACCESS
    |--------------------------------------------------------------------------
    |
    | Modul-modul yang hanya dapat diakses oleh:
    | superadmin, admin, kplp, ka. kplp
    |
    */
    Route::middleware('role:superadmin,admin,kplp,ka_kplp')->group(function () {
        //Routes Mutasi
        Route::delete('/snapshot/reset', [MutasiScenarioController::class, 'reset'])->name('snapshot.reset');
        Route::delete('/admin-banceuy/mutasi/{id}', [MutasiController::class, 'destroy'])->name('mutasi.destroy');
        Route::get('/admin-banceuy/riwayat-mutasi', [MutasiController::class, 'dataMutasi']);
        Route::put('/mutasi/{id}', [MutasiController::class, 'updateKamarNew'])->name('mutasi.update');
        Route::post('/admin-banceuy/wbp-kamar-update', [MutasiController::class, 'wbpKamarUpdate']);
        Route::get('/admin-banceuy/riwayat-mutasi', [MutasiController::class, 'dataMutasi'])->name('mutasi.riwayat');

        //Route Kamar & Status WBP
        Route::post('/admin-banceuy/wbp/store', [DataWbpController::class, 'store'])->name('wbp.store');
        Route::post('/api/kamar/update-status', [DataWbpController::class, 'updateStatusKamar'])->name('api.updateStatusKamar');
        Route::post('/admin-banceuy/wbp/update-keterangan', [DataWbpController::class, 'updateKeterangan']);
        Route::post('/admin-banceuy/wbp/update-status', [DataWbpController::class, 'updateStatus']);
        Route::post('/admin-banceuy/wbp/update-kamar', [DataWbpController::class, 'wbpKamarUpdate']);
        Route::post('/wbp/update-status-multiple', [DataWbpController::class, 'updateStatusMultiple'])->name('wbp.updateStatusMultiple');
        Route::delete('/admin-banceuy/wbp/{id}', [DataWbpController::class, 'destroy']);
        Route::get('/wbp/export', [DataWbpController::class, 'export'])->name('wbp.export');
        Route::post('/admin-banceuy/wbp/import/import-wbp', [ImportFileController::class, 'import'])->name('wbp.import');
        Route::post('/admin-banceuy/wbp/import/preview', [ImportFileController::class, 'preview'])->name('wbp.import.preview');
        Route::get('/admin-banceuy/laporan-data-kamar', [KamarController::class, 'index']);
        Route::get('/admin-banceuy/kamar/search', [KamarController::class, 'search']);
        Route::get('/admin-banceuy/kamar/{id}/detail', [KamarController::class, 'detail']);
        Route::post('/admin-banceuy/kamar/update-status', [KamarController::class, 'updateStatusKamar']);
        Route::post('/admin-banceuy/kamar/upload-pdf', [KamarController::class, 'uploadPdf']);
        Route::get('/admin-banceuy/kamar/print', [KamarController::class, 'print']);
        Route::get('/kamar-with-wbps', [KamarController::class, 'kamarWithWbps']);
        //Komandan Jaga
        Route::get('/komandan-jaga/filter', [KomjaController::class, 'filter'])->name('komandan-jaga.filter');
        Route::get('/admin-banceuy/komandan-jaga', [KomjaController::class, 'index'])->name('komandan-jaga.index');
        Route::post('/admin-banceuy/komja/store', [KomjaController::class, 'store'])->name('komja.store');
        //Petugas KPLP
        Route::get('/admin-banceuy/petugas-kplp', [PetugasKplpController::class, 'index'])->name('petugas-kplp.index');
        Route::post('/admin-banceuy/petugas-kplp/store', [PetugasKplpController::class, 'store'])->name('petugas-kplp.store');
        Route::delete('/admin-banceuy/petugas-kplp/{id}', [PetugasKplpController::class, 'destroy'])->name('petugas-kplp.destroy');
        //Manajemen User
        Route::get('/manajemen-pengguna', [ManajemenPenggunaController::class, 'index'])->name('manajemen-pengguna.index');
        Route::post('/manajemen-pengguna', [ManajemenPenggunaController::class, 'store'])->name('manajemen-pengguna.store');
        Route::put('/manajemen-pengguna/{id}', [ManajemenPenggunaController::class, 'update'])->name('manajemen-pengguna.update');
        Route::delete('/manajemen-pengguna/{id}', [ManajemenPenggunaController::class, 'destroy'])->name('manajemen-pengguna.destroy');
        //Produk
        Route::get('/admin-banceuy/produk', [ProdukController::class, 'index']);
        Route::put('/admin-banceuy/produk/{id}', [ProdukController::class, 'updateProduct'])->name('admin.products.update');
        Route::delete('/admin-banceuy/produk/{id}', [ProdukController::class, 'deleteProduct'])->name('admin.products.delete');
        Route::post('/admin-banceuy/categories', [ProdukController::class, 'storeCategory'])->name('admin.categories.store');
        Route::delete('/categories/{id}', [ProdukController::class, 'deleteCategory'])->name('admin.categories.delete');
        Route::post('/admin-banceuy/produk', [ProdukController::class, 'storeProduct'])->name('admin.products.store');
        Route::get('/admin-banceuy/setting', [SettingController::class, 'index']);
        Route::post('/admin-banceuy/setting', [SettingController::class, 'update'])->name('admin.setting.update');
        // Data Kunjungan ADMIN
        Route::resource('/admin-banceuy/laporan-data-kunjungan', DataKunjunganController::class);
        Route::get('/admin-banceuy/data-kunjungan-status-pending', [DataKunjunganController::class, 'pending']);
        Route::get('/admin-banceuy/data-kunjungan-status-checkin', [DataKunjunganController::class, 'checkIn']);
        Route::get('/admin-banceuy/data-kunjungan-status-checkout', [DataKunjunganController::class, 'checkOut']);
        Route::delete('/admin/kunjungan/{id}', [PengunjungController::class, 'destroy']);
        // Admin Kuota Kunjungan
        Route::get('/admin-banceuy/kuota', [DataKunjunganController::class, 'kuotaKunjungan']);
        Route::get('/admin-banceuy/kuota/{id}', [AntrianController::class, 'show']);
        Route::post('/admin-banceuy/kuota', [AntrianController::class, 'store']);
        Route::put('/admin-banceuy/kuota/{id}', [AntrianController::class, 'update']);
        Route::delete('/admin-banceuy/kuota/{id}', [AntrianController::class, 'destroy']);
        // Admin Buku
        Route::get('/admin-banceuy/daftar-buku', [BukuController::class, 'index']);
        Route::post('/admin-banceuy/buku/store', [BukuController::class, 'store'])->name('admin.buku.store');
        Route::put('/admin-banceuy/buku/update/{id}', [BukuController::class, 'update'])->name('admin.buku.update');
        Route::delete('/admin-banceuy/buku/delete/{id}', [BukuController::class, 'destroy'])->name('admin.buku.destroy');
        // Kategori Buku
        Route::get('/admin-banceuy/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');
        Route::post('/admin-banceuy/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store');
        Route::put('/admin-banceuy/kategori/{id}', [KategoriController::class, 'update'])->name('admin.kategori.update');
        Route::delete('/admin-banceuy/kategori/{id}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');
        // Pengunjung Perpustakaan
        Route::get('/admin-banceuy/pengunjung-perpustakaan', [PengunjungPerpustakaanController::class, 'index'])->name('admin.pengunjung.index');
        Route::get('/admin-banceuy/peminjaman-buku', [PengunjungPerpustakaanController::class, 'pengunjungPeminjam'])->name('admin.pengunjung.pengunjungPeminjam');
        Route::get('/peminjaman/{id}', [PengunjungPerpustakaanController::class, 'show'])->name('peminjaman.show');
        Route::get('/admin-banceuy/pengembalian-buku', [PengunjungPerpustakaanController::class, 'pengunjungPengembalian'])->name('admin.pengunjung.pengunjungPengembalian');
        Route::get('/admin-banceuy/laporan-perpustakaan', [PengunjungPerpustakaanController::class, 'laporanPerpustakaan'])->name('admin.pengunjung.laporan');
        Route::post('/admin-banceuy/pengunjung-perpustakaan', [PengunjungPerpustakaanController::class, 'store'])->name('admin.pengunjung.store');
        Route::put('/admin-banceuy/pengunjung-perpustakaan/{id}', [PengunjungPerpustakaanController::class, 'update'])->name('admin.pengunjung.update');
        Route::delete('/admin-banceuy/pengunjung-perpustakaan/delete/{id}', [PengunjungPerpustakaanController::class, 'destroy'])->name('admin.pengunjung.destroy');
        // Kritik
        Route::get('/admin-banceuy/laporan-data-kritik', [DataKritikController::class, 'index'])->name('kritik.index');
        Route::get('/admin-banceuy/kritik/detail/{id}', [DataKritikController::class, 'detail'])->name('kritik.detail');
        Route::delete('/admin-banceuy/kritik/hapus/{id}', [KritikController::class, 'hapus'])->name('kritik.hapus');
        Route::get('/admin-banceuy/kritik/export-pdf', [DataKritikController::class, 'exportPdf'])->name('kritik.exportPdf');
        // Profile
        Route::get('/admin-banceuy/profile', [DataProfileController::class, 'index'])->name('admin.profile.index');
        Route::post('/admin-banceuy/profile', [DataProfileController::class, 'update'])->name('admin.profile.update');
        // Berita
        Route::get('/admin-banceuy/berita', [BeritaController::class, 'index'])->name('admin.berita.index');
        Route::post('/admin-banceuy/berita/store', [BeritaController::class, 'store'])->name('admin.berita.store');
        Route::post('/admin-banceuy/berita/update/{id}', [BeritaController::class, 'update'])->name('admin.berita.update');
        Route::post('/admin-banceuy/berita/delete/{id}', [BeritaController::class, 'destroy'])->name('admin.berita.destroy');
        // Infromasi Layanan
        Route::get('/admin-banceuy/informasi-layanan', [InformasiLayananController::class, 'index']);
        Route::post('/admin-banceuy/informasi-layanan', [InformasiLayananController::class, 'store']);
        Route::post('/admin-banceuy/informasi-layanan/{id}/update', [InformasiLayananController::class, 'update']);
        Route::post('/admin-banceuy/informasi-layanan/{id}/delete', [InformasiLayananController::class, 'destroy']);
        // Kegiatan
        Route::get('/admin-banceuy/kegiatan', [KegiatanController::class, 'index'])->name('admin.kegiatan.index');
        Route::post('/admin-banceuy/kegiatan/store', [KegiatanController::class, 'store'])->name('admin.kegiatan.store');
        Route::post('/admin-banceuy/kegiatan/update/{id}', [KegiatanController::class, 'update'])->name('admin.kegiatan.update');
        Route::post('/admin-banceuy/kegiatan/delete/{id}', [KegiatanController::class, 'destroy'])->name('admin.kegiatan.destroy');
        //  Bulletin
        Route::get('/admin-banceuy/bulletin', [BulettinController::class, 'index'])->name('admin.bulettin.index');
        Route::post('/admin-banceuy/bulettin/store', [BulettinController::class, 'store'])->name('admin.bulettin.store');
        Route::post('/admin-banceuy/bulettin/update/{id}', [BulettinController::class, 'update'])->name('admin.bulettin.update');
        Route::post('/admin-banceuy/bulettin/delete/{id}', [BulettinController::class, 'destroy'])->name('admin.bulettin.destroy');
        // Data Hp Petugas
        Route::get('/admin-banceuy/hp-gasban', [HpGasbanController::class, 'index'])->name('hp-gasban.index');
        Route::get('/hp-gasban/barcode/{barcode_id}', [HpGasbanController::class, 'showBarcode'])->name('hp-gasban.barcode');
        Route::post('/admin-banceuy/hp-gasban/store', [HpGasbanController::class, 'store'])->name('hp-gasban.store');
        Route::get('/admin-banceuy/hp-gasban/show/{id}', [HpGasbanController::class, 'show'])->name('hp-gasban.show');
        Route::delete('/admin-banceuy/hp-gasban/delete/{id}', [HpGasbanController::class, 'destroy'])->name('hp-gasban.delete');
        // Tracking App
        Route::get('/admin/tracking', [TrackingController::class, 'index']);
        Route::get('/admin/tracking/live', [\App\Http\Controllers\TrackingController::class, 'live']);
        // Converter pdf-to-excel
        Route::post('/pdf-to-excel', [ConverterController::class, 'convert']);
        //download-excel
        Route::get('/download-excel', function () {
            return response()->download(request('path'));
        });

        //Sistem Generate Laporan
        Route::get('/sistemlaporan', function () {
            return view('admin-banceuy.sistemlaporan');
        })->name('sistemlaporan');
        Route::prefix('pemindahanwbp')->group(function () {
            Route::get('/', [PemindahanWBPController::class, 'index'])->name('pemindahanwbp.index');
            Route::get('/create', [PemindahanWBPController::class, 'create'])->name('pemindahanwbp.create');
            Route::post('/store', [PemindahanWBPController::class, 'store'])->name('pemindahanwbp.store');
            Route::get('/edit/{id}', [PemindahanWBPController::class, 'edit'])->name('pemindahanwbp.edit');
            Route::get('/update/{id}', [PemindahanWBPController::class, 'update'])->name('pemindahanwbp.update');
            Route::post('/upload-foto/{id}', [PemindahanWBPController::class, 'uploadFoto'])->name('pemindahanwbp.upload-foto');
            Route::get('/preview/{id}', [PemindahanWBPController::class, 'preview'])->name('pemindahanwbp.preview');
            Route::get('/download/{id}', [PemindahanWBPController::class, 'download'])->name('pemindahanwbp.download');
            Route::delete('/delete/{id}', [PemindahanWBPController::class, 'destroy'])->name('pemindahanwbp.destroy');
        });

        Route::prefix('penerimaanwbp')->group(function () {
            Route::get('/', [PenerimaanWBPController::class, 'index'])->name('penerimaanwbp.index');
            Route::get('/create', [PenerimaanWBPController::class, 'create'])->name('penerimaanwbp.create');
            Route::post('/store', [PenerimaanWBPController::class, 'store'])->name('penerimaanwbp.store');
            Route::get('/edit/{id}', [PenerimaanWBPController::class, 'edit'])->name('penerimaanwbp.edit');
            Route::post('/update/{id}', [PenerimaanWBPController::class, 'update'])->name('penerimaanwbp.update');
            Route::post('/upload-foto/{id}', [PenerimaanWBPController::class, 'uploadFoto'])->name('penerimaanwbp.upload-foto');
            Route::get('/preview/{id}', [PenerimaanWBPController::class, 'preview'])->name('penerimaanwbp.preview');
            Route::get('/download/{id}', [PenerimaanWBPController::class, 'download'])->name('penerimaanwbp.download');
            Route::delete('/delete/{id}', [PenerimaanWBPController::class, 'destroy'])->name('penerimaanwbp.destroy');
        });

        Route::prefix('razia')->group(function () {
            Route::get('/', [RaziaController::class, 'index'])->name('razia.index');
            Route::get('/create', [RaziaController::class, 'create'])->name('razia.create');
            Route::get('/{id}/edit', [RaziaController::class, 'edit'])->name('razia.edit');
            Route::post('/store', [RaziaController::class, 'store'])->name('razia.store');
            Route::put('/{id}', [RaziaController::class, 'update'])->name('razia.update');
            Route::get('/{id}/download', [RaziaController::class, 'download'])->name('razia.download');
            Route::post('/{id}/kamar', [RaziaController::class, 'saveKamar'])->name('razia.kamar');
            Route::post('/{id}/barang', [RaziaController::class, 'saveBarang'])->name('razia.barang');
            Route::post('/{id}/personel', [RaziaController::class, 'savePersonel'])->name('razia.personel');
            Route::post('/{id}/foto', [RaziaController::class, 'uploadFoto'])->name('razia.foto');
            Route::post('/{id}/save-kamar', [RaziaController::class, 'saveKamar'])->name('razia.save-kamar');
            Route::post('/{id}/save-barang', [RaziaController::class, 'saveBarang'])->name('razia.save-barang');
            Route::post('/{id}/save-personel', [RaziaController::class, 'savePersonel'])->name('razia.save-personel');
            Route::post('/{id}/upload-foto', [RaziaController::class, 'uploadFoto'])->name('razia.upload-foto');
            Route::get('/{id}/preview', [RaziaController::class, 'preview'])->name('razia.preview');
            Route::delete('/{id}/delete', [RaziaController::class, 'destroy'])->name('razia.destroy');
        });

        Route::prefix('razia-gabungan')->group(function () {
            Route::get('/', [RaziaGabunganController::class, 'index'])->name('raziagabungan.index');
            Route::get('/create', [RaziaGabunganController::class, 'create'])->name('raziagabungan.create');
            Route::post('/store', [RaziaGabunganController::class, 'store'])->name('raziagabungan.store');
            Route::get('/{id}/edit', [RaziaGabunganController::class, 'edit'])->name('raziagabungan.edit');
            Route::post('/{id}/update', [RaziaGabunganController::class, 'update'])->name('raziagabungan.update');
            Route::post('/{id}/save-kamar', [RaziaGabunganController::class, 'saveKamar'])->name('raziagabungan.save-kamar');
            Route::post('/{id}/save-barang', [RaziaGabunganController::class, 'saveBarang'])->name('raziagabungan.save-barang');
            Route::post('/{id}/save-tes-urine', [RaziaGabunganController::class, 'saveTesUrine'])->name('raziagabungan.save-tes-urine');
            Route::post('/{id}/upload-foto', [RaziaGabunganController::class, 'uploadFoto'])->name('raziagabungan.upload-foto');
            Route::get('/{id}/preview', [RaziaGabunganController::class, 'preview'])->name('raziagabungan.preview');
            Route::get('/{id}/download', [RaziaGabunganController::class, 'download'])->name('raziagabungan.download');
            Route::delete('/{id}/delete', [RaziaGabunganController::class, 'destroy'])->name('raziagabungan.destroy');

            Route::get('/tracking', [TrackingPhotoController::class, 'index']);
        });
    });
});


// WBP


    // Route::get('/generate-barcode-url', function () {

    //     $kamars = Kamar::all();

    //     $updated = 0;

    //     foreach ($kamars as $kamar) {

    //         $kamar->url_barcode = url(
    //             '/scan/' . $kamar->barcode_id
    //         );

    //         $kamar->save();

    //         $updated++;
    //     }

    //     return response()->json([
    //         'status' => true,
    //         'updated' => $updated,
    //         'message' => 'URL barcode berhasil dibuat'
    //     ]);
    // });
