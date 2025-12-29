<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'insurance_id',
        'parent_policy_id',
        'object_type_id',
        'policy_number',
        'object',
        'object_address',
        'object_value'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function insurance()
    {
        return $this->belongsToMany(Insurance::class);
    }

    public function objectType()
    {
        return $this->belongsTo(ObjectType::class);
    }

    public function parentPolicy()
    {
        return $this->belongsTo(Policy::class, 'parent_policy_id');
    }
}
