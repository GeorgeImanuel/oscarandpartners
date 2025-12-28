<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coverage extends Model
{
    protected $primaryKey = 'code';
    
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'code',
        'name',
    ];
}
