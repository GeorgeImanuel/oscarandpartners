<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyCoverage extends Pivot
{
    protected $table = 'policy_coverages';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'policy_id',
        'coverage_code',
        'percentage',
    ];

    protected $casts = [
        'percentage' => 'decimal:7',
    ];

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function coverage(): BelongsTo
    {
        return $this->belongsTo(Coverage::class, 'coverage_code', 'code');
    }
}
