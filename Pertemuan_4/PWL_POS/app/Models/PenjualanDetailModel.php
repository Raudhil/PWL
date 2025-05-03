<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// PenjualanDetailModel.php
class PenjualanDetailModel extends Model
{
    protected $table = 'm_penjualan_detail';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }
}
