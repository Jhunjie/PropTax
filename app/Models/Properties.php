<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'lot_no',
        'barangay',
        'rpt_account_no',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function taxes(): HasMany
    {
        return $this->hasMany(PropertyTax::class);
    }
}