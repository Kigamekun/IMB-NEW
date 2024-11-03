<?php

namespace App\Imports;

use App\Models\IMBIndukNonPerum;
use App\Models\IMBIndukPerum;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\IMBItem;

use Illuminate\Support\Facades\DB;


class ImportIMBIndukPerum implements ToCollection
{
    public function collection(Collection $rows)
    {
        $dataRows = $rows->slice(1); // Skip the header rows
        $imbInduk = null;
        $failures = [];
        $jenis_kegiatan_array = [];
        try {
            foreach ($dataRows as $key => $row) {
                if (!is_null($row[1])) {
                    if (DB::table('master_district')->where('name', $row[8])->count() == 0) {
                        $failures[$key]['message'] = 'Kecamatan ' . $row[8] . ' tidak ditemukan';
                        $failures[$key]['baris'] = $key;
                        $imbInduk = null;
                        continue;
                    }
                    if (DB::table('master_subdistrict')->where('name', $row[9])->count() == 0) {
                        $failures[$key]['message'] = 'Desa/Kelurahan ' . $row[9] . ' tidak ditemukan';
                        $failures[$key]['baris'] = $key;
                        $imbInduk = null;
                        continue;
                    } else {
                        $districtCodes = DB::table('master_district')
                            ->where('name', $row[8])
                            ->pluck('code')
                            ->toArray();
                        $village = DB::table('master_subdistrict')
                            ->where('name', $row[9])
                            ->whereIn('district_code', $districtCodes)
                            ->first();
                        if (!$village) {
                            $failures[$key]['message'] = 'Desa/Kelurahan ' . $row[9] . ' tidak berada di kecamatan ' . $row[8];
                            $failures[$key]['baris'] = $key;
                            $imbInduk = null;
                            continue;
                        }
                    }
                    $kec = DB::table('master_district')->where('name', $row[8])->first()->code;
                    $kel = DB::table('master_subdistrict')->where('name', $row[9])->first()->code;
                    $jenis_kegiatan = DB::table('app_md_jeniskeg')->where('name_jeniskeg', $row[10])->first()->id_jeniskeg;
                    $fungsi_bangunan = DB::table('app_md_fungsibang')->where('name_fungsibang', $row[11])->first()->id_fungsibang;
                    if (!is_null($imbInduk)) {
                        $jenisKegiatanGabungan = implode(' / ', array_unique($jenis_kegiatan_array));
                        $jenisKegiatanRecord = DB::table('app_md_jeniskeg')
                            ->where('name_jeniskeg', $jenisKegiatanGabungan)
                            ->first();
                        if (!$jenisKegiatanRecord) {
                            $jenisKegiatanId = DB::table('app_md_jeniskeg')->insertGetId([
                                'name_jeniskeg' => $jenisKegiatanGabungan
                            ]);
                        } else {
                            $jenisKegiatanId = $jenisKegiatanRecord->id_jeniskeg;
                        }
                        DB::table('imb_induk_perum')
                            ->where('id', $imbInduk->id)
                            ->update(['jenis_kegiatan' => $jenisKegiatanId]);
                        $jenis_kegiatan_array = [];
                    }
                    $imbInduk = IMBIndukPerum::create([
                        'imb_induk' => $row[1],
                        'tgl_imb_induk' => date('Y-m-d', strtotime($row[2])),
                        'no_register' => $row[3],
                        'tgl_register' => $row[4] != '' ? date('Y-m-d', strtotime($row[4])) : null,
                        'nama' => $row[5],
                        'atas_nama' => $row[6],
                        'lokasi_perumahan' => $row[7],
                        'kecamatan' => $kec,
                        'desa_kelurahan' => $kel,
                    ]);
                    if (!in_array($row[10], $jenis_kegiatan_array)) {
                        $jenis_kegiatan_array[] = $row[10];
                    }
                    IMBItem::create([
                        'induk_perum_id' => $imbInduk->id,
                        'jenis_kegiatan' => $jenis_kegiatan,
                        'fungsi_bangunan' => $fungsi_bangunan,
                        'type' => $row[12],
                        'luas_bangunan' => $row[13],
                        'jumlah_unit' => $row[14],
                        'keterangan' => $row[15],
                        'scan_imb' => $row[16],
                    ]);
                    if ($key == count($dataRows)) {
                        $jenisKegiatanGabungan = implode(' / ', array_unique($jenis_kegiatan_array));
                        $jenisKegiatanRecord = DB::table('app_md_jeniskeg')
                            ->where('name_jeniskeg', $jenisKegiatanGabungan)
                            ->first();
                        if (!$jenisKegiatanRecord) {
                            // Jika belum ada, insert dan dapatkan ID baru
                            $jenisKegiatanId = DB::table('app_md_jeniskeg')->insertGetId([
                                'name_jeniskeg' => $jenisKegiatanGabungan
                            ]);
                        } else {
                            // Jika sudah ada, gunakan ID yang ditemukan
                            $jenisKegiatanId = $jenisKegiatanRecord->id_jeniskeg;
                        }
                        DB::table('imb_induk_perum')
                            ->where('id', $imbInduk->id)
                            ->update(['jenis_kegiatan' => $jenisKegiatanId]);
                    }
                } else {
                    if (!is_null($imbInduk)) {
                        $jenis_kegiatan = DB::table('app_md_jeniskeg')->where('name_jeniskeg', $row[10])->first()->id_jeniskeg;
                        $fungsi_bangunan = DB::table('app_md_fungsibang')->where('name_fungsibang', $row[11])->first()->id_fungsibang;
                        if (!in_array($row[10], $jenis_kegiatan_array)) {
                            $jenis_kegiatan_array[] = $row[10];
                        }
                        IMBItem::create([
                            'induk_perum_id' => $imbInduk->id,
                            'jenis_kegiatan' => $jenis_kegiatan,
                            'fungsi_bangunan' => $fungsi_bangunan,
                            'type' => $row[12],
                            'luas_bangunan' => $row[13],
                            'jumlah_unit' => $row[14],
                            'keterangan' => $row[15],
                        ]);
                        if ($key == count($dataRows)) {
                            $jenisKegiatanGabungan = implode(' / ', array_unique($jenis_kegiatan_array));
                            $jenisKegiatanRecord = DB::table('app_md_jeniskeg')
                                ->where('name_jeniskeg', $jenisKegiatanGabungan)
                                ->first();
                            if (!$jenisKegiatanRecord) {
                                // Jika belum ada, insert dan dapatkan ID baru
                                $jenisKegiatanId = DB::table('app_md_jeniskeg')->insertGetId([
                                    'name_jeniskeg' => $jenisKegiatanGabungan
                                ]);
                            } else {
                                // Jika sudah ada, gunakan ID yang ditemukan
                                $jenisKegiatanId = $jenisKegiatanRecord->id_jeniskeg;
                            }
                            DB::table('imb_induk_perum')
                                ->where('id', $imbInduk->id)
                                ->update(['jenis_kegiatan' => $jenisKegiatanId]);
                        }
                    }
                }
            }
            return redirect()->back()->with([
                'status' => 'success',
                'message' => 'Data successfully added!',
                'failures' => $failures
            ]);
        } catch (\Throwable $th) {
            dd($th);
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'There is a formatting error in your file.'
            ]);
        }
    }
}
