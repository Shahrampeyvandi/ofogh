<?php

namespace App\Models\Acounting;

use Illuminate\Database\Eloquent\Model;

class PersonalBank extends Model
{
    public function personal()
    {
        return $this->belongsTo('App\Models\Personals\Personal');
    }
}
