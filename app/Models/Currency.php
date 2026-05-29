<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = ['code', 'name', 'symbol', 'rate', 'is_active'];

    protected $casts = ['is_active' => 'boolean', 'rate' => 'decimal:6'];
}
