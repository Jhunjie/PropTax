<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_tax_id',
        'user_id',
        'reference_no',
        'amount',
        'method',
        'status',
        'paid_at',
        'receipt_path',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function propertyTax(): BelongsTo
    {
        return $this->belongsTo(PropertyTax::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}