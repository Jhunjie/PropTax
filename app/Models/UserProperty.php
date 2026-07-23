<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'acct_email_address',
        'acct_no',
        'name_of_account',
        'account_code',
        'type',
        'lot_no',
        'brgy_name',
        'lgu',
        'date_of_registration',
        'status',
    ];

    protected $casts = [
        'date_of_registration' => 'date',
        'acct_no' => 'integer',
    ];
    
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'acct_email_address', 'email');
    // }
}