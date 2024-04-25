<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPassword extends Model
{
    protected $fillable = [
        'phone_number',
        'code',
        'expire_at',
    ];
    protected $casts = [
        'expire_at' => 'datetime',
    ];
}
