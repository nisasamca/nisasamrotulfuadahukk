<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lending extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'ket', 'tanggal_pinjam', 'tanggal_kembali', 'is_returned', 'kondisi_kembali', 'edited_by'];

    public function details()
    {
        return $this->hasMany(LendingDetail::class);
    }
}
