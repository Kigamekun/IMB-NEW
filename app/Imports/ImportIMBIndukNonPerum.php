<?php

namespace App\Imports;

use App\Models\IMBIndukNonPerum;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\IMBPecahan;
use Illuminate\Support\Facades\DB;

class ImportIMBIndukNonPerum implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        $dataRows = $rows->slice(1); // Skip header row
        $failures = [];
        $batchData = [];
        $jenisKegiatanList = DB::table('app_md_jeniskeg')->pluck('id_jeniskeg', 'name_jeniskeg')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $fungsiBangunanList = DB::table('app_md_fungsibang')->pluck('id_fungsibang', 'name_fungsibang')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $jenisList = DB::table('master_jenis_non_perum')->pluck('id', DB::raw('LOWER(name)'));
        try {
            foreach ($dataRows as $key => $row) {
                $rowDistrict = strtolower($row[9]);
                $rowSubdistrict = strtolower($row[10]);
                $rowJenisKegiatan = strtolower($row[11]);
                $rowFungsiBangunan = strtolower($row[12]);
                $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
                $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;
                if (!is_null($row[1])) {
                    $districts = DB::table('master_district')
                        ->where(DB::raw('LOWER(name)'), $rowDistrict)
                        ->pluck('code')
                        ->toArray();
                    if (empty($districts)) {
                        $failures[$key] = [
                            'message' => 'Kecamatan ' . $row[9] . ' tidak ditemukan',
                            'baris' => $key,
                        ];
                        continue;
                    }
                    $village = DB::table('master_subdistrict')
                        ->where(DB::raw('LOWER(name)'), $rowSubdistrict)
                        ->whereIn('district_code', $districts)
                        ->first();
                    if (!$village) {
                        $failures[$key] = [
                            'message' => 'Desa/Kelurahan ' . $row[10] . ' tidak ditemukan di kecamatan ' . $row[9],
                            'baris' => $key,
                        ];
                        continue;
                    }
                    $jenis = $jenisList[strtolower($row[0])] ?? null;
                    $batchData[] = [
                        'contoh_jenis' => $jenis,
                        'imb_induk_non_perum' => $row[2],
                        'tgl_imb_induk_non_perum' => date('Y-m-d', strtotime($row[3])),
                        'no_register' => $row[4],
                        'tgl_register' => date('Y-m-d', strtotime($row[5])),
                        'nama' => $row[6],
                        'atas_nama' => $row[7],
                        'lokasi' => $row[8],
                        'kecamatan' => $village->district_code,
                        'desa_kelurahan' => $village->code,
                        'jenis_kegiatan' => $jenis_kegiatan,
                        'fungsi_bangunan' => $fungsi_bangunan,
                        'luas_bangunan' => $row[13],
                        'detail_luas_bangunan' => $row[14],
                        'keterangan' => $row[15],
                    ];
                }
            }
            if (!empty($batchData)) {
                IMBIndukNonPerum::insert($batchData);
            }
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Data successfully added!',
                'failures' => $failures
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'Anda memiliki kesalahan format'
            ]);
        }
    }
}
