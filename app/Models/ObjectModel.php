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
        'object_occupation_id',
        'name',
        'keterangan',
    ];

    public function objectOccupation(): BelongsTo
    {
        return $this->belongsTo(ObjectOccupation::class);
    }

    /**
     * Alias for objectOccupation() for backward compatibility
     */
    public function objectType(): BelongsTo
    {
        return $this->objectOccupation();
    }

    public function policies()
    {
        return $this->hasMany(Policy::class, 'object_id');
    }

    public function interests()
    {
        return $this->belongsToMany(Interest::class, 'object_interests', 'object_id', 'interest_id')
            ->withPivot('value');
    }

    /**
     * Alias for backward compatibility - returns objectParts as a hasMany relationship
     */
    public function objectParts()
    {
        return $this->hasMany(ObjectPart::class, 'object_id');
    }

    public function getObjectValue()
    {
        return $this->interests()->sum('value');
    }
}
