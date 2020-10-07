<?php

namespace App\Models\Personals;

use App\Models\User;
use App\Models\Store\Store;
use App\Models\Orders\Order;
use App\Models\Services\Service;
use App\Models\Personals\PersonalsPosition;
use App\Models\Acounting\UserAcounts;
use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Personal  extends  Authenticatable  implements JWTSubject
{
    protected $guarded = [];
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return [
            'first_name'      => $this->personal_firstname,
            'last_name'       => $this->personal_lastname,
            'mobile'           => $this->personal_mobile,
        ];
    }


    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot(['personal_chosen_status','personal_confirmed_services']);
    }

    public function order()
    {
        return $this->belongsToMany(Order::class);
    }
	
	  public function positions()
    {
        return $this->hasMany(PersonalsPosition::class);
    }

    public function useracounts()
    {
        return $this->hasMany(UserAcounts::class);
    }

    public function store()
    {
        return $this->hasOne(Store::class,'owner_id');
    }

    public function scopeGetBroker()
    {
        if($this->services){
             return $role =  $this->services->first()->service_role;
             return $broker = User::whereHas('roles',function($q) use($role){
                $q->where('name',$role);
            })->get();
            if(!is_null($broker)){
               return $broker_name = $broker->user_username;
            }else{
               return $broker_name = 'کارگزاری یافت نشد!';
            }
            

        }
        
    }

    public function bank()
    {
        return $this->hasOne('App\Models\Acounting\PersonalBank');
    }
}
