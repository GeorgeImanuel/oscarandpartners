<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function objects()
    {
        return $this->belongsToMany(ObjectModel::class, 'object_interests', 'interest_id', 'object_id')
            ->withPivot('value');
    }
}
