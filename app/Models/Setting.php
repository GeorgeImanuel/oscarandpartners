<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['commision_percentage', 'tax_percentage'];
    public $timestamps = false;
}
