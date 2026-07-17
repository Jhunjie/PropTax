<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barangays extends Model
{
    protected $table = 'barangays';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'string';

    protected $fillable = ['id','name','lgu_id'];

}
