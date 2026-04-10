<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lending extends Model
{
    protected $fillable = ['name', 'ket', 'is_returned', 'edited_by'];

    public function details()
    {
        return $this->hasMany(LendingDetail::class);
    }
}
