<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LendingDetail extends Model
{
    use HasFactory;

    protected $fillable = ['lending_id', 'item_name', 'total', 'kondisi_kembali'];

    protected $casts = [
        'kondisi_kembali' => 'array',
    ];

    public function lending()
    {
        return $this->belongsTo(Lending::class);
    }
}
