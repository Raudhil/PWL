<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokModel extends Model
{
    use HasFactory;

    protected $table = 'm_stok';
    protected $primaryKey = 'stok_id';

    protected $fillable = [
        'supplier_id',
        'barang_id',
        'user_id',
        'stok_jumlah',
        'stok_tanggal'
    ];

    protected $casts = [
        'stok_tanggal' => 'datetime:Y-m-d H:i:s', // Format eksplisit
    ];

    /**
     * Relasi ke model Supplier
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }

    /**
     * Relasi ke model Barang
     */
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'barang_id');
    }

    /**
     * Relasi ke model User
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }

    /**
     * Scope untuk filtering data
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['supplier_id'] ?? false, function ($query, $supplier_id) {
            return $query->where('supplier_id', $supplier_id);
        });

        $query->when($filters['barang_id'] ?? false, function ($query, $barang_id) {
            return $query->where('barang_id', $barang_id);
        });

        $query->when($filters['user_id'] ?? false, function ($query, $user_id) {
            return $query->where('user_id', $user_id);
        });
    }
}
