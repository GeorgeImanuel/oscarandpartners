<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ObjectCoverage extends Model
{
    protected $table = 'object_coverages';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'object_id',
        'coverage_code',
        'percentage',
    ];

    protected $casts = [
        'percentage' => 'decimal:7',
    ];

    public function object(): BelongsTo
    {
        return $this->belongsTo(ObjectModel::class, 'object_id');
    }

    public function coverage(): BelongsTo
    {
        return $this->belongsTo(Coverage::class, 'coverage_code', 'code');
    }
}
