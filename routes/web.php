<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{IMBController, RekapController, JenisNonPerumController, TujuanSuratController, DataIMBTidaklengkapController, MasterController, SinkronisasiLokasiIMBController, SuratController, IMBIndukNonPerumController, IMBIndukPerumController, IMBTidakLengkapController, IMBPerluasanController, IMBPecahanController, IMBBersyaratController, IMBPelunasanController, IMBPemutihanController, ListSuratController};

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::get('/imb/search', function () {
    return view('imb-search');
})->name('imb');


Route::get('/chart-simpol', function () {
    return view('chart-simpol');
})->name('chart-simpol');


// Route::get('/chart-simpol-data', function (Request $request) {
//     $jenis = $_GET['jenis'] ?? 'all'; // Default 'all'
//     $tahun = $_GET['tahun'];       // Tahun wajib diisi

//     // Validasi input
//     if (!$tahun || !is_numeric($tahun)) {
//         return response()->json(['error' => 'Tahun harus diisi dan berupa angka'], 400);
//     }

//     $data = [];

//     // Ambil data berdasarkan jenis
//     switch ($jenis) {
//         case 'imbinduk':
//             $data = DB::table('imb_induk_perum')
//                 ->select(DB::raw('MONTH(tgl_imb_induk) as bulan'), DB::raw('COUNT(*) as jumlah'))
//                 ->whereYear('tgl_imb_induk', $tahun)
//                 ->groupBy('bulan')
//                 ->orderBy('bulan')
//                 ->get();
//             break;

//         case 'imbpecahan':
//             $data = DB::table('imb_pecahan')
//                 ->select(DB::raw('MONTH(tgl_imb_pecahan) as bulan'), DB::raw('COUNT(*) as jumlah'))
//                 ->whereYear('tgl_imb_pecahan', $tahun)
//                 ->groupBy('bulan')
//                 ->orderBy('bulan')
//                 ->get();
//             break;

//         case 'imbperluasan':
//             $data = DB::table('imb_perluasan')
//                 ->select(DB::raw('MONTH(tgl_imb_perluasan) as bulan'), DB::raw('COUNT(*) as jumlah'))
//                 ->whereYear('tgl_imb_perluasan', $tahun)
//                 ->groupBy('bulan')
//                 ->orderBy('bulan')
//                 ->get();
//             break;

//         case 'imbinduknonperum':
//             $data = DB::table('imb_induk_non_perum')
//                 ->select(DB::raw('MONTH(tgl_imb_induk) as bulan'), DB::raw('COUNT(*) as jumlah'))
//                 ->whereYear('tgl_imb_induk', $tahun)
//                 ->groupBy('bulan')
//                 ->orderBy('bulan')
//                 ->get();
//             break;

//         default: // Jika jenis = 'all'
//         $imbInduk = DB::table('imb_induk_perum')
//         ->select(DB::raw('MONTH(tgl_imb_induk) as bulan'), DB::raw('COUNT(*) as jumlah'))
//         ->whereYear('tgl_imb_induk', $tahun)
//         ->groupBy(DB::raw('MONTH(tgl_imb_induk)'));

//     $imbPecahan = DB::table('imb_pecahan')
//         ->select(DB::raw('MONTH(tgl_imb_pecahan) as bulan'), DB::raw('COUNT(*) as jumlah'))
//         ->whereYear('tgl_imb_pecahan', $tahun)
//         ->groupBy(DB::raw('MONTH(tgl_imb_pecahan)'));

//     $imbPerluasan = DB::table('imb_perluasan')
//         ->select(DB::raw('MONTH(tgl_imb_perluasan) as bulan'), DB::raw('COUNT(*) as jumlah'))
//         ->whereYear('tgl_imb_perluasan', $tahun)
//         ->groupBy(DB::raw('MONTH(tgl_imb_perluasan)'));

//     $imbIndukNonPerum = DB::table('imb_induk_non_perum')
//         ->select(DB::raw('MONTH(tgl_imb_induk) as bulan'), DB::raw('COUNT(*) as jumlah'))
//         ->whereYear('tgl_imb_induk', $tahun)
//         ->groupBy(DB::raw('MONTH(tgl_imb_induk)'));

//     $data = $imbInduk->union($imbPecahan)
//         ->union($imbPerluasan)
//         ->union($imbIndukNonPerum)
//         ->orderBy('bulan')
//         ->get();
//             break;
//     }

//     // Format data menjadi { label, value }
//     $formattedData = $data->map(function ($item) {
//         return [
//             'label' => date('F', mktime(0, 0, 0, $item->bulan, 1)), // Nama bulan
//             'value' => $item->jumlah
//         ];
//     });

//     return response()->json($formattedData);
// })->name('chart-simpol-data');



Route::get('/chart-simpol-data', function (Request $request) {
    $jenis = $_GET['jenis'] ?? 'all'; // Default 'all'
    $tahun = $_GET['tahun'];       // Tahun wajib diisi

    // Validasi input
    if (!$tahun || !is_numeric($tahun)) {
        return response()->json(['error' => 'Tahun harus diisi dan berupa angka'], 400);
    }

    $data = [];

    // Ambil data berdasarkan jenis
    switch ($jenis) {
        case 'imbinduk':
            $data = DB::table('imb_induk_perum')
                ->select(DB::raw('MONTH(tgl_imb_induk) as bulan'), DB::raw('COUNT(*) as jumlah'))
                ->whereYear('tgl_imb_induk', $tahun)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();
            break;

        case 'imbpecahan':
            $data = DB::table('imb_pecahan')
                ->select(DB::raw('MONTH(tgl_imb_pecahan) as bulan'), DB::raw('COUNT(*) as jumlah'))
                ->whereYear('tgl_imb_pecahan', $tahun)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();
            break;

        case 'imbperluasan':
            $data = DB::table('imb_perluasan')
                ->select(DB::raw('MONTH(tgl_imb_perluasan) as bulan'), DB::raw('COUNT(*) as jumlah'))
                ->whereYear('tgl_imb_perluasan', $tahun)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();
            break;

        case 'imbinduknonperum':
            $data = DB::table('imb_induk_non_perum')
                ->select(DB::raw('MONTH(tgl_imb_induk) as bulan'), DB::raw('COUNT(*) as jumlah'))
                ->whereYear('tgl_imb_induk', $tahun)
                ->groupBy('bulan')
                ->orderBy('bulan')
                ->get();
            break;

        default: // Jika jenis = 'all'
            $imbInduk = DB::table('imb_induk_perum')
                ->select(DB::raw('MONTH(tgl_imb_induk) as bulan'), DB::raw('COUNT(*) as jumlah'))
                ->whereYear('tgl_imb_induk', $tahun)
                ->groupBy('bulan');

            $imbPecahan = DB::table('imb_pecahan')
                ->select(DB::raw('MONTH(tgl_imb_pecahan) as bulan'), DB::raw('COUNT(*) as jumlah'))
                ->whereYear('tgl_imb_pecahan', $tahun)
                ->groupBy('bulan');

            $imbPerluasan = DB::table('imb_perluasan')
                ->select(DB::raw('MONTH(tgl_imb_perluasan) as bulan'), DB::raw('COUNT(*) as jumlah'))
                ->whereYear('tgl_imb_perluasan', $tahun)
                ->groupBy('bulan');

            $imbIndukNonPerum = DB::table('imb_induk_non_perum')
                ->select(DB::raw('MONTH(tgl_imb_induk) as bulan'), DB::raw('COUNT(*) as jumlah'))
                ->whereYear('tgl_imb_induk', $tahun)
                ->groupBy('bulan');

            $data = $imbInduk->union($imbPecahan)
                ->union($imbPerluasan)
                ->union($imbIndukNonPerum)
                ->orderBy('bulan')
                ->get();
            break;
    }

    // Buat array bulan default
    $defaultData = collect(range(1, 12))->map(function ($bulan) {
        return [
            'bulan' => $bulan,
            'jumlah' => 0
        ];
    });

    // Gabungkan data dari database dengan data default
    $mergedData = $defaultData->map(function ($default) use ($data) {
        $found = $data->firstWhere('bulan', $default['bulan']);
        return [
            'bulan' => $default['bulan'],
            'jumlah' => $found ? $found->jumlah : 0
        ];
    });

    // Format data menjadi { label, value }
    $formattedData = $mergedData->map(function ($item) {
        return [
            'label' => date('F', mktime(0, 0, 0, $item['bulan'], 1)), // Nama bulan
            'value' => $item['jumlah']
        ];
    });

    return response()->json($formattedData);
})->name('chart-simpol-data');





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
        Route::get('/cari-imb', [IMBIndukPerumController::class, 'cariIMB'])->name('IMBIndukPerum.cari-imb');
        Route::get('/getIMBDetail/{id}/{type}', [IMBIndukPerumController::class, 'getIMBDetail'])->name('IMBIndukPerum.getIMBDetail');
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




    Route::prefix('rekap')->group(function () {
        Route::prefix('register-imb-pertahun')->group(function () {
            Route::get('/detail-imb-induk', [RekapController::class, 'DetailIMBInduk'])->name('rekap.DetailIMBInduk');
            Route::match(['GET', 'POST'], '/detail-imb-induk-list', [RekapController::class, 'DetailIMBIndukList'])->name('rekap.DetailIMBIndukList');
            Route::match(['GET', 'POST'], '/detail-imb-induk-list/{nama_pemohon}', [RekapController::class, 'DetailIMBIndukListNamaPemohon'])->name('rekap.DetailIMBIndukListNamaPemohon');
            Route::match(['GET', 'POST'], '/detail-imb-induk/{nama_pemohon}', [RekapController::class, 'DetailIMBIndukNamaPemohon'])->name('rekap.DetailIMBIndukNamaPemohon');

            Route::get('/detail-imb-pecahan', [RekapController::class, 'DetailIMBPecahan'])->name('rekap.DetailIMBPecahan');
            Route::match(['GET', 'POST'], '/detail-imb-pecahan-list', [RekapController::class, 'DetailIMBPecahanList'])->name('rekap.DetailIMBPecahanList');
            Route::match(['GET', 'POST'], '/detail-imb-pecahan/{imb_induk}', [RekapController::class, 'DetailIMBPecahanNamaIMB'])->name('rekap.DetailIMBPecahanNamaIMB');

            Route::get('/detail-imb-perluasan', [RekapController::class, 'DetailIMBPerluasan'])->name('rekap.DetailIMBPerluasan');
            Route::match(['GET', 'POST'], '/detail-imb-perluasan-list', [RekapController::class, 'DetailIMBPerluasanList'])->name('rekap.DetailIMBPerluasanList');
            Route::match(['GET', 'POST'], '/detail-imb-perluasan-list/{nama_pemohon}', [RekapController::class, 'DetailIMBPerluasanListNamaPemohon'])->name('rekap.DetailIMBPerluasanListNamaPemohon');
            Route::match(['GET', 'POST'], '/detail-imb-perluasan/{nama_pemohon}', [RekapController::class, 'DetailIMBPerluasanNamaPemohon'])->name('rekap.DetailIMBPerluasanNamaPemohon');
        });

        Route::prefix('rekap-imb')->group(function () {
            Route::match(['GET', 'POST'], '/rekap-penerbitan', [RekapController::class, 'RekapPenerbitan'])->name('rekap.RekapPenerbitan');
            Route::match(['GET', 'POST'], '/rekap-penerbitan-detail', [RekapController::class, 'RekapPenerbitanDetail'])->name('rekap.RekapPenerbitanDetail');

            Route::match(['GET', 'POST'], '/rekap-unit-dan-fungsi', [RekapController::class, 'RekapUnitDanFungsi'])->name('rekap.RekapUnitDanFungsi');
            Route::match(['GET', 'POST'], '/rekap-unit-dan-fungsi-detail', [RekapController::class, 'RekapUnitDanFungsiDetail'])->name('rekap.RekapUnitDanFungsiDetail');

            Route::match(['GET', 'POST'], '/rekap-lokasi', [RekapController::class, 'RekapLokasi'])->name('rekap.RekapLokasi');
            Route::match(['GET', 'POST'], '/rekap-lokasi-detail', [RekapController::class, 'RekapLokasiDetail'])->name('rekap.RekapLokasiDetail');

            Route::match(['GET', 'POST'], '/rekap-unit-fungsi-dan-lokasi', [RekapController::class, 'RekapUnitFungsiDanLokasi'])->name('rekap.RekapUnitFungsiDanLokasi');
            Route::match(['GET', 'POST'], '/rekap-unit-fungsi-dan-lokasi-detail', [RekapController::class, 'RekapUnitFungsiDanLokasiDetail'])->name('rekap.RekapUnitFungsiDanLokasiDetail');

        });

        Route::prefix('rekap-imb-pertahun')->group(function () {
            Route::match(['GET', 'POST'], '/rekap-unit-dan-fungsi', [RekapController::class, 'RekapUnitDanFungsiPertahun'])->name('rekap.RekapUnitDanFungsiPertahun');

            Route::match(['GET', 'POST'], '/rekap-lokasi', [RekapController::class, 'RekapLokasiPertahun'])->name('rekap.RekapLokasiPertahun');

            Route::match(['GET', 'POST'], '/rekap-unit-fungsi-dan-lokasi', [RekapController::class, 'RekapUnitFungsiDanLokasiPertahun'])->name('rekap.RekapUnitFungsiDanLokasiPertahun');
        });

        Route::prefix('rekap-sk-imbg')->group(function () {
            // 10 DAN 10.1

            Route::prefix('register')->group(function () {
                Route::match(['GET', 'POST'], '/perbulan', [ListSuratController::class, 'ListSurat10'])->name('rekap.ListSurat10');
                Route::match(['GET', 'POST'], '/pertahun', [ListSuratController::class, 'ListSurat'])->name('rekap.ListSurat');

            });
            Route::match(['GET', 'POST'], '/pertahun', [RekapController::class, 'RekapSuratPertahun'])->name('rekap.RekapSuratPertahun');
            Route::match(['GET', 'POST'], '/perbulan/{tahun}', [RekapController::class, 'RekapSuratPerbulan'])->name('rekap.RekapSuratPerbulan');
        });
    });


    Route::prefix('surat')->group(function () {
        Route::get('/', [SuratController::class, 'index'])->name('surat.index');
        Route::get('/cari-surat', [SuratController::class, 'cariSurat'])->name('surat.cari-surat');
        Route::get('/create', [SuratController::class, 'create'])->name('surat.create');
        Route::post('/store', [SuratController::class, 'store'])->name('surat.store');
        Route::post('/upload/{id}', [SuratController::class, 'upload'])->name('surat.upload');
        Route::get('/edit/{id}', [SuratController::class, 'edit'])->name('surat.edit');
        Route::post('/update/{id}', [SuratController::class, 'update'])->name('surat.update');
        Route::post('/update_nomor/{id}', [SuratController::class, 'updateNomor'])->name('surat.update_nomor');
        Route::get('/lihat/{id}', [SuratController::class, 'lihatSurat'])->name('surat.lihat');
        Route::get('/lihatTable/{id}', [SuratController::class, 'LihatTableIndex'])->name('surat.lihatTable');
        Route::get('/format-1', [SuratController::class, 'format1'])->name('surat.format-1');
        Route::get('/format-3', [SuratController::class, 'format3'])->name('surat.format-3');
        Route::post('/preview', [SuratController::class, 'preview'])->name('surat.preview');
        Route::post('/preview-table', [SuratController::class, 'previewTable'])->name('surat.previewTable'); // tambahan
        Route::get('/preview-index', [SuratController::class, 'LihatIndex'])->name('surat.previewIndex');
        Route::post('/copyData', [SuratController::class, 'copyData'])->name('surat.copyData');
        Route::get('/suratNomor', [SuratController::class, 'getNomorSuratPemohon'])->name('surat.getNomorSuratPemohon');
        Route::get('/download/{id}', [SuratController::class, 'download'])->name('surat.download');

        Route::delete('/destroy/{id}', [SuratController::class, 'destroy'])->name('surat.destroy');

        Route::get('/cetakHalaman', [SuratController::class, 'cetakHalaman'])->name('surat.cetakHalaman');
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
