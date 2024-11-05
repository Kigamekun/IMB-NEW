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
        $jenisKegiatanList = DB::table('app_md_jeniskeg')->pluck('id_jeniskeg', 'name_jeniskeg')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $fungsiBangunanList = DB::table('app_md_fungsibang')->pluck('id_fungsibang', 'name_fungsibang')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $jenis_kegiatan_array = [];
        try {
            foreach ($dataRows as $key => $row) {
                if (!is_null($row[1])) {
                    $rowDistrict = strtolower($row[8]);
                    $rowSubdistrict = strtolower($row[9]);
                    $districts = DB::table('master_district')
                        ->where(DB::raw('LOWER(name)'), $rowDistrict)
                        ->pluck('code')
                        ->toArray();
                    if (empty($districts)) {
                        $failures[$key] = [
                            'message' => 'Kecamatan ' . $row[8] . ' tidak ditemukan',
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
                            'message' => 'Desa/Kelurahan ' . $row[9] . ' tidak ditemukan di kecamatan ' . $row[8],
                            'baris' => $key,
                        ];
                        continue;
                    }
                    $rowJenisKegiatan = strtolower($row[10]);
                    $rowFungsiBangunan = strtolower($row[11]);
                    $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
                    $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;
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
                        'kecamatan' => $village->district_code,
                        'desa_kelurahan' => $village->code,
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
                        $rowJenisKegiatan = strtolower($row[10]);
                        $rowFungsiBangunan = strtolower($row[11]);
                        $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
                        $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;
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
