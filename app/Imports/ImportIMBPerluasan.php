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
                $fail = 0;

                $rowJenisKegiatan = strtolower($row[9]);
                $rowFungsiBangunan = strtolower($row[10]);
                $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
                $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;

                if (!is_null($row[1])) {
                    $rowRegency = strtolower($row[12]);
                    $rowDistrict = strtolower($row[13]);
                    $rowSubdistrict = strtolower($row[14]);

                    $regency = DB::table('master_regency')
                    ->where(DB::raw('LOWER(name)'), $rowRegency)
                    ->pluck('code')
                    ->first();

                    // dd($regency);
                    if (!$regency) {
                        $fail = 1;
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
                            'kabupaten_lama' => $row[12],
                            'kecamatan_lama' => $row[13],
                            'kelurahan_lama' => $row[14],
                            'type' => $row[15],
                            'luas_bangunan_lama' => $row[16],
                            'luas_bangunan_perluasan' => $row[17],
                            'blok' => $row[18],
                            'no_blok' => $row[19],
                            'keterangan' => $row[20],
                        ]);
                        $failures[$key] = [
                            'message' => 'Kabupaten/Kota ' . $row[12] . ' tidak ditemukan ',
                            'baris' => $key,
                        ];
                       // continue;
                    }
                    $errorDistricts = 0;
                    $districts = DB::table('master_district')
                        ->where(DB::raw('LOWER(name)'), $rowDistrict)
                        ->pluck('code')
                        ->toArray();
                    /// dd($districts);
                    if (empty($districts)) {
                        $fail = 1;
                        $errorDistricts = 1;
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
                            'kabupaten_lama' => $row[12],
                            'kecamatan_lama' => $row[13],
                            'kelurahan_lama' => $row[14],
                            'type' => $row[15],
                            'luas_bangunan_lama' => $row[16],
                            'luas_bangunan_perluasan' => $row[17],
                            'blok' => $row[18],
                            'no_blok' => $row[19],
                            'keterangan' => $row[20],
                        ]);
                        $failures[$key] = [
                            'message' => 'Kecamatan ' . $row[13] . ' tidak ditemukan',
                            'baris' => $key,
                        ];
                     //   continue;
                    }
                    $village = DB::table('master_subdistrict')
                        ->where(DB::raw('LOWER(name)'), $rowSubdistrict)
                        ->whereIn('district_code', $districts)
                        ->first();
                    if (!$village) {
                        if ($errorDistricts == 1) {
                            $failures[$key] = [
                                'message' => 'Desa/Kelurahan ' . $row[14] . ' tidak ditemukan di kecamatan ' . $row[13],
                                'baris' => $key,
                            ];
                           continue;
                        }
                        $fail = 1;
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
                            'kabupaten_lama' => $row[12],
                            'kecamatan_lama' => $row[13],
                            'kelurahan_lama' => $row[14],
                            'type' => $row[15],
                            'luas_bangunan_lama' => $row[16],
                            'luas_bangunan_perluasan' => $row[17],
                            'blok' => $row[18],
                            'no_blok' => $row[19],
                            'keterangan' => $row[20],
                        ]);
                        $failures[$key] = [
                            'message' => 'Desa/Kelurahan ' . $row[14] . ' tidak ditemukan di kecamatan ' . $row[13],
                            'baris' => $key,
                        ];
                       // continue;
                    }
                   // dd($village);

                    if ($fail == 0) {
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
                            'kabupaten' => $regency,
                            'kecamatan' => $village->district_code,
                            'desa_kelurahan' => $village->code,
                            'type' => $row[15],
                            'luas_bangunan_lama' => $row[16],
                            'luas_bangunan_perluasan' => $row[17],
                            'blok' => $row[18],
                            'no_blok' => $row[19],
                            'keterangan' => $row[20],
                        ]);
                    }
                }

            }
            if (count($failures) > 0) {
                //dd($failures);
                return redirect()->back()->with(['status' => 'error', 'message' => 'Import data selesai, namun terdapat kesalahan. Silahkan download file log untuk melihat detail kesalahan.'])->with('failures', $failures);
            } else {
                return redirect()->back()->with(['status' => 'success', 'message' => 'Import data berhasil']);
            }
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->back()->with(['status' => 'error', 'message' => 'Anda memiliki kesalahan format']);
        }
    }
}
