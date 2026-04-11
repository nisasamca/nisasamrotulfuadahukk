<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = ['nama', 'kategori_id', 'kondisi', 'lokasi', 'total_item', 'jumlah_repair'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    public function getLendingAttribute()
    {
        return \App\Models\LendingDetail::where('item_name', $this->nama)
            ->whereHas('lending', function($q) {
                $q->where('is_returned', false);
            })->sum('total');
    }

    // Baik = Total - Repair
    public function getBaikAttribute()
    {
        return $this->total_item - $this->jumlah_repair;
    }

    // Rusak = jumlah_repair saat ini
    public function getRusakAttribute()
    {
        return $this->jumlah_repair;
    }

    // Available = Baik - yang sedang dipinjam
    public function getAvailableAttribute()
    {
        return $this->baik - $this->lending;
    }
}