<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMBIndukPerum extends Model
{
    use HasFactory;

    protected $table = 'imb_induk_perum';

    protected $fillable = [
        "imb_induk",
        "tgl_imb_induk",
        "no_register",
        "tgl_register",
        "nama",
        "atas_nama",
        "lokasi_perumahan",
        "kecamatan",
        "desa_kelurahan"
    ];
    public $timestamps = false;


    // imbitems
    public function imbItems()
    {
        return $this->hasMany(IMBItem::class, 'induk_perum_id', 'id');
    }
}