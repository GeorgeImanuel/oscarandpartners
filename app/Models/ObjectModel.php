<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ObjectModel extends Model
{
    use HasFactory;
    
    protected $table = 'objects';

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
