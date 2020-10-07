<?php

namespace App\Models\Acounting;

use Illuminate\Database\Eloquent\Model;
use App\Models\Acounting\Transations;
use App\Models\Acounting\CheckoutPersonals;
use App\Models\Personals\Personal;
use App\Models\Cunsomers\Cunsomer;


class UserAcounts extends Model
{
    protected $quarded= [];

    public function transactions()
    {
        return $this->hasMany(Transations::class);
    }

    public function checkoutpersonals()
    {
        return $this->hasMany(CheckoutPersonals::class);
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }

    public function cunsomer()
    {
        return $this->belongsTo(Cunsomer::class);
    }
}
