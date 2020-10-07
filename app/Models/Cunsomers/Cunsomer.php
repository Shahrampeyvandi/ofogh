<?php

namespace App\Models\Cunsomers;

use App\App\Models\Customers\CustomerAddress;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Acounting\UserAcounts;
use App\Models\Orders\Order;
use App\Models\Services\Service;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Cunsomer extends  Authenticatable  implements JWTSubject
{
    protected $guarded = [];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [
            'first_name'      => $this->customer_firstname,
            'last_name'       => $this->customer_lastname,
            'mobile'           => $this->customer_mobile,
        ];
        }

    public function useracounts()
    {
        return $this->hasMany(UserAcounts::class);
    }

    public function broker()
    {
        return $this->belongsToMany(User::class);
    }

    public function getOrders($customer_id)
    {
       return Order::where('customer_id',$customer_id)->latest()->get();

    }
    public function getOrder($customer_id,$code)
    {
        return Order::where('customer_id',$customer_id)->where('order_unique_code',$code)->first();

    }

    public function getOrderService($customer_id,$code)
    {
        $order = $this->getOrder($customer_id,$code);
        if ($order) {
           
            return $service= Service::where('id',$order->service_id)->first();
        }
       
        
    }
    public function getAddresses($customer_id)
    {
        return CustomerAddress::where('customer_id',$customer_id)->get();
    }

}
