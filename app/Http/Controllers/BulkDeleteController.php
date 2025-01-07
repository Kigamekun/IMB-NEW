<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IMBIndukPerum;
use App\Models\IMBPecahan;
use App\Models\IMBPerluasan;
use App\Models\IMBIndukNonPerum;

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
            dd('ada');
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
}
