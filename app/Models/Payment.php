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
        'amount',
        'note',
        // CLIENT PAYMENT
        'payment_status_id',
        'deadline_at',
        'paid_at',
        'client_evident_file',
        'client_evident_number',
        // INSURANCE PAYMENT
        'insurance_payment_status_id',
        'insurance_paid_at',
        'insurance_evident_file',
        'insurance_evident_number',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'deadline_at' => 'datetime',
        'paid_at' => 'datetime',
        'insurance_paid_at' => 'datetime',
    ];

    public function paymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function insurancePaymentStatus(): BelongsTo
    {
        return $this->belongsTo(PaymentStatus::class, 'insurance_payment_status_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Accessor untuk mendapatkan Nama Tertanggung
     * Prioritas: Client ID Name -> User Name -> '-'
     */
    public function getInsuredNameAttribute(): string
    {
        // Cek relasi card dan client untuk menghindari error on null
        if (!$this->card || !$this->card->client) {
            return '-';
        }

        // 1. Cek id_name (Nama sesuai KTP/Identitas)
        if (!empty($this->card->client->id_name)) {
            return $this->card->client->id_name;
        }

        // 2. Cek nama user (Login) jika id_name kosong
        return $this->card->client->user?->name ?? '-';
    }
}
