<?php

namespace App\Models\City;

use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable= ['city_name','broker_id'];

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
}
