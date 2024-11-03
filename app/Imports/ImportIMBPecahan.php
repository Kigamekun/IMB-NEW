<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\IMBPecahan;
use Illuminate\Support\Facades\DB;




class ImportIMBPecahan implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $rows)
    {
        // Skip header rows if necessary
        $dataRows = $rows->slice(1); // Adjust slice as needed
        $failures = [];

        try {
            foreach ($dataRows as $key => $row) {


                $jenis_kegiatan = DB::table('app_md_jeniskeg')->where('name_jeniskeg', $row[9])->first()->id_jeniskeg;
                $fungsi_bangunan = DB::table('app_md_fungsibang')->where('name_fungsibang', $row[10])->first()->id_fungsibang;


                if (!is_null($row[1])) {
                    if (DB::table('master_district')->where('name', $row[12])->count() == 0) {
                        $failures[$key]['message'] = 'Kecamatan ' . $row[12] . ' tidak ditemukan';
                        $failures[$key]['baris'] = $key;
                        $imbInduk = null;
                        continue;
                    }
                    if (DB::table('master_subdistrict')->where('name', $row[13])->count() == 0) {
                        $failures[$key]['message'] = 'Desa/Kelurahan ' . $row[13] . ' tidak ditemukan';
                        $failures[$key]['baris'] = $key;
                        $imbInduk = null;
                        continue;
                    } else {
                        $districtCodes = DB::table('master_district')
                            ->where('name', $row[12])
                            ->pluck('code')
                            ->toArray();
                        $village = DB::table('master_subdistrict')
                            ->where('name', $row[13])
                            ->whereIn('district_code', $districtCodes)
                            ->first();
                        if (!$village) {
                            $failures[$key]['message'] = 'Desa/Kelurahan ' . $row[13] . ' tidak berada di kecamatan ' . $row[12];
                            $failures[$key]['baris'] = $key;
                            $imbInduk = null;
                            continue;
                        }
                    }

                    $kec = DB::table('master_district')->where('name', $row[12])->first()->code;
                    $kel = DB::table('master_subdistrict')->where('name', $row[13])->first()->code;



                    IMBPecahan::create([
                        'imb_induk_id' => $row[1],
                        'tgl_imb_induk' => date('Y-m-d', strtotime($row[2])),
                        'imb_pecahan' => $row[3],
                        'tgl_imb_pecahan' => date('Y-m-d', strtotime($row[4])),
                        'no_register' => $row[5],
                        'tgl_register' => date('Y-m-d', strtotime($row[6])),
                        'nama' => $row[7],
                        'atas_nama' => $row[8],
                        'jenis_kegiatan' => $jenis_kegiatan,
                        'fungsi_bangunan' => $fungsi_bangunan,
                        'lokasi_perumahan' => $row[11],
                        'kecamatan' => $kec,
                        'desa_kelurahan' => $kel,
                        'type' => $row[14],
                        'luas' => $row[15],
                        'blok' => $row[16],
                        'no_blok' => $row[17],
                        'keterangan' => $row[18],
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
