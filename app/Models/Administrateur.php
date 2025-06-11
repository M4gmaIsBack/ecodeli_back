<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Administrateur extends Model
{
    protected $table = 'administrateurs';

    protected $fillable = ['id_utilisateur'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
}
