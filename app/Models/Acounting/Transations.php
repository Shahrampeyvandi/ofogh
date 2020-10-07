<?php

namespace App\Models\Acounting;

use Illuminate\Database\Eloquent\Model;
use App\Models\Acounting\UserAcounts;

class Transations extends Model
{
    public function user_acounts()
    {
        return $this->belongsTo(UserAcounts::class);
    }

    public function orders()
    {
        return $this->belongsTo('App\Models\Orders\Orders');
    }
}
