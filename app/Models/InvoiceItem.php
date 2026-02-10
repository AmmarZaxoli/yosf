<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'name',
        'link',
        'quantity',
        'date_order',
        'delivery_date',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }
}
