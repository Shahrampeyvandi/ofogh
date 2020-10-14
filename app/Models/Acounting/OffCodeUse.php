<?php

namespace App\Models\Acounting;

use Illuminate\Database\Eloquent\Model;
use App\Models\Orders\Order;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Store\GoodsOrders;

class OffCodeUse extends Model
{
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function goodsorder()
    {
        return $this->belongsTo(GoodsOrders::class,'goods_order_id');
    }

    public function offcode()
    {
        return $this->belongsTo(OffCode::class,'off_code_id');
    }

    public function cunsomer()
    {
        return $this->belongsTo(Cunsomer::class);
    }
}
