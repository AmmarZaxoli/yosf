<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemImage extends Model
{
    protected $fillable = [
        'invoice_item_id',
        'image_path',
    ];

    public function item()
    {
        return $this->belongsTo(InvoiceItem::class, 'invoice_item_id');
    }
}
