<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $table = 'paiements';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'montant',
        'id_utilisateur',
        'id_prestation',
        'id_annonce',
        'status'
    ];
}
