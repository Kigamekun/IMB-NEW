<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterController extends Controller
{

    public function getProvinsi(Request $request)
    {
        $search = $request->input('q');
        $page = $request->input('page', 1);
        $pageSize = 10;

        // Fetch data with pagination and search term
        $query = DB::table('master_province')->select('code', 'name');
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $results = $query
            ->get();

        $morePages = count($results) == $pageSize;

        return response()->json([
            'items' => $results->map(function ($item) {
                return [
                    'id' => $item->code,
                    'text' => $item->name
                ];
            }),
            'pagination' => [
                'more' => $morePages
            ]
        ]);
    }

    public function getKabupaten(Request $request)
    {
        $search = $request->input('q');
        $provinsiId = $request->input('provinsi_id'); // Get the provinsi_id from the request
        $page = $request->input('page', 1);
        $pageSize = 10;

        $query = DB::table('master_regency')
            ->select('code', 'name');

        // Filter by provinsi_id if provided
        if ($provinsiId) {
            $query->where('province_code', $provinsiId); // Adjust the column name as needed
        }

        // Apply search if provided
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $results = $query
            ->get();

        $morePages = count($results) == $pageSize;

        return response()->json([
            'items' => $results->map(function ($item) {
                return [
                    'id' => $item->code,
                    'text' => $item->name
                ];
            }),
            'pagination' => [
                'more' => $morePages
            ]
        ]);
    }

    public function getKecamatan(Request $request)
    {
        $search = $request->input('q');
        $kabupatenId = $request->input('kabupaten_id'); // Get the kabupaten_id from the request
        $page = $request->input('page', 1);
        $pageSize = 10;

        $query = DB::table('master_district')
            ->select('code', 'name');

        // Filter by kabupaten_id if provided
        if ($kabupatenId) {
            $query->where('regency_code', $kabupatenId); // Adjust the column name as needed
        }

        // Apply search if provided
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $results = $query
            ->get();

        $morePages = count($results) == $pageSize;

        return response()->json([
            'items' => $results->map(function ($item) {
                return [
                    'id' => $item->code,
                    'text' => $item->name
                ];
            }),
            'pagination' => [
                'more' => $morePages
            ]
        ]);
    }

    public function getKelurahan(Request $request)
    {
        $search = $request->input('q');
        $kecamatanId = $request->input('kecamatan_id'); // Get the kecamatan_id from the request
        $page = $request->input('page', 1);
        $pageSize = 10;

        $query = DB::table('master_subdistrict')
            ->select('code', 'name', 'district_code');

        // Filter by kecamatan_id if provided
        if ($kecamatanId) {
            $query->where('district_code', $kecamatanId); // Adjust the column name as needed
        }

        // Apply search if provided
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $results = $query
            ->get();

        $morePages = count($results) == $pageSize;

        return response()->json([
            'items' => $results->map(function ($item) {
                return [
                    'kecamatan' => $item->district_code,
                    'id' => $item->code,
                    'text' => $item->name
                ];
            }),
            'pagination' => [
                'more' => $morePages
            ]
        ]);
    }

    public function getIMBInduk(Request $request)
    {
        $search = $request->input('q');
        $page = $request->input('page', 1);
        $pageSize = 10;

        // Base query to fetch data from imb_induk_perum and imb_induk_non_perum
        $query = DB::table('imb_induk_perum')
            ->select('id', 'imb_induk', 'tgl_imb_induk', DB::raw("'perum' as jenis"))
            ->when($search, function ($query, $search) {
                $query->where('imb_induk', 'like', '%' . $search . '%');
            })
            ->unionAll(
                DB::table('imb_induk_non_perum')
                    ->select('id', 'imb_induk', 'tgl_imb_induk', DB::raw("'non_perum' as jenis"))
                    ->when($search, function ($query, $search) {
                        $query->where('imb_induk', 'like', '%' . $search . '%');
                    })
            );

        // Apply pagination
        $results = DB::table(DB::raw("({$query->toSql()}) as combined"))
            ->mergeBindings($query)
            ->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->get();

        $morePages = count($results) == $pageSize;

        return response()->json([
            'items' => $results->map(function ($item) {
                return [
                    'id' => $item->imb_induk . ' | ' . $item->tgl_imb_induk . ' | ' . $item->jenis,
                    'text' => $item->imb_induk . ' | ' . $item->tgl_imb_induk . ' | ' . $item->jenis
                ];
            }),
            'pagination' => [
                'more' => $morePages
            ]
        ]);
    }



    public function getIMBPecahan(Request $request)
    {
        $search = $request->input('q');
        $page = $request->input('page', 1);
        $pageSize = 10;

        // Fetch data with pagination and search term
        $query = DB::table('imb_pecahan')->select('id', 'imb_pecahan', 'tgl_imb_pecahan');
        if ($search) {
            $query->where('imb_pecahan', 'like', '%' . $search . '%');
        }

        $results = $query->offset(($page - 1) * $pageSize)
            ->limit($pageSize)
            ->get();

        $morePages = count($results) == $pageSize;

        return response()->json([
            'items' => $results->map(function ($item) {
                return [
                    'id' => $item->imb_pecahan . ' | ' . $item->tgl_imb_pecahan,
                    'text' => $item->imb_pecahan . ' | ' . $item->tgl_imb_pecahan
                ];
            }),
            'pagination' => [
                'more' => $morePages
            ]
        ]);
    }

}
