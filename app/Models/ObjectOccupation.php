<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObjectOccupation extends Model
{
    use HasFactory;
    protected $table = 'object_occupation';
    protected $fillable = ['name'];
    public $timestamps = false;
}
