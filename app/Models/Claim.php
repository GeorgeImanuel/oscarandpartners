<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'policy_id',
        'incident_date',
        'insurance_report_date',
        'chronology',
        'docs',
        'casco',
        'tjh',
        'note',
        'payment_date',
        'amount',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'insurance_report_date' => 'date',
        'payment_date' => 'date',
        'casco' => 'integer',
        'tjh' => 'integer',
        'amount' => 'integer',
    ];

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }
}
