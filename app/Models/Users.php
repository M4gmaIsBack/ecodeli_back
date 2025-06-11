<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    use HasFactory;

    protected $table = 'users';
    
    protected $primaryKey = 'id';

    public $timestamps = true;
    
    protected $fillable = [
        'email', 'password', 'google2fa_secret', 'authentification_2FA', 'photo_profil'
    ];
    
    public function mfaSecret()
    {
        return $this->hasOne(\App\Models\MFASecurity::class, 'user_id');
    }
    
    public function hasVerifiedMFA()
    {
        return $this->mfaSecret && $this->mfaSecret->is_verified;
    }

    public function administrateur()
    {
        return $this->hasOne(Administrateur::class, 'id_utilisateur');
    }

    public function documentsJustificatifs()
    {
        return $this->hasMany(\App\Models\DocumentJustificatif::class, 'user_id');
    }

    public function forUser($userId)
    {
        $docs = DocumentJustificatif::where('user_id', $userId)->get();
        return response()->json([
        'documents' => $docs->map(fn($d) => [
            'id'         => $d->id,
            'filename'   => $d->filename,
            'chemin'     => $d->chemin,
            'created_at' => $d->created_at,
        ]),
        ]);
    }
public function achievements()
{
    return $this->belongsToMany(\App\Models\Achievement::class, 'user_achievements', 'user_id', 'achievement_id')
                ->withPivot('unlocked_at');
}




}
