<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function policies()
    {
        return $this->hasMany(Policy::class, 'object_id');
    }

    public function objectParts(){
        return $this->hasMany(ObjectPart::class, 'object_id');
    }
}
