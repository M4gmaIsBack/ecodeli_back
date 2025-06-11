<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livreur extends Model
{
    use HasFactory;

    protected $table = 'livreurs';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'nom',
        'prenom',
        'adresse',
        'telephone',
        'statut_validation',
        'date_verification',
        'authentification_2FA',
        'id_utilisateur',
        'iban'
    ];

    public function user()
    {
        return $this->belongsTo(Users::class, 'id_utilisateur');
    }
}
