<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    protected $fillable = [
        'invoice_number',
        'section',
        'product',
        'status',
        'value_status',
        'invoice_id',
        'user',
        'payment_date',
        'note'
    ];
}
