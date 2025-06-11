<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MFASecurity extends Model {
    protected $table = 'mfa_secrets';

    protected $fillable = ['user_id', 'secret', 'is_verified'];

    public function user() {
        return $this->belongsTo(Users::class, 'user_id');
    }
}

