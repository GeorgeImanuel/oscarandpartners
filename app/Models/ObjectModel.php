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

    public function objectParts(): HasMany
    {
        return $this->hasMany(ObjectPart::class, 'object_id');
    }

    public function parts()
    {
        return $this->belongsToMany(Part::class, 'object_parts', 'object_id', 'part_code', 'id', 'code')
            ->withPivot('value');
    }
}
