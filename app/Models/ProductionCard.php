<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionCard extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'client_id',
        'policy_id',
        'card_status_id',
        'parent_card_id',
        'payment_type_id',
        'created_by',
        'premi_percentages',
        'tax_percentages',
        'commision_percentages',
        'object_value',
        'discount',
        'production_value',
        'premi',
        'commision_bruto',
        'commision_netto',
        'tax',
        'insurance_pay',
        'insurance_payment_status_id',
        'insurance_paid_at',
        'insurance_evident_file',
        'insurance_evident_number',
    ];

    protected $casts = [
        'premi_percentages' => 'decimal:2',
        'tax_percentages' => 'decimal:2',
        'commision_percentages' => 'decimal:2',
        'object_value' => 'decimal:2',
        'discount' => 'decimal:2',
        'production_value' => 'decimal:2',
        'premi' => 'decimal:2',
        'commision_bruto' => 'decimal:2',
        'commision_netto' => 'decimal:2',
        'tax' => 'decimal:2',
        'insurance_pay' => 'decimal:2',
        'insurance_paid_at' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function policy(): BelongsTo
    {
        return $this->belongsTo(Policy::class);
    }

    public function cardStatus(): BelongsTo
    {
        return $this->belongsTo(CardStatus::class);
    }

    public function parentCard(): BelongsTo
    {
        return $this->belongsTo(ProductionCard::class, 'parent_card_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function insurancePaymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'insurance_payment_status_id');
    }
}
