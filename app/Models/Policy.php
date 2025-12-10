<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $fillable = [
        'client_id',
        'insurance_id',
        'parent_policy_id',
        'object_type_id',
        'policy_number',
        'object',
        'object_address',
        'object_value'
    ];
}
