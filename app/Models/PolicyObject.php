<?php

namespace App\Models;

use App\Models\ObjectModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PolicyObject extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'policy_id',
        'object_id',
    ];

    /**
     * Get the policy that owns the PolicyObject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    /**
     * Get the object that owns the PolicyObject
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function object(): BelongsTo
    {
        return $this->belongsTo(ObjectModel::class);
    }
}
