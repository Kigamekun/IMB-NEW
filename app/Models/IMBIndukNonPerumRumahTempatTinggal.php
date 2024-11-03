<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMBIndukNonPerumRumahTempatTinggal extends Model
{
    use HasFactory;

    protected $table = 'imb_induk_non_perum_rumah_tempat_tinggal';


    protected $fillable = [
        'nama',
        'letak_bangunan',
        'jenis_bangunan',
        'luas_bangunan',
        'gsp',
        'gsb',
        'surat_izin_nomer',
        'surat_izin_tanggal',
        'bea_pendirian',
        'bea_pemeriksaan',
        'daftar_leges',
        'jumlah',
        'tanggal',
        'kas_no',
        'keterangan',
        'file'
    ];
}
