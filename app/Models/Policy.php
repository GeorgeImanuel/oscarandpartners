<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Policy extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'policy_number',
        'object_id',
        'currency_code',
        'started_date',
        'ended_date',
        'coverage_percentages',
    ];

    protected $casts = [
        'started_date' => 'date',
        'ended_date' => 'date',
    ];

    public function productionCard(): HasOne
    {
        return $this->hasOne(Card::class);
    }

    public function object(): BelongsTo
    {
        return $this->belongsTo(ObjectModel::class);
    }

    public function coverages(): BelongsToMany
    {
        return $this->belongsToMany(Coverage::class, 'policy_coverages', 'policy_id', 'coverage_id')
            ->withPivot('percentage')
            ->withTimestamps()
            ->using(PolicyCoverage::class);
    }

    public function policyCoverages(): HasMany
    {
        return $this->hasMany(PolicyCoverage::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
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
