<?php

namespace App\Models\Services;

use App\Models\City\City;
use App\Models\Personals\Personal;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded =[];
    protected $casts = [
        'service_city' => 'array'
    ];

    public function relationCategory()
    {
        return $this->belongsTo(ServiceCategory::class,'service_category_id','id');
    }

    public function relatedBroker()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function personal()
    {
        return $this->belongsToMany(Personal::class)->withPivot(['personal_chosen_status','personal_confirmed_services']);
    }

    public function user()
    {
        return $this->belongsToMany(User::class);
    }
    public function cities()
    {
        return $this->belongsToMany(City::class);
    }
}
