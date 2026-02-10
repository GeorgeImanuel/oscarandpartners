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
        'currency_code',
        'started_date',
        'ended_date',
    ];

    protected $casts = [
        'started_date' => 'date',
        'ended_date' => 'date',
    ];

    public function productionCard(): HasOne
    {
        return $this->hasOne(Card::class);
    }

    public function policyObjects(): HasMany
    {
        return $this->hasMany(PolicyObject::class);
    }

    public function objects(): BelongsToMany
    {
        return $this->belongsToMany(
            ObjectModel::class, 
            'policy_objects', 
            'policy_id',
            'object_id'       
        );
    }

    public function coverages(): BelongsToMany
    {
        return $this->belongsToMany(Coverage::class, 'policy_coverages', 'policy_id', 'coverage_code')
            ->withPivot('percentage')
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
            ->withPivot('is_main', 'percentage');
    }

    public function mainInsurance(): BelongsToMany
    {
        return $this->belongsToMany(Insurance::class, 'policy_insurances')
            ->wherePivot('is_main', true)
            ->withPivot('is_main', 'percentage');
    }

    public function insurance(): BelongsToMany
    {
        return $this->mainInsurance();
    }

    // Accessor untuk backward compatibility dengan code lama yang pakai ->object
    // Mengembalikan object pertama dari list
    public function getObjectAttribute()
    {
        if (!$this->relationLoaded('objects')) {
            $this->load('objects.objectOccupation', 'objects.objectInterests.interest');
        }
        
        $firstObject = $this->objects->first();
        
        // Jika ada object, tambahkan property tjh (total harga pertanggungan)
        if ($firstObject) {
            $firstObject->tjh = $this->getTotalObjectValue();
        }
        
        return $firstObject;
    }

    // Method untuk get total nilai semua objects di policy ini
    public function getTotalObjectValue()
    {
        if (!$this->relationLoaded('objects')) {
            $this->load('objects.objectInterests');
        }
        
        $total = 0;
        foreach ($this->objects as $object) {
            $total += $object->objectInterests->sum('value');
        }
        
        return $total;
    }

    // Helper untuk get nama semua objects (comma separated)
    public function getObjectNamesAttribute()
    {
        if (!$this->relationLoaded('objects')) {
            $this->load('objects');
        }
        
        return $this->objects->pluck('name')->join(', ');
    }
}
