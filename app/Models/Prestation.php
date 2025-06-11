<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prestation extends Model
{
    protected $fillable = [
        'title', 'description', 'price', 'location', 'status', 'id_prestataire'
    ];

    public function commercant() {
        return $this->belongsTo(Prestataire::class, 'id_prestataire');
    }
    
    public function client() {
        return $this->belongsTo(Client::class, 'id_client');
    }
}
