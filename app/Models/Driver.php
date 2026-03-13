<?php
// app/Models/Driver.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'delivery_price',
    ];

    protected $casts = [
        'delivery_price' => 'decimal:2',
    ];
}