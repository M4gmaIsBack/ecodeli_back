<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commercant extends Model
{
    use HasFactory;

    protected $table = 'commercants';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'nom_entreprise',
        'adresse',
        'numero_siret',
        'email_responsable',
        'telephone',
        'id_utilisateur',
        'site_web',
        'image_url',
        'iban'
    ];
}
