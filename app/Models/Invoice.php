<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id', // added
        'phone',
        'address',
        'truck_number',
        'today_date',
    ];

    protected $casts = [
        'today_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    
    
}
