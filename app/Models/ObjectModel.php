<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObjectModel extends Model
{
    protected $fillable = [
        'object_type_id',
        'name',
        'address',
    ];

    public function objectType(): BelongsTo
    {
        return $this->belongsTo(ObjectType::class);
    }
}
