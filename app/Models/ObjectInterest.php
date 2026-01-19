<?php

namespace App\Models;

use App\Models\ObjectModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObjectInterest extends Model
{
    protected $primaryKey = ['object_id', 'interest_id'];
    
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $fillable = [
        'object_id',
        'interest_id',
        'part_id', // alias for interest_id (backward compatibility)
        'value',
    ];

    public function object(): BelongsTo
    {
        return $this->belongsTo(ObjectModel::class, 'object_id');
    }

    public function interest(): BelongsTo
    {
        return $this->belongsTo(Interest::class, 'interest_id');
    }
}
