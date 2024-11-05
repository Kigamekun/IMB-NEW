<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\IMBPerluasan;
use Illuminate\Support\Facades\DB;

class ImportIMBPerluasan implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // Skip header rows if necessary
        $dataRows = $rows->slice(1); // Adjust slice as needed
        $failures = [];
        $jenisKegiatanList = DB::table('app_md_jeniskeg')->pluck('id_jeniskeg', 'name_jeniskeg')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $fungsiBangunanList = DB::table('app_md_fungsibang')->pluck('id_fungsibang', 'name_fungsibang')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        try {
            foreach ($dataRows as $key => $row) {

                $rowJenisKegiatan = strtolower($row[9]);
                $rowFungsiBangunan = strtolower($row[10]);
                $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
                $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;

                if (!is_null($row[1])) {
                    $rowDistrict = strtolower($row[12]);
                    $rowSubdistrict = strtolower($row[13]);
                    $districts = DB::table('master_district')
                        ->where(DB::raw('LOWER(name)'), $rowDistrict)
                        ->pluck('code')
                        ->toArray();
                    if (empty($districts)) {
                        $failures[$key] = [
                            'message' => 'Kecamatan ' . $row[12] . ' tidak ditemukan',
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
                            'message' => 'Desa/Kelurahan ' . $row[13] . ' tidak ditemukan di kecamatan ' . $row[12],
                            'baris' => $key,
                        ];
                        continue;
                    }
                    IMBPerluasan::create([
                        'imb_pecahan' => $row[1],
                        'tgl_pecahan' => date('Y-m-d', strtotime($row[2])),
                        'imb_perluasan' => $row[3],
                        'tgl_imb_perluasan' => date('Y-m-d', strtotime($row[4])),
                        'no_register' => $row[5],
                        'tgl_register' => date('Y-m-d', strtotime($row[6])),
                        'nama' => $row[7],
                        'atas_nama' => $row[8],
                        'jenis_kegiatan' => $jenis_kegiatan,
                        'fungsi_bangunan' => $fungsi_bangunan,
                        'lokasi_perumahan' => $row[11],
                        'kecamatan' => $village->district_code,
                        'desa_kelurahan' => $village->code,
                        'type' => $row[14],
                        'luas_bangunan_lama' => $row[15],
                        'luas_bangunan_perluasan' => $row[16],
                        'blok' => $row[17],
                        'no_blok' => $row[18],
                        'keterangan' => $row[19],
                    ]);
                }

            }
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Data successfully added!',
                'failures' => $failures
            ]);
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->back()->with(['status' => 'error', 'message' => 'Anda memiliki kesalahan format']);
        }
    }
}
