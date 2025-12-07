<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insurance extends Model
{
    protected $fillable = ['name', 'bank_account', 'va_number', 'default_commission_pct'];
}
