<?php

namespace App\Models;

use App\Models\PolicyCoverage;
use App\Models\ObjectCoverage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Coverage extends Model
{
    protected $primaryKey = 'code';
    
    public $incrementing = false;
    
    public $timestamps = false;
    
    protected $keyType = 'string';
    
    protected $fillable = [
        'code',
        'name',
    ];

    /**
     * Get the policyCoverages for the Coverage
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function policyCoverages(): HasMany
    {
        return $this->hasMany(PolicyCoverage::class);
    }

    public function objectCoverages(): HasMany
    {
        return $this->hasMany(ObjectCoverage::class, 'coverage_code', 'code');
    }
}
