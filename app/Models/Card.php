<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = [
        'client_id',
        'policy_id',
        'card_status_id',
        'created_by',
        'start_date',
        'end_date'
    ];
}
