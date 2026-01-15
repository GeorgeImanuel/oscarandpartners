<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Card extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'client_id',
        'policy_id',
        'card_type_id',
        'card_status_id',
        'parent_card_id',
        'payment_type_id',
        'created_by',
        'premi_percentages',
        'commision_percentages',
        'object_value',
        'policy_fee',
        'discount',
        'production_value',
        'premi_value',
        'commision_bruto',
        'commision_netto',
        'tax',
        'disc_agent',
        'client_pay',
        'insurance_pay',
    ];

    protected $casts = [
        'premi_percentages' => 'decimal:7',
        'commision_percentages' => 'decimal:7',
        'object_value' => 'decimal:7',
        'policy_fee' => 'decimal:7',
        'discount' => 'decimal:7',
        'production_value' => 'decimal:7',
        'premi_value' => 'decimal:7',
        'commision_bruto' => 'decimal:7',
        'commision_netto' => 'decimal:7',
        'tax' => 'decimal:7',
        'disc_agent' => 'integer',
        'client_pay' => 'decimal:7',
        'insurance_pay' => 'decimal:7',
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

    public function cardType(): BelongsTo
    {
        return $this->belongsTo(CardType::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'parent_card_id');
    }

    public function child(): HasOne
    {
        return $this->HasOne(Card::class, 'parent_card_id');
    }

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all of the payments for the Card
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the insurance payment status from the latest payment
     * This is a helper method to maintain backward compatibility
     */
    public function getInsurancePaymentStatusAttribute()
    {
        $latestPayment = $this->payments()->with('insurancePaymentStatus')->latest()->first();
        return $latestPayment ? $latestPayment->insurancePaymentStatus : null;
    }
}
