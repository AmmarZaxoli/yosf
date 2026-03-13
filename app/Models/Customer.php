<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'delivery_price',
        'driver_id',
        'date_order',       
        'delivery_date',    
        'discount',
        'blocked',
        'invoice_id',
    ];

    protected $casts = [
        'blocked' => 'boolean',
        'date_order' => 'date',    
        'delivery_date' => 'date',   
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
