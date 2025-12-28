<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'card_id',
        'sequence',
        'created_by',
        'collected_by',
        'collector_type_id',
        'amount',
        'note',
        'client_payment_status_id',
        'deadline_at',
        'paid_at',
        'client_evident_file',
        'client_evident_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deadline_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(ProductionCard::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function collectorType(): BelongsTo
    {
        return $this->belongsTo(CollectorType::class);
    }

    public function clientPaymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'client_payment_status_id');
    }
}
