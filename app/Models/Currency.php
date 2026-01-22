<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    protected $primaryKey = 'code';
    
    public $incrementing = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'code',
        'name',
        'value'
    ];

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class, 'currency_code', 'code');
    }
}
