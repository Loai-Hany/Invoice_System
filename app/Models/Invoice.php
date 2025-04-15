<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'invoice_date',
        'due_date',
        'product',
        'section_id',
        'discount',
        'rate_vat',
        'value_vat',
        'total',
        'amount_collection',
        'amount_commission',
        'status',
        'value_status',
        'note',
        'payment_date',
        'user'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
