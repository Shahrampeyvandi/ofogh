<?php

namespace App\Http\Controllers\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store\Store;
use App\Models\Store\GoodsOrders;

class GoodsOrdersController extends Controller
{
    public function index(){

        $goodsorders=GoodsOrders::all();


        //dd($goodsorders);

        return view('User.Stores.GoodsOrders',compact('goodsorders'));
    }
}
