<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InfoInvoice extends Model
{
    protected $table = 'info_invoices';
    protected $fillable = [
        'number_track',
        'totalbuyprice',
        'status'
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'id_truck', 'number_track');
    }
}
