<?php

namespace App\Models;

use App\Models\ObjectModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObjectPart extends Model
{
    protected $primaryKey = ['object_id', 'part_id'];
    
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $fillable = [
        'object_id',
        'part_id',
        'value',
    ];

    public function object(): BelongsTo
    {
        return $this->belongsTo(ObjectModel::class, 'object_id');
    }

    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class, 'part_id');
    }
}
