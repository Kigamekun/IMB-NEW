<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMBPecahan extends Model
{
    use HasFactory;

    protected $table = 'imb_pecahan';

    public $timestamps = false;


    protected $fillable = [
        'imb_induk_id',
        'tgl_imb_induk',
        'imb_pecahan',
        'tgl_imb_pecahan',
        'no_register',
        'tgl_register',
        'nama',
        'atas_nama',
        'jenis_kegiatan',
        'fungsi_bangunan',
        'lokasi_perumahan',
        'kecamatan_lama',
        'kelurahan_lama',
        'kecamatan',
        'desa_kelurahan',
        'type',
        'luas',
        'blok',
        'no_blok',
        'keterangan',
        'scan_imb'
    ];


}
