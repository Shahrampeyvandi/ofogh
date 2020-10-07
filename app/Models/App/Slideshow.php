<?php

namespace App\Models\App;

use Illuminate\Database\Eloquent\Model;

class Slideshow extends Model
{
    protected $fillable= [
    	'title','image','status','default','place','release','expiry'
    ];
}
