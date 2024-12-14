<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IMBPecahan;
use App\Models\IMBPerluasan;
use \Yajra\DataTables\DataTables;
class ListSuratController extends Controller
{
    // Rekap 10-1
    public function ListSurat(Request $request)
    {
        if ($request->ajax()) {
            // Query with join to get district and subdistrict names
            $query = \DB::table('surat')
                ->leftJoin('master_regency', 'surat.kabupaten', '=', 'master_regency.code') // Join to master_regency
                ->leftJoin('master_district', 'surat.kecamatan', '=', 'master_district.code') // Join to master_district
                ->leftJoin('master_subdistrict', 'surat.kelurahan', '=', 'master_subdistrict.code') // Join to master_subdistrict
                ->select(
                    'surat.*',
                    'master_regency.name as nama_kabupaten',
                    'master_district.name as nama_kecamatan',
                    'master_subdistrict.name as nama_kelurahan',
                    'surat.tujuanSurat', // Ensure this field is selected
                    'surat.jenisKegiatan' // Ensure this field is selected
                );

            // Apply filters based on request input
            if ($request->has('nomor_surat')) {
                $query->where('surat.nomorSurat', 'like', '%' . $request->input('nomor_surat') . '%');
            }

            if ($request->has('nama_pemohon')) {
                $query->where('surat.nama', 'like', '%' . $request->input('nama_pemohon') . '%');
            }

            if ($request->has('lokasi_bangunan')) {
                $query->where('surat.lokasi', 'like', '%' . $request->input('lokasi_bangunan') . '%');
            }

            if ($request->has('kecamatan_pemohon')) {
                $query->where('master_district.name', 'like', '%' . $request->input('kecamatan_pemohon') . '%');
            }

            if ($request->has('kelurahan_pemohon')) {
                $query->where('master_subdistrict.name', 'like', '%' . $request->input('kelurahan_pemohon') . '%');
            }

            // Get data and return as JSON for DataTables
            $data = $query->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->make(true);
        }

        // Return the view when not an AJAX request
        $tahun = '2024';
        return view('rekap.rekap-register.register-pertahun', compact('tahun'));
    }

    // Data 10
    public function ListSurat10(Request $request)
    {
        if ($request->ajax()) {
            // Query with join to get district and subdistrict names
            $query = DB::table('surat')
                ->leftJoin('master_regency', 'surat.kabupaten', '=', 'master_regency.code') // Join to master_regency
                ->leftJoin('master_district', 'surat.kecamatan', '=', 'master_district.code') // Join to master_district
                ->leftJoin('master_subdistrict', 'surat.kelurahan', '=', 'master_subdistrict.code') // Join to master_subdistrict
                ->select(
                    'surat.*',
                    'master_regency.name as nama_kabupaten',
                    'master_district.name as nama_kecamatan',
                    'master_subdistrict.name as nama_kelurahan',
                    'surat.tujuanSurat', // Ensure this field is selected
                    'surat.jenisKegiatan' // Ensure this field is selected
                );

            // Apply filters based on request input
            if ($request->has('nomor_surat')) {
                $query->where('surat.nomorSurat', 'like', '%' . $request->input('nomor_surat') . '%');
            }

            if ($request->has('nama_pemohon')) {
                $query->where('surat.nama', 'like', '%' . $request->input('nama_pemohon') . '%');
            }

            if ($request->has('lokasi_bangunan')) {
                $query->where('surat.lokasi', 'like', '%' . $request->input('lokasi_bangunan') . '%');
            }

            if ($request->has('kecamatan_pemohon')) {
                $query->where('master_district.name', 'like', '%' . $request->input('kecamatan_pemohon') . '%');
            }

            if ($request->has('kelurahan_pemohon')) {
                $query->where('master_subdistrict.name', 'like', '%' . $request->input('kelurahan_pemohon') . '%');
            }


            // Get data and return as JSON for DataTables
            $data = $query->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->make(true);
        }

        // Return the view when not an AJAX request
        $tahun = '2024';
        return view('rekap.rekap-register.register-perbulan', compact('tahun'));
    }
    // public function ListSurat(Reque


    // public function ListSurat(Request $request)
    // {
    //     if($request->ajax()) {
    //         // Query dengan join untuk mendapatkan nama kecamatan dan kelurahan
    //         $query = \DB::table('surat')
    //         ->leftJoin('master_district', 'surat.kecamatan', '=', 'master_district.code') // Join ke master_district
    //         ->leftJoin('master_subdistrict', 'surat.kelurahan', '=', 'master_subdistrict.code') // Join ke master_subdistrict
    //         ->select(
    //             'surat.*',
    //             'master_district.name as nama_kecamatan',
    //             'master_subdistrict.name as nama_kelurahan'
    //         );

    //         // Filter berdasarkan input dari request
    //         if ($request->has('nomor_surat')) {
    //         $query->where('surat.nomorSurat', 'like', '%' . $request->input('nomor_surat') . '%');
    //         }

    //         if ($request->has('nama_pemohon')) {
    //         $query->where('surat.nama', 'like', '%' . $request->input('nama_pemohon') . '%');
    //         }

    //         if ($request->has('lokasi_bangunan')) {
    //         $query->where('surat.lokasi', 'like', '%' . $request->input('lokasi_bangunan') . '%');
    //         }

    //         if ($request->has('kecamatan_pemohon')) {
    //         $query->where('master_district.name', 'like', '%' . $request->input('kecamatan_pemohon') . '%');
    //         }

    //         if ($request->has('kelurahan_pemohon')) {
    //         $query->where('master_subdistrict.name', 'like', '%' . $request->input('kelurahan_pemohon') . '%');
    //         }

    //         // Ambil data
    //         $data = $query->get();
    //     }


    //     $tahun = '2024';
    //     return view('rekap.10-1', compact('tahun', 'data'));
    // }

}
