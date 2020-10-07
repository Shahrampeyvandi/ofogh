<?php

namespace App\Models;

use App\Models\Cunsomers\Cunsomer;
use App\Models\Services\Service;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable 
{
    use Notifiable , HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // public function Services()
    // {
    //     return $this->hasMany(Service::class,'user_id','id');
    // }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Cunsomer::class);
    }
}
