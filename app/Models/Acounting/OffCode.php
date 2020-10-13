<?php

namespace App\Models\Acounting;

use Illuminate\Database\Eloquent\Model;

class OffCode extends Model
{
    public function uses()
    {
        return $this->hasMany(OffCodeUse::class);
    }
}
