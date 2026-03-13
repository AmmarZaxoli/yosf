<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = [
        'invoice_number',
        'user_id',
        'id_truck',
        'total_price',
        'status',
        'is_active',
    ];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'invoice_id');
    }
    
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function infoInvoice()
    {
        return $this->belongsTo(InfoInvoice::class, 'id_truck', 'number_track');
    }

    public function getDriverAttribute()
    {
        return $this->customer ? $this->customer->driver : null;
    }
    
    public function getDeliveryPriceAttribute()
    {
        return $this->customer ? $this->customer->delivery_price : 0;
    }
    
    public function getDiscountAttribute()
    {
        return $this->customer ? $this->customer->discount : 0;
    }
}