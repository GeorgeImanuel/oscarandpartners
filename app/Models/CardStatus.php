<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardStatus extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'color'];
}
