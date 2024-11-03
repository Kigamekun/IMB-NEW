<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMBIndukNonPerumPerusahaan extends Model
{

    protected $table = 'imb_induk_non_perum_perusahaan';


    protected $fillable = [
        'nama',
        'letak_bangunan',
        'jenis_bangunan',
        'luas_bangunan',
        'gsp',
        'gsb',
        'surat_izin_nomer_dan_tanggal',
        'bea_pendirian',
        'bea_pemeriksaan',
        'daftar_leges',
        'jumlah',
        'tanggal',
        'kas_no',
        'keterangan',
        'file'
    ];
    use HasFactory;
}
