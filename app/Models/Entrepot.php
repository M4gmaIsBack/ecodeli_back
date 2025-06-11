<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrepot extends Model
{
    protected $table = 'entrepots';

    protected $fillable = [
        'name',
        'address',
        'max_capacity',
        'current_capacity',
    ];
}
