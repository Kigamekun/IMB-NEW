<?php

namespace App\Imports;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

use Illuminate\Support\Facades\DB;
use App\Models\IMBPecahan;

class ImportIMBPecahan implements ToCollection, WithHeadingRow, WithCustomCsvSettings
{
    /**
     * Customize CSV settings.
     *
     * @return array
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ',', // Adjust delimiter if necessary
        ];
    }

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        ini_set('max_execution_time', 0); // Unlimited execution time
        ini_set('memory_limit', '-1');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $failures = [];
        $jenisKegiatanList = DB::table('app_md_jeniskeg')->pluck('id_jeniskeg', 'name_jeniskeg')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);
        $fungsiBangunanList = DB::table('app_md_fungsibang')->pluck('id_fungsibang', 'name_fungsibang')->mapWithKeys(fn($item, $key) => [strtolower($key) => $item]);

        try {
            foreach ($rows as $key => $row) {
                $rowJenisKegiatan = strtolower($row['jenis_kegiatan']);
                $rowFungsiBangunan = strtolower($row['fungsi_bangunan']);
                $jenis_kegiatan = $jenisKegiatanList[$rowJenisKegiatan] ?? null;
                $fungsi_bangunan = $fungsiBangunanList[$rowFungsiBangunan] ?? null;

                if (!is_null($row['imb_induk_id'])) {
                    $rowDistrict = strtolower($row['kecamatan']);
                    $rowSubdistrict = strtolower($row['desa_kelurahan']);
                    $districts = DB::table('master_district')
                        ->where(DB::raw('LOWER(name)'), $rowDistrict)
                        ->pluck('code')
                        ->toArray();

                    if (empty($districts)) {
                        $failures[$key] = [
                            'message' => 'Kecamatan ' . $row['kecamatan'] . ' tidak ditemukan',
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
                            'message' => 'Desa/Kelurahan ' . $row['desa_kelurahan'] . ' tidak ditemukan di kecamatan ' . $row['kecamatan'],
                            'baris' => $key,
                        ];
                        continue;
                    }


                    IMBPecahan::create([
                        'imb_induk_id' => $row['imb_induk_id'],
                        'tgl_imb_induk' => null,
                        'imb_pecahan' => $row['imb_pecahan'],
                        'tgl_imb_pecahan' => null,
                        'no_register' => $row['no_register'],
                        'tgl_register' => null,
                        'nama' => $row['nama'],
                        'atas_nama' => $row['atas_nama'],
                        'jenis_kegiatan' => $jenis_kegiatan,
                        'fungsi_bangunan' => $fungsi_bangunan,
                        'lokasi_perumahan' => $row['lokasi_perumahan'],
                        'kecamatan' => $village->district_code,
                        'desa_kelurahan' => $village->code,
                        'type' => $row['type'],
                        'luas' => $row['luas'],
                        'blok' => $row['blok'],
                        'no_blok' => $row['no_blok'],
                        'keterangan' => $row['keterangan'],
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
