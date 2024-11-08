<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IMBItemNon extends Model
{
    protected $table = 'item_imb_induk_non_perum';
    use HasFactory;

    protected $fillable = [
        'induk_perum_id',
        "jenis_kegiatan",
        "fungsi_bangunan",
        "type",
        "luas_bangunan",
        "jumlah_unit",
        "keterangan",
        "scan_imb"
    ];


    public $timestamps = false;

    public function imbIndukPerum()
    {
        return $this->belongsTo(IMBIndukPerum::class, 'induk_perum_id', 'id');
    }
}
