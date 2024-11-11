<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{IMBController,JenisNonPerumController,TujuanSuratController,DataIMBTidaklengkapController, MasterController,SinkronisasiLokasiIMBController, SuratController, IMBIndukNonPerumController, IMBIndukPerumController, IMBTidakLengkapController, IMBPerluasanController, IMBPecahanController, IMBBersyaratController, IMBPelunasanController, IMBPemutihanController};

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {

    Route::prefix('master')->group(function () {
        Route::get('/get-provinsi', [MasterController::class, 'getProvinsi'])->name('master.provinsi');
        Route::get('/get-kabupaten', [MasterController::class, 'getKabupaten'])->name('master.kabupaten');
        Route::get('/get-kecamatan', [MasterController::class, 'getKecamatan'])->name('master.kecamatan');
        Route::get('/get-kelurahan', [MasterController::class, 'getKelurahan'])->name('master.kelurahan');
        Route::get('/get-imb-induk', [MasterController::class, 'getIMBInduk'])->name('master.imb-induk');
        Route::get('/get-imb-pecahan', [MasterController::class, 'getIMBPecahan'])->name('master.imb-pecahan');
    });

    Route::prefix('IMBIndukPerum')->group(function () {
        Route::get('/', [IMBIndukPerumController::class, 'index'])->name('IMBIndukPerum.index');
        Route::get('/items', [IMBIndukPerumController::class, 'items'])->name('IMBIndukPerum.items');
        Route::get('/create', [IMBIndukPerumController::class, 'create'])->name('IMBIndukPerum.create');
        Route::post('/store', [IMBIndukPerumController::class, 'store'])->name('IMBIndukPerum.store');
        Route::get('/edit/{id}', [IMBIndukPerumController::class, 'edit'])->name('IMBIndukPerum.edit');
        Route::put('/update/{id}', [IMBIndukPerumController::class, 'update'])->name('IMBIndukPerum.update');
        Route::delete('/delete/{id}', [IMBIndukPerumController::class, 'destroy'])->name('IMBIndukPerum.destroy');
        Route::get('/import', [IMBIndukPerumController::class, 'import'])->name('IMBIndukPerum.import');
        Route::post('/import', [IMBIndukPerumController::class, 'importData'])->name('IMBIndukPerum.importData');
        Route::get('/export', [IMBIndukPerumController::class, 'exportData'])->name('IMBIndukPerum.export');
        Route::get('/download-template', [IMBIndukPerumController::class, 'downloadTemplate'])->name('IMBIndukPerum.download-template');
    });

    Route::prefix('IMBPecahan')->group(function () {
        Route::get('/', [IMBPecahanController::class, 'index'])->name('IMBPecahan.index');
        Route::get('/create', [IMBPecahanController::class, 'create'])->name('IMBPecahan.create');
        Route::post('/store', [IMBPecahanController::class, 'store'])->name('IMBPecahan.store');
        Route::get('/edit/{id}', [IMBPecahanController::class, 'edit'])->name('IMBPecahan.edit');
        Route::put('/update/{id}', [IMBPecahanController::class, 'update'])->name('IMBPecahan.update');
        Route::delete('/delete/{id}', [IMBPecahanController::class, 'destroy'])->name('IMBPecahan.destroy');
        Route::get('/import', [IMBPecahanController::class, 'import'])->name('IMBPecahan.import');
        Route::post('/import', [IMBPecahanController::class, 'importData'])->name('IMBPecahan.importData');
        Route::get('/export', [IMBPecahanController::class, 'exportData'])->name('IMBPecahan.export');
        Route::get('/download-template', [IMBPecahanController::class, 'downloadTemplate'])->name('IMBPecahan.download-template');
    });

    Route::prefix('SinkronisasiLokasiIMB')->group(function () {
        Route::get('/hubungkan', [SinkronisasiLokasiIMBController::class, 'hubungkan'])->name('SinkronisasiLokasiIMB.hubungkan');
        Route::get('/', [SinkronisasiLokasiIMBController::class, 'index'])->name('SinkronisasiLokasiIMB.index');
        Route::post('/hubungkan', [SinkronisasiLokasiIMBController::class, 'hubungkanStore'])->name('SinkronisasiLokasiIMB.hubungkanStore');



        Route::get('/create', [SinkronisasiLokasiIMBController::class, 'create'])->name('SinkronisasiLokasiIMB.create');
        Route::post('/store', [SinkronisasiLokasiIMBController::class, 'store'])->name('SinkronisasiLokasiIMB.store');
        Route::get('/edit/{id}', [SinkronisasiLokasiIMBController::class, 'edit'])->name('SinkronisasiLokasiIMB.edit');
        Route::put('/update/{id}', [SinkronisasiLokasiIMBController::class, 'update'])->name('SinkronisasiLokasiIMB.update');
        Route::delete('/delete/{id}', [SinkronisasiLokasiIMBController::class, 'destroy'])->name('SinkronisasiLokasiIMB.destroy');
        Route::get('/import', [SinkronisasiLokasiIMBController::class, 'import'])->name('SinkronisasiLokasiIMB.import');
        Route::post('/import', [SinkronisasiLokasiIMBController::class, 'importData'])->name('SinkronisasiLokasiIMB.importData');
        Route::get('/export', [SinkronisasiLokasiIMBController::class, 'exportData'])->name('SinkronisasiLokasiIMB.export');
        Route::get('/download-template', [SinkronisasiLokasiIMBController::class, 'downloadTemplate'])->name('SinkronisasiLokasiIMB.download-template');
    });

    Route::prefix('IMBPerluasan')->group(function () {
        Route::get('/', [IMBPerluasanController::class, 'index'])->name('IMBPerluasan.index');
        Route::get('/create', [IMBPerluasanController::class, 'create'])->name('IMBPerluasan.create');
        Route::post('/store', [IMBPerluasanController::class, 'store'])->name('IMBPerluasan.store');
        Route::get('/edit/{id}', [IMBPerluasanController::class, 'edit'])->name('IMBPerluasan.edit');
        Route::put('/update/{id}', [IMBPerluasanController::class, 'update'])->name('IMBPerluasan.update');
        Route::delete('/delete/{id}', [IMBPerluasanController::class, 'destroy'])->name('IMBPerluasan.destroy');
        Route::get('/import', [IMBPerluasanController::class, 'import'])->name('IMBPerluasan.import');
        Route::post('/import', [IMBPerluasanController::class, 'importData'])->name('IMBPerluasan.importData');
        Route::get('/export', [IMBPerluasanController::class, 'exportData'])->name('IMBPerluasan.export');
        Route::get('/download-template', [IMBPerluasanController::class, 'downloadTemplate'])->name('IMBPerluasan.download-template');

    });

    Route::prefix('IMBIndukNonPerum')->group(function () {
        Route::get('/', [IMBIndukNonPerumController::class, 'index'])->name('IMBIndukNonPerum.index');
        Route::get('/items', [IMBIndukNonPerumController::class, 'items'])->name('IMBIndukNonPerum.items');

        Route::get('/create', [IMBIndukNonPerumController::class, 'create'])->name('IMBIndukNonPerum.create');
        Route::post('/store', [IMBIndukNonPerumController::class, 'store'])->name('IMBIndukNonPerum.store');
        Route::get('/edit/{id}', [IMBIndukNonPerumController::class, 'edit'])->name('IMBIndukNonPerum.edit');
        Route::put('/update/{id}', [IMBIndukNonPerumController::class, 'update'])->name('IMBIndukNonPerum.update');
        Route::delete('/delete/{id}', [IMBIndukNonPerumController::class, 'destroy'])->name('IMBIndukNonPerum.destroy');
        Route::get('/import', [IMBIndukNonPerumController::class, 'import'])->name('IMBIndukNonPerum.import');
        Route::post('/import', [IMBIndukNonPerumController::class, 'importData'])->name('IMBIndukNonPerum.importData');
        Route::get('/export', [IMBIndukNonPerumController::class, 'exportData'])->name('IMBIndukNonPerum.export');
        Route::get('/download-template', [IMBIndukNonPerumController::class, 'downloadTemplate'])->name('IMBIndukNonPerum.download-template');
    });



    Route::prefix('IMBTidakLengkap')->group(function () {
        Route::get('/pecahan', [DataIMBTidaklengkapController::class, 'pecahan'])->name('DataIMBTidakLengkap.pecahan');
        Route::get('/perluasan', [DataIMBTidaklengkapController::class, 'perluasan'])->name('DataIMBTidakLengkap.perluasan');
        Route::post('/pair-pecahan', [DataIMBTidaklengkapController::class, 'pairPecahan'])->name('DataIMBTidakLengkap.pair-pecahan');
        Route::post('/pair-perluasan', [DataIMBTidaklengkapController::class, 'pairPerluasan'])->name('DataIMBTidakLengkap.pair-perluasan');

    });





    Route::prefix('surat')->group(function () {
        Route::get('/', [SuratController::class, 'index'])->name('surat.index');
        Route::get('/create', [SuratController::class, 'create'])->name('surat.create');
        Route::post('/store', [SuratController::class, 'store'])->name('surat.store');
        Route::post('/upload/{id}', [SuratController::class, 'upload'])->name('surat.upload');
        Route::get('/lihat/{id}', [SuratController::class, 'lihatSurat'])->name('surat.lihat');
        Route::get('/format-1', [SuratController::class, 'format1'])->name('surat.format-1');
        Route::get('/format-3', [SuratController::class, 'format3'])->name('surat.format-3');
        Route::post('/preview', [SuratController::class, 'preview'])->name('surat.preview');

        Route::get('/download/{id}', [SuratController::class, 'download'])->name('surat.download');

        Route::delete('/destroy/{id}', [SuratController::class, 'destroy'])->name('surat.destroy');

    });

    Route::prefix('master')->group(function () {
        Route::prefix('tujuan-surat')->group(function () {
            Route::get('/', [TujuanSuratController::class, 'index'])->name('tujuan-surat.index');
            Route::post('/store', [TujuanSuratController::class, 'store'])->name('tujuan-surat.store');
            Route::put('/update/{id}', [TujuanSuratController::class, 'update'])->name('tujuan-surat.update');
            Route::delete('/destroy/{id}', [TujuanSuratController::class, 'destroy'])->name('tujuan-surat.destroy');
        });
        Route::prefix('jenis-non-perum')->group(function () {
            Route::get('/', [JenisNonPerumController::class, 'index'])->name('jenis-non-perum.index');
            Route::post('/store', [JenisNonPerumController::class, 'store'])->name('jenis-non-perum.store');
            Route::put('/update/{id}', [JenisNonPerumController::class, 'update'])->name('jenis-non-perum.update');
            Route::delete('/destroy/{id}', [JenisNonPerumController::class, 'destroy'])->name('jenis-non-perum.destroy');
        });

    });
});




require __DIR__ . '/auth.php';
