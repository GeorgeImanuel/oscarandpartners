<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PolicyInsurance extends Model
{

    protected $primaryKey = ['policy_id', 'insurance_id'];
        
    public $incrementing = false;

    protected $fillable = [
        'policy_id',
        'insurance_id',
        'is_main',
        'percentage',
        'tax',
    ];

    public function policy()
    {
        return $this->belongsTo(Policy::class);
    }
    public function insurance()
    {
        return $this->belongsTo(Insurance::class);
    }
}
