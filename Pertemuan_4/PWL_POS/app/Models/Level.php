<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $table = 'm_level'; // Nama tabel di database
    protected $primaryKey = 'level_id';
    protected $fillable = ['nama_level', 'deskripsi']; // Kolom yang bisa diisi

    public function users()
    {
        return $this->hasMany(User::class); // Relasi ke User
    }
}

