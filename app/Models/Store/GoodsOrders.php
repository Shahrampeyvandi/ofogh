<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Acounting\OffCodeUse;

class GoodsOrders extends Model
{
    public function images()
    {
        return $this->hasMany(GoodsOrdersImages::class);
    }

    public function goodsordersstatuses()
    {
        return $this->hasOne(GoodsOrdersStatuses::class,'id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function cunsomers()
    {
        return $this->belongsTo('App\Models\Cunsomers\Cunsomer');
    }

    public function offcodeuse()
    {
        return $this->belongsTo(OffCodeUse::class,'off_code');
    }
}
