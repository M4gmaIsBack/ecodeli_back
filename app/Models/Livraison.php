<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livraison extends Model
{
    protected $fillable = [
        'type_livraison',
        'description',
        'poids',
        'taille',
        'adresse_depart',
        'adresse_arrivee',
        'instructions',
        'id_client',
        'id_livreur',
        'prix',
        'status',
    ];
}
