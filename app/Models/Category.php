<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'nama',
        'division',
        'pj'
    ];

    public function items()
    {
        return $this->hasMany(Item::class, 'kategori_id');
    }
}