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
        'object_value' => 'decimal:2',
        'amount' => 'decimal:2',
        'deadline_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id');
    }

    public function productionCard(): BelongsTo
    {
        return $this->belongsTo(Card::class, 'card_id');
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

        // app/Models/Card.php

    public function paymentType(): BelongsTo
    {
        return $this->belongsTo(PaymentType::class, 'payment_type_id');
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
