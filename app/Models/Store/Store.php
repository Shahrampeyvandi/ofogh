<?php

namespace App\Models\Store;

use App\Models\Neighborhood;
use App\Models\Personals\Personal;
use Illuminate\Database\Eloquent\Model;
use App\Models\City\City;

class Store extends Model
{
    protected $guarded =[];

    protected $casts = ['store_neighborhoods' => 'array'];

    public function products()
    {
        return $this->hasMany(Product::class);

    }
    public function neighborhoods()
    {
        return $this->belongsToMany(Neighborhood::class);
    }

    public function goodsorders()
    {
        return $this->hasMany(GoodsOrders::class);
    }
   
    public function workinghours()
    {
        return $this->hasOne(StoreWorkingHours::class,'id');
    }

    public function personal()
    {
        return $this->belongsTo(Personal::class,'owner_id');
    }

    public function city()
    {
        return $this->belongsTo(City::class,'store_city');
    }
}
