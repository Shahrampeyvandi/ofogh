<?php

namespace App\Models;

use App\Models\Store\Store;
use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    protected $guarded =[];

    public function store()
    {
        return $this->belongsToMany(Store::class);
    }
}
