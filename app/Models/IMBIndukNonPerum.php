<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMBIndukNonPerum extends Model
{
    use HasFactory;


    protected $table = 'imb_induk_non_perum';

    protected $fillable = [
        'contoh_jenis',
        'imb_induk_non_perum',
        'tgl_imb_induk_non_perum',
        "no_register",
        "tgl_register",
        "nama",
        "atas_nama",
        "lokasi_perumahan",
        "lokasi",
        'jenis_kegiatan',
        'fungsi_bangunan',
        'luas_bangunan',
        'detail_luas_bangunan',
        'keterangan',
        'scan_imb',
        "kecamatan",
        "desa_kelurahan"
    ];
    public $timestamps = false;


}
