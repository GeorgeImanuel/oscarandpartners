<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'created_by',
        'collected_by',
        'collector_type_id',
        'started_date',
        'ended_date',
        'commision_precentage',
        'tax_precentage',
        'premium_gross',
        'discount',
        'admin_fee',
        'commission_pct',
        'commission_amount',
        'net_to_insurance',
        'premi_amount',
        'polis_amount',
        'client_total',
        'commision_ammount',
        'tax_ammount',
        'discount_amount',
        'commision_total',
        'note',
        'client_payment_status_id',
        'client_paid_at',
        'client_evident_file',
        'client_evident_number',
        'insurance_payment_status_id',
        'insurance_paid_at',
        'insurance_evident_file',
        'insurance_evident_number',
    ];
}
