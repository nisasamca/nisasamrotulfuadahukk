<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LendingDetail extends Model
{
    protected $fillable = ['lending_id', 'item_name', 'total'];
}
