<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMBPerluasan extends Model
{
    protected $table = 'imb_perluasan';

    protected $fillable = [
        'imb_pecahan',
        'tgl_imb_pecahan',
        'imb_perluasan',
        'tgl_imb_perluasan',
        'no_register',
        'tgl_register',
        'nama',
        'atas_nama',
        'jenis_kegiatan',
        'lokasi_perumahan',
        'kecamatan',
        'desa_kelurahan',
        'type',
        'luas_bangunan_lama',
        'luas_bangunan_perluasan',
        'blok',
        'no_blok',
        'keterangan',
        'scan_imb',
    ];

    public $timestamps = false;
    use HasFactory;
}
