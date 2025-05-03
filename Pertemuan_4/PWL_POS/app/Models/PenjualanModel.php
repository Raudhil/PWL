<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenjualanModel extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'm_penjualan';

    // Primary key tabel
    protected $primaryKey = 'penjualan_id';

    // Field yang bisa diisi (mass assignment)
    protected $fillable = [
        'user_id',
        'pembeli',
        'penjualan_kode',
        'penjualan_tanggal'
    ];

    // Tipe data casting
    protected $casts = [
        'penjualan_tanggal' => 'datetime'
    ];

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke detail penjualan (jika ada tabel detail)
     */
    // PenjualanModel.php
    public function details()
    {
        return $this->hasMany(PenjualanDetailModel::class, 'penjualan_id', 'penjualan_id');
    }


    /**
     * Generate kode penjualan otomatis
     * Format: PJ001, PJ002, dst
     */
    public static function generateKodePenjualan()
    {
        $latest = self::orderBy('penjualan_id', 'desc')->first();

        if (!$latest) {
            return 'PJ001';
        }

        $lastNumber = (int) substr($latest->penjualan_kode, 2);
        $nextNumber = $lastNumber + 1;

        return 'PJ' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
