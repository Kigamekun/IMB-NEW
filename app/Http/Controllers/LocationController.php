<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IMBIndukPerum;
use App\Models\IMBPecahan;
use App\Models\IMBPerluasan;
use App\Models\IMBIndukNonPerum;

class LocationController extends Controller
{
    public function getLocations(Request $request)
    {
        $category = $request->input('category');
        $kabupaten = [];

        switch ($category) {
            case 'induk':
                $kabupaten = IMBIndukPerum::whereNull('kabupaten')->pluck('kabupaten_lama')->unique();
                break;
            case 'pecahan':
                $kabupaten = IMBPecahan::whereNull('kabupaten')->pluck('kabupaten_lama')->unique();
                break;
            case 'perluasan':
                $kabupaten = IMBPerluasan::whereNull('kabupaten')->pluck('kabupaten_lama')->unique();
                break;
            case 'non_perum':
                $kabupaten = IMBIndukNonPerum::whereNull('kabupaten')->pluck('kabupaten_lama')->unique();
                break;
        }


        return response()->json(['kabupaten' => $kabupaten->values()->toArray()]);
    }

    public function getKecamatan(Request $request)
    {
        $category = $request->input('category');
        $kabupaten = $request->input('kabupaten');
        $kecamatan = [];

        switch ($category) {
            case 'induk':
                $kecamatan = IMBIndukPerum::whereNull('kecamatan')->pluck('kecamatan_lama')->unique();
                break;
            case 'pecahan':
                $kecamatan = IMBPecahan::whereNull('kecamatan')->pluck('kecamatan_lama')->unique();
                break;
            case 'perluasan':
                $kecamatan = IMBPerluasan::whereNull('kecamatan')->pluck('kecamatan_lama')->unique();
                break;
            case 'non_perum':
                $kecamatan = IMBIndukNonPerum::whereNull('kecamatan')->pluck('kecamatan_lama')->unique();
                break;
        }

        return response()->json(['kecamatan' => $kecamatan->values()->toArray()]);
    }

    public function getDesa(Request $request)
    {
        $category = $request->input('category');
        $kecamatan = $request->input('kecamatan');
        $desa = [];

        switch ($category) {
            case 'induk':
                $desa = IMBIndukPerum::whereNull('desa_kelurahan')->pluck('kelurahan_lama')->unique();
                break;
            case 'pecahan':
                $desa = IMBPecahan::whereNull('desa_kelurahan')->pluck('kelurahan_lama')->unique();
                break;
            case 'perluasan':
                $desa = IMBPerluasan::whereNull('desa_kelurahan')->pluck('kelurahan_lama')->unique();
                break;
            case 'non_perum':
                $desa = IMBIndukNonPerum::whereNull('desa_kelurahan')->pluck('kelurahan_lama')->unique();
                break;
        }

        return response()->json(['desa' => $desa->values()->toArray()]);
    }
}
