<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMBBersyarat extends Model
{
    use HasFactory;

    protected $table = 'imb_bersyarat';

    protected $fillable = [
        'nama',
        'alamat',
        'letak_bangunan',
        'luas_bangunan',
        'jenis_bangunan',
        'ketentuan_jarak_gsp',
        'ketentuan_jarak_gsb',
        'keadaan_jarak_gsp',
        'keadaan_jarak_gsb',
        'surat_izin_nomer',
        'surat_izin_tanggal',
        'jangka_waktu',
        'tanggal',
        'bulan',
        'tahun',
        'keterangan',
        'file'
    ];


}
