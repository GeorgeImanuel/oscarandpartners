<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ObjectModel extends Model
{
    use HasFactory;

    protected $table = 'objects';

    protected $fillable = [
        'object_occupation_id',
        'name',
        'tjh',
        'keterangan',
    ];

    public function objectOccupation(): BelongsTo
    {
        return $this->belongsTo(ObjectOccupation::class);
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class, 'object_id');
    }

    public function objectInterests()
    {
        return $this->hasMany(ObjectInterest::class, 'object_id');
    }

    public function getObjectValue()
    {
        return $this->objectInterests()->sum('value');
    }
}
