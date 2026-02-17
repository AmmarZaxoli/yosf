<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number',
        'name',
        'user_id',
        'phone',
        'address',
        'id_truck',
        'status',
        'is_active',
        'today_date',
    ];

    protected $casts = [
        'today_date' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function infoInvoice()
    {
        return $this->belongsTo(InfoInvoice::class, 'id_truck', 'number_track');
    }
}
