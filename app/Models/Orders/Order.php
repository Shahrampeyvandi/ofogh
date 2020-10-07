<?php

namespace App\Models\Orders;

use App\Models\Personals\Personal;
use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded =[];
    
    public function relatedService()
    {
        return $this->hasOne(Service::class,'id','service_id');
    }


    public function personals()
    {
        return $this->belongsToMany(Personal::class);
    }

    public function orderImages()
    {
        return $this->hasMany(ImageOrders::class);
    }

    public function orderDetail()
    {
        return $this->hasOne(OrderStatusDetail::class);
    }

    public function transactions()
    {
        return $this->belongsTo('App\Models\Acounting\Transations');
    }
}
