<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IMBIndukPerum;
use App\Models\IMBPecahan;
use App\Models\IMBPerluasan;
use App\Models\IMBIndukNonPerum;
use Illuminate\Support\Facades\DB;


class BulkDeleteController extends Controller
{
    // Peta model berdasarkan kategori
    private $models = [
        'induk' => IMBIndukPerum::class,
        'pecahan' => IMBPecahan::class,
        'perluasan' => IMBPerluasan::class,
        'non_perum' => IMBIndukNonPerum::class,
    ];

    public function index(Request $request)
    {
        $category = $request->input('category', 'induk'); // Default ke 'induk'

        // Validasi kategori
        if (!array_key_exists($category, $this->models)) {
            return redirect()->back()->withErrors(['Invalid category']);
        }

        // Ambil model sesuai kategori
        $model = $this->models[$category];
        $query = $model::query();

        // Filter data (opsional, berdasarkan input)
        if ($request->has('kabupaten') and $request->kabupaten != '') {
            $query->where('kabupaten_lama', $request->kabupaten);
        }

        $query->whereNull('kabupaten');

        if ($request->has('kecamatan') and $request->kecamatan != '') {
            $query->where('kecamatan_lama', $request->kecamatan);
        }

        $query->whereNull('kecamatan');

        if ($request->has('desa') and $request->desa != '') {
            $query->where('kelurahan_lama', $request->desa);
        }

        $query->whereNull('desa_kelurahan');

        $data = $query->get();

        return view('bulk-delete.index', compact('data', 'category'));
    }

    public function delete(Request $request)
    {
        $category = $request->input('category', 'induk'); // Default ke 'induk'
        // Validasi kategori
        if (!array_key_exists($category, $this->models)) {
            return redirect()->back()->withErrors(['Invalid category']);
        }

        // Ambil model sesuai kategori
        $model = $this->models[$category];

        // Validasi request
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:' . (new $model)->getTable() . ',id',
        ]);


        // Hapus data yang dipilih
        $model::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with(['status' => 'success', 'message' => 'Data berhasil didelete']);
    }

    public function deleteDuplicates(Request $request)
    {
        $tableConfig = [
            'imb_induk' => [
                'table' => 'imb_induk_perum',
                'id_column' => 'imb_induk',
                'date_column' => 'tgl_imb_induk',
                'model' => IMBIndukPerum::class
            ],
            'imb_pecahan' => [
                'table' => 'imb_pecahan',
                'id_column' => 'imb_pecahan',
                'date_column' => 'tgl_imb_pecahan',
                'model' => IMBPecahan::class
            ],
            'imb_perluasan' => [
                'table' => 'imb_perluasan',
                'id_column' => 'imb_perluasan',
                'date_column' => 'tgl_imb_perluasan',
                'model' => IMBPerluasan::class
            ],
            'imb_induk_non_perum' => [
                'table' => 'imb_induk',
                'id_column' => 'imb_induk_id',
                'date_column' => 'tgl_imb_induk',
                'model' => IMBIndukNonPerum::class
            ]
        ];

        $type = $request->type;
        if (!isset($tableConfig[$type])) {
            return redirect()->back()->with(['status' => 'error', 'message' => 'Tipe tidak valid']);
        }

        $config = $tableConfig[$type];
        $totalDeleted = 0;
        $batchSize = 1000; // Jumlah record yang diproses per batch

        try {
            // Set timeout lebih lama
            set_time_limit(300); // 5 menit

            // Dapatkan daftar ID duplikat
            $duplicateIds = DB::table($config['table'] . ' as t1')
                ->select('t1.id')
                ->join($config['table'] . ' as t2', function($join) use ($config) {
                    $join->on('t1.' . $config['id_column'], '=', 't2.' . $config['id_column'])
                         ->on('t1.' . $config['date_column'], '=', 't2.' . $config['date_column'])
                         ->whereRaw('t1.id > t2.id');
                })
                ->pluck('id')
                ->toArray();

            // Proses penghapusan dalam batch
            foreach (array_chunk($duplicateIds, $batchSize) as $chunk) {
                $deleted = DB::table($config['table'])
                    ->whereIn('id', $chunk)
                    ->delete();

                $totalDeleted += $deleted;

                // Beri waktu sistem untuk bernafas
                usleep(100000); // delay 0.1 detik
            }

            return redirect()->back()->with([
                'status' => 'success',
                'message' => "Berhasil menghapus $totalDeleted data duplikat"
            ]);

        } catch (\Exception $e) {
            \Log::error('Error deleting duplicates: ' . $e->getMessage());
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus data duplikat'
            ]);
        }
    }
}
