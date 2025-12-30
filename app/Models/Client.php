<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'relation_id',
        'id_name',
        'id_number',
        'id_address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function relation()
    {
        return $this->belongsTo(Relation::class);
    }
}
