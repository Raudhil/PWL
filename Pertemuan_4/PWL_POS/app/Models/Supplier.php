<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'm_supplier'; // Nama tabel harus sesuai dengan database

    protected $primaryKey = 'supplier_id'; // Primary key sesuai tabel

    public $timestamps = false; // Karena created_at dan updated_at NULL

    protected $fillable = ['supplier_kode', 'supplier_nama', 'supplier_alamat'];
}
