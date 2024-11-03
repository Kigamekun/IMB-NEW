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
        $dataRows = $rows->slice(1); // Adjust slice as needed
        $failures = [];
        try {
            foreach ($dataRows as $key => $row) {
                $jenis_kegiatan = DB::table('app_md_jeniskeg')->where('name_jeniskeg', $row[11])->first()->id_jeniskeg;
                $fungsi_bangunan = DB::table('app_md_fungsibang')->where('name_fungsibang', $row[12])->first()->id_fungsibang;
                if (!is_null($row[1])) {
                    if (DB::table('master_district')->where('name', $row[9])->count() == 0) {
                        $failures[$key]['message'] = 'Kecamatan ' . $row[9] . ' tidak ditemukan';
                        $failures[$key]['baris'] = $key;
                        $imbInduk = null;
                        continue;
                    }
                    if (DB::table('master_subdistrict')->where('name', $row[10])->count() == 0) {
                        $failures[$key]['message'] = 'Desa/Kelurahan ' . $row[10] . ' tidak ditemukan';
                        $failures[$key]['baris'] = $key;
                        $imbInduk = null;
                        continue;
                    } else {
                        $districtCodes = DB::table('master_district')
                            ->where('name', $row[9])
                            ->pluck('code')
                            ->toArray();
                        $village = DB::table('master_subdistrict')
                            ->where('name', $row[10])
                            ->whereIn('district_code', $districtCodes)
                            ->first();
                        if (!$village) {
                            $failures[$key]['message'] = 'Desa/Kelurahan ' . $row[10] . ' tidak berada di kecamatan ' . $row[9];
                            $failures[$key]['baris'] = $key;
                            $imbInduk = null;
                            continue;
                        }
                    }

                    $kec = DB::table('master_district')->where('name', $row[9])->first()->code;
                    $kel = DB::table('master_subdistrict')->where('name', $row[10])->first()->code;


                    IMBIndukNonPerum::create([
                        'contoh_jenis' => $row[0],
                        'imb_induk_non_perum' => $row[2],
                        'tgl_imb_induk_non_perum' => date('Y-m-d', strtotime($row[3])),
                        'no_register' => $row[4],
                        'tgl_register' => date('Y-m-d', strtotime($row[5])),
                        'nama' => $row[6],
                        'atas_nama' => $row[7],
                        'lokasi' => $row[8],
                        'kecamatan' => $kec,
                        'desa_kelurahan' => $kel,
                        'jenis_kegiatan' => $jenis_kegiatan,
                        'fungsi_bangunan' => $fungsi_bangunan,
                        'luas_bangunan' => $row[13],
                        'detail_luas_bangunan' => $row[14],
                        'keterangan' => $row[15],
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
