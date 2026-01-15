<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Policy extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_number',
        'object_id',
        'coverage_id',
        'coverage_percentage',
        'started_date',
        'ended_date',
    ];

    protected $casts = [
        'started_date' => 'date',
        'ended_date' => 'date',
        'coverage_percentage' => 'decimal:7',
    ];

    protected function coveragePercentage(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => is_numeric($value) ? (float) $value : null,
        );
    }

    public function productionCard(): HasOne
    {
        return $this->hasOne(Card::class);
    }

    public function object(): BelongsTo
    {
        return $this->belongsTo(ObjectModel::class);
    }

    public function coverage(): BelongsTo
    {
        return $this->belongsTo(Coverage::class, 'coverage_code', 'code');
    }

    public function policyInsurances(): HasMany
    {
        return $this->hasMany(PolicyInsurance::class);
    }

    public function insurances(): BelongsToMany
    {
        return $this->belongsToMany(Insurance::class, 'policy_insurances')
            ->withPivot('is_main', 'percentage')
            ->withTimestamps();
    }

    public function mainInsurance(): BelongsToMany
    {
        return $this->belongsToMany(Insurance::class, 'policy_insurances')
            ->wherePivot('is_main', true)
            ->withPivot('is_main', 'percentage')
            ->withTimestamps();
    }

    public function insurance(): BelongsToMany
    {
        return $this->mainInsurance();
    }
}
