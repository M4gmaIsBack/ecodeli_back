<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 

class ReponseTicket extends Model
{
    protected $table = 'reponses_tickets';

    protected $fillable = [
        'message',
        'ticket_id',
        'utilisateur_id',
        'administrateur_id',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class);
    }

    public function administrateur()
    {
        return $this->belongsTo(Administrateur::class);
    }
}
