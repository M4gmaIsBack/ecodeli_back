<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnoncePrestataire extends Model
{
    protected $table = 'annonces_prestataires';

    protected $fillable = [
        'title', 'description', 'price', 'date', 'location', 'status', 'id_prestataire', 'id_client', 'id_prestation'
    ];

    public function livreur() {
        return $this->belongsTo(Prestataire::class, 'id_prestataire');
    }
    
    public function client() {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function prestation() {
        return $this->belongsTo(Prestation::class, 'id_prestation');
    }
}
