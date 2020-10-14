<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\OffCodeController;
use App\Models\Personals\Personal;
use App\Models\Store\Store;
use App\Models\Store\GoodsOrders;
use App\Models\Store\GoodsOrdersImages;
use App\Models\Store\GoodsOrdersStatuses;
use App\Models\Store\StoreWorkingHours;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Acounting\OffCode;
use App\Models\Acounting\OffCodeUse;
use App\Models\Store\Product;
use App\Models\Acounting\Transations;
use Carbon\Carbon;
use App\Models\Acounting\UserAcounts;
use App\App\Models\Customers\CustomerAddress;
use Tymon\JWTAuth\Facades\JWTAuth;

class GoodsOrderController extends Controller
{
    public function workinghours(Request $request)
    {
        $store=Store::find($request->id);

        $wrkhors=$store->workinghours;
        
        $wrkhors['saturday']=explode(',', $wrkhors['saturday']);
        $wrkhors['sunday']=explode(',', $wrkhors['sunday']);
        $wrkhors['monday']=explode(',', $wrkhors['monday']);
        $wrkhors['tuesday']=explode(',', $wrkhors['tuesday']);
        $wrkhors['wednesday']=explode(',', $wrkhors['wednesday']);
        $wrkhors['thursday']=explode(',', $wrkhors['thursday']);
        $wrkhors['friday']=explode(',', $wrkhors['friday']);


        return response()->json(
            $wrkhors,
            200
        );
    }

    public function order(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

       

        $store=Store::find($request->store_id);

        $order=new GoodsOrders;

        $order->store_id=$store->id;

        $order->personal_mobile	=Personal::find($store->owner_id)->personal_mobile;

        $order->cunsomers_id =$customer->id;

        $order->cunsomer_mobile=$customer->customer_mobile;
 



        $order->items=implode(',', $request->items);
        //$order->items=str_replace(['[',']'],'',$request->items);

        $order->counts=implode(',', $request->counts);
        //$order->counts=str_replace(['[',']'],'',$request->counts);

        $items=$request->items;
        $counts=$request->counts;

  

        $total=0;
        $prices=[];
        for ($i=0;$i<count($items);$i++) {
            $product=Product::find($items[$i]);

            $total+=$product->product_price*$counts[$i];

            $prices[]=$product->product_price;
        }

    
        if (strlen($request->off_code)>1) {
            $offcodeverify=new OffCodeController();
            $amountoff=$offcodeverify->checkoffcode($request->off_code, $customer->id, 'کالا', $store->id, $total, true);

            if (strlen($amountoff)>20) {
                return $amountoff;
            }
            $offcode=OffCode::where('code', $request->off_code)
        ->where(function ($query) {
            $query->where('expiration', '>=', date("Y-m-d"))
            ->orWhere('expiration', null);
        })
        ->first();

            $offcodeuse=new OffCodeUse;
            $offcodeuse->off_code_id=$offcode->id;
            $offcodeuse->cunsomer_id=$customer->id;
            $offcodeuse->amount=$amountoff;
            $offcodeuse->save();

            $order->off_amount=$amountoff;
            $order->off_code=$offcodeuse->id;
        }

        $order->totalamountitems=$total;
        $order->prices=implode(',', $prices);
  
    
        $order->packingprice=$store->packing_price;
    
        $order->sendingprice=$store->sending_price;

        $address=CustomerAddress::find($request->address_id);

        $order->address=$address->address;
    
        $order->address_id=$address->id;

        $datenow=Carbon::today();

        if ($request->deliverdate=='0') {
        } elseif ($request->deliverdate=='1') {
            $datenow=$datenow->addDays(1);
        } elseif ($request->deliverdate=='2') {
            $datenow=$datenow->addDays(2);
        }
    
        $order->deliverdate=$datenow;
    
        $order->delivertime=$request->delivertime;
    
        $order->description=$request->description;
    
        $order->delivercode=rand(10000, 99999);


        $order->questions=implode(',', $request->questions);
    
        $order->answers	=implode(',', $request->answers);

 

      
        // return response()->json(['items'=>$items],200);


        $order->save();

       

        $date = Carbon::parse($order->deliverdate)->timestamp;
        $Code = $this->generateRandomString($order->cunsomer_mobile, $date, $order->id);
     
        $order->orderuniquecode=$Code;

        $order->update();

        if (strlen($request->off_code)>1) {
            $offcodeuse->goods_order_id=$order->id;

            $offcodeuse->update();
        }

        $order->items=[];
        $order->counts=[];
        $order->questions=[];
        $order->answers=[];


        $this->sendsms($order->personal_mobile, 'newgoodsorder', $order->personal_mobile, null, null);
        $this->sendnotification($order->store->personal->firebase_token, 'یک سفارش کالای جدید ثبت شد!', 'وارد چهارسو شوید');


        return response()->json([
            'code'=>200,
            'data'=>$order->orderuniquecode
        ], 200);
    }

    public function uploadpic(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');

        $personal = Personal::where('personal_mobile', $mobile)->first();
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

      
     

        if (is_null($personal) && is_null($customer)) {
            return response()->json(
                '',
                403
            );
        }


        $order=GoodsOrders::where('orderuniquecode', $request->code)->first();

        
        $file = $request->type .'_' .time(). '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('/uploads/goodsorders/' . $order->id), $file);
        $image_url = 'goodsorders/'.$order->id . '/' . $file;
        
       

        $image=new GoodsOrdersImages;
        $image->goods_orders_id=$order->id;
        $image->type=$request->type;
        $image->link=$image_url;
        $image->save();


        return response()->json(
            'ok',
            200
        );
    }

    public function getgoodsordercustomer(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();


        $order=GoodsOrders::where('orderuniquecode', $request->code)->first();
  


        $items=explode(',', $order->items);
        if ($order->prices) {
            $prices=explode(',', $order->prices);
        } else {
            $prices=null;
        }
        $products=[];
        foreach ($items as $key=>$item) {
            $product=Product::find($item);
            if ($prices) {
                $product->product_price=$prices[$key];
            }

            $products[]=$product;
        }
        $order['customer_mobile']=$order->store->tell;
        $order['tell']=$order->store->tell;
        $order['customer_name']=$order->store->store_name;

        $order['products']=$products;
        $order->items=explode(',', $order->items);
        $order->counts=explode(',', $order->counts);
        $order['images']=$order->images;
        $status=GoodsOrdersStatuses::find($order->id);

        $order['accept_time']=$status->accept_time??'';
        $order['preparation_time']=$status->preparation_time??'';
        $order['send_time']=$status->send_time??'';
        $order['deliver_time']=$status->deliver_time??'';
        $order['cancel_time']=$status->cancel_time??'';

        //--این بخش برای اپ های نسخه قدیمی است-- تاریخ 1.3.99
        $order['payedprice']=$order->creditamount+$order->cashamount;
        //------

        $order->questions=[];
        $order->answers=[];

        $order['charge']=$customer->useracounts[0]->cash;

        if (strlen($order->off_code)>0) {
            if ($order->off_amount==0) {
                $order['offreason']='کد تخفیف به دلیل عدم بهره مندی از شرایط اعمال نشد';
            }
        }


        return response()->json(
            $order,
            200
        );
    }

    
    public function getgoodsorderpersonal(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $order=GoodsOrders::where('orderuniquecode', $request->code)->first();

        $order['delivercode']=00000;
        $order['images']=$order->images;

        $items=explode(',', $order->items);
        if ($order->prices) {
            $prices=explode(',', $order->prices);
        } else {
            $prices=null;
        }

        $products=[];
        foreach ($items as $key=>$item) {
            $product=Product::find($item);
            if ($prices) {
                $product->product_price=$prices[$key];
            }
            $products[]=$product;
        }

        $order->items=$items;
        $order->counts=explode(',', $order->counts);
        $order->questions=[];
        $order->answers=[];


        $order->creditamount+=$order->off_amount;

        $order['customer_mobile']=$order->cunsomers->customer_mobile;
        $order['customer_name']=$order->cunsomers->customer_firstname.' '.$order->cunsomers->customer_lastname;

        $order['products']=$products;
        //---------------------
        if ($order->status=='تایید فروشنده') {
            $customer = Cunsomer::find($order->cunsomers_id);
            $useracount_Worker=$personal->useracounts[1];
            $useracount_Customer=$customer->useracounts[0];
            //$useracount_Customer->cash+=$goodsorder->off_amount;
            $useracountcustomerwithoff= $useracount_Customer->cash+$order->off_amount;
            if ($order->sendingprice<1) {
                $sendingprice=0;
            } else {
                $sendingprice=$order->sendingprice;
            }
            $cost=$order->totalamountitems+$order->packingprice+$sendingprice;
    
            if ($useracountcustomerwithoff>=$cost) {
                $order->paytype='اعتباری';
            } else {
                if ($order->off_amount>0) {
                    if ($order->offcodeuse->offcode->dolaw==4) {
                        $useracountcustomerwithoff-=$order->off_amount;
                        $order->off_amount=0;
                        $offcodeuse=$order->offcodeuse;
                        $offcodeuse->success=0;
                        $offcodeuse->update();
                    }
                }
            
                if ($useracountcustomerwithoff>0) {
                    $order->paytype='نقدی-اعتباری';
                } else {
                    $order->paytype='نقدی';
                }
            }
        }

        return response()->json(
            $order,
            200
        );
    }

    
    public function getallorderscustomer(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $orders=GoodsOrders::where('cunsomers_id', $customer->id)
            ->where(function ($query) {
                $query->where('status', 'معلق')
                ->orWhere('status', 'تایید فروشنده')
                ->orWhere('status', 'در حال آماده سازی')
                ->orWhere('status', 'ارسال شده');
            })
            ->get();

        // foreach($orders as $order){

        //     if($order->status=='لغو شده'){

        //         continue;
        //     }
        //     if($order->status=='تحویل شده'){

        //         continue;
        //     }

        //     $ordersall[]=$order;

        // }
        


        foreach ($orders as $order) {

            //$order['images']=$order->images;
            //$order['statuseses']=$order->goodsordersstatuses;
           
            $order->items=[];
            $order->counts=[];
            $order->questions=[];
            $order->answers=[];
            $order['store_name']=$order->store->store_name;
        }


        return response()->json([
            'data'=>$orders
        ], 200);
    }

    public function getallorderspersonal(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $store=Store::where('owner_id', $personal->id)->first();

        $orders=GoodsOrders::where('store_id', $store->id)
        ->where(function ($query) {
            $query->where('status', 'معلق')
            ->orWhere('status', 'تایید فروشنده')
            ->orWhere('status', 'در حال آماده سازی')
            ->orWhere('status', 'ارسال شده');
        })
        ->get();

        // foreach($orders as $order){

        //     if($order->status=='لغو شده'){

        //         continue;
        //     }
        //     if($order->status=='تحویل شده'){

        //         continue;
        //     }

        //     $ordersall[]=$order;

        // }


        foreach ($orders as $order) {

            //$order['images']=$order->images;
            //$order['statuseses']=$order->statuses;
            $order['customer_mobile']=$order->cunsomers->customer_mobile;
            $order['customer_name']=$order->cunsomers->customer_firstname.' '.$order->cunsomers->customer_lastname;
            $order['delivercode']=00000;
            $order->items=[];
            $order->counts=[];
            $order->questions=[];
            $order->answers=[];
        }


        return response()->json([
            'data'=>$orders
        ], 200);
    }

    public function acceptorder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
        

        $goodsorder=GoodsOrders::where('orderuniquecode', $request->code)->first();
        $goodsorder->status='تایید فروشنده';

        $status=new GoodsOrdersStatuses;

        $status->id=$goodsorder->id;
        $status->accept_time=Carbon::now();

        


        $this->sendsms($goodsorder->cunsomer_mobile, 'acceptorder', $goodsorder->cunsomer_mobile, null, null);
        $this->sendnotification($goodsorder->cunsomers->firebase_token, 'سفارش شما توسط فروشنده تایید شد!', 'وارد چهارسو شوید');


        $status->save();
        $goodsorder->update();
        return response()->json($status, 200);
    }

    public function prepareorder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
        $goodsorder=GoodsOrders::where('orderuniquecode', $request->code)->first();
        $goodsorder->status='در حال آماده سازی';
        $status=GoodsOrdersStatuses::find($goodsorder->id);
        $status->preparation_time=Carbon::now();
        $customer = Cunsomer::find($goodsorder->cunsomers_id);


        $useracount_Worker=$personal->useracounts[1];
        $useracount_Customer=$customer->useracounts[0];
        //$useracount_Customer->cash+=$goodsorder->off_amount;
        $useracountcustomerwithoff= $useracount_Customer->cash+$goodsorder->off_amount;
        if ($goodsorder->sendingprice<1) {
            $sendingprice=0;
        } else {
            $sendingprice=$goodsorder->sendingprice;
        }
        $cost=$goodsorder->totalamountitems+$goodsorder->packingprice+$sendingprice;

        if ($useracountcustomerwithoff>=$cost) {
            $goodsorder->paytype='اعتباری';
        } else {
            if ($goodsorder->off_amount>0) {
                if ($goodsorder->offcodeuse->offcode->dolaw==4) {
                    $useracountcustomerwithoff-=$goodsorder->off_amount;
                    $goodsorder->off_amount=0;
                    $offcodeuse=$goodsorder->offcodeuse;
                    $offcodeuse->success=0;
                    $offcodeuse->update();
                }
            }
        
            if ($useracountcustomerwithoff>0) {
                $goodsorder->paytype='نقدی-اعتباری';
            } else {
                $goodsorder->paytype='نقدی';
            }
        }
   

        if ($goodsorder->paytype=='اعتباری') {
            $cost-=$goodsorder->off_amount;


            $transactionbardasht = new Transations();
            $transactionbardasht->user_acounts_id=$useracount_Customer->id;
            $transactionbardasht->type='برداشت';
            $transactionbardasht->for='هزینه سفارش';
            $transactionbardasht->order_unique_code=$goodsorder->orderuniquecode;
            $transactionbardasht->amount=$cost;
            $transactionbardasht->from_to='به حساب خدمت رسان فروشنده با شناسه '.$useracount_Worker->id;
            $transactionbardasht->goods_order_id=$goodsorder->id;
            $transactionbardasht->user_account_fromto=$useracount_Worker->id;
            $useracount_Customer->cash=$useracount_Customer->cash-$cost;
            $transactionbardasht->save();

        
            $useracount_Customer->deductions+=$cost;
            $useracount_Customer->transactionsc+=1;


            $useracount_Customer->update();

            // $transactionvariz = new Transations();
            // $transactionvariz->user_acounts_id=$useracount_Worker->id;
            // $transactionvariz->type='واریز';
            // $transactionvariz->for='فروش کالا';
            // $transactionvariz->order_unique_code=$goodsorder->orderuniquecode;
            // $transactionvariz->amount=$cost;
            // $transactionvariz->from_to='از حساب مشتری با شناسه '.$useracount_Customer->id;
            // $useracount_Worker->cash=$useracount_Worker->cash+$cost;
            // $transactionvariz->save();
            // $useracount_Worker->update();



            $goodsorder->creditamount=$cost;
            $goodsorder->cashamount=0;
        } elseif ($goodsorder->paytype=='نقدی-اعتباری') {
            $naghd=$cost-$useracountcustomerwithoff;
            $cost-=$goodsorder->off_amount;
            //$useracount_Customer->cash-=$goodsorder->off_amount;


            //تراکنش های نقدی
        
  
            $transactionbardasht = new Transations();
            $transactionbardasht->user_acounts_id=$useracount_Customer->id;
            $transactionbardasht->type='برداشت';
            $transactionbardasht->for='هزینه سفارش';
            $transactionbardasht->order_unique_code=$goodsorder->orderuniquecode;
            $transactionbardasht->amount=$useracount_Customer->cash;
            $transactionbardasht->from_to='به حساب خدمت رسان فروشنده با شناسه '.$useracount_Worker->id;
            $transactionbardasht->goods_order_id=$goodsorder->id;
            $transactionbardasht->user_account_fromto=$useracount_Worker->id;
            $transactionbardasht->save();

            $transactionbardashtnaghd = new Transations();
            $transactionbardashtnaghd->user_acounts_id=$useracount_Customer->id;
            $transactionbardashtnaghd->type='برداشت';
            $transactionbardashtnaghd->method='نقدی';
            $transactionbardashtnaghd->for='هزینه سفارش';
            $transactionbardashtnaghd->order_unique_code=$goodsorder->orderuniquecode;
            $transactionbardashtnaghd->amount=$naghd;
            $transactionbardashtnaghd->from_to='به صورت نقدی به حساب خدمت رسان فروشنده با شناسه '.$useracount_Worker->id;
            $transactionbardashtnaghd->description='این تراکنش به صورت نقدی و بدون اعمال در حساب خدمت رسان ثبت گردید';
            $transactionbardashtnaghd->goods_order_id=$goodsorder->id;
            $transactionbardashtnaghd->user_account_fromto=$useracount_Worker->id;
            $transactionbardashtnaghd->save();

            $goodsorder->creditamount=$useracount_Customer->cash;
            $goodsorder->cashamount=$naghd;

            $useracount_Customer->deductions+=$useracount_Customer->cash;
            $useracount_Customer->transactionsc+=1;

            $useracount_Customer->cash=$useracount_Customer->cash-$useracount_Customer->cash;
            $useracount_Customer->update();


  
        //   $transactionvariz = new Transations();
        //   $transactionvariz->user_acounts_id=$useracount_Worker->id;
        //   $transactionvariz->type='واریز';
        //   $transactionvariz->for='فروش کالا';
        //   $transactionvariz->order_unique_code=$orderdata->order_unique_code;
        //   $transactionvariz->amount=$useracount_Customer->cash;
        //   $transactionvariz->from_to='از حساب مشتری با شناسه '.$useracount_Customer->id;
        //   $transactionvariz->save();

        //   $transactionvariznaghd = new Transations();
        //   $transactionvariznaghd->user_acounts_id=$useracount_Worker->id;
        //   $transactionvariznaghd->type='واریز';
        //   $transactionvariznaghd->method='نقدی';
        //   $transactionvariznaghd->for='انجام سفارش';
        //   $transactionvariznaghd->order_unique_code=$orderdata->order_unique_code;
        //   $transactionvariznaghd->amount=$naghd;
        //   $transactionvariznaghd->from_to='به صورت نقد از حساب مشتری با شناسه'.$useracount_Customer->id;
        //   $transactionvariznaghd->description='این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
        //   $transactionvariznaghd->save();

        //   $useracount_Worker->cash=$useracount_Worker->cash+$useracount_Customer->cash;
        //   $useracount_Worker->update();
        } else {
            $cost-=$goodsorder->off_amount;
  
            //تراکنش های نقدی
            $transactionbardashtnaghd = new Transations();
            $transactionbardashtnaghd->user_acounts_id=$useracount_Customer->id;
            $transactionbardashtnaghd->type='برداشت';
            $transactionbardashtnaghd->method='نقدی';
            $transactionbardashtnaghd->for='هزینه سفارش';
            $transactionbardashtnaghd->order_unique_code=$goodsorder->orderuniquecode;
            $transactionbardashtnaghd->amount=$cost;
            $transactionbardashtnaghd->from_to='به صورت نقدی به حساب خدمت رسان فروشنده با شناسه '.$useracount_Worker->id;
            $transactionbardashtnaghd->description='این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
            $transactionbardashtnaghd->goods_order_id=$goodsorder->id;
            $transactionbardashtnaghd->user_account_fromto=$useracount_Worker->id;
            $transactionbardashtnaghd->save();

            $goodsorder->creditamount=0;
            $goodsorder->cashamount=$cost;
            //    $transactionvariznaghd =new Transations();
        //    $transactionvariznaghd->user_acounts_id=$useracount_Worker->id;
        //    $transactionvariznaghd->type='واریز';
        //    $transactionvariznaghd->method='نقدی';
        //    $transactionvariznaghd->for='فروش کالا';
        //    $transactionvariznaghd->order_unique_code=$orderdata->order_unique_code;
        //    $transactionvariznaghd->amount=$cost;
        //    $transactionvariznaghd->from_to='به صورت نقد از حساب مشتری با شناسه'.$useracount_Customer->id;
        //    $transactionvariznaghd->description='این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
        //    $transactionvariznaghd->save();
        }
      




        $status->update();
        $goodsorder->update();
        return response()->json($status, 200);
    }

    public function sendorder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
        $goodsorder=GoodsOrders::where('orderuniquecode', $request->code)->first();
        $goodsorder->status='ارسال شده';
        $status=GoodsOrdersStatuses::find($goodsorder->id);
        $status->send_time=Carbon::now();


    








        $status->save();
        $goodsorder->update();
        return response()->json($status, 200);
    }

    public function deliverorder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
        $goodsorder=GoodsOrders::where('orderuniquecode', $request->code)->first();
        $customer = Cunsomer::find($goodsorder->cunsomers_id);
        $goodsorder->status='تحویل شده';
        $status=GoodsOrdersStatuses::find($goodsorder->id);

        $delivercode=$request->confirmcode;

        if ($delivercode!=$goodsorder->delivercode) {
            return response()->json(
                ['code' => '400','error'=>'کد تحویل نا درست می باشد'],
                200
            );
        }
        
        //return response()->json($goodsorder->cashamount ,200);
        $useracount_Worker=$personal->useracounts[1];
        $useracount_Customer=$customer->useracounts[0];
        //$cost=$goodsorder->totalamountitems+$goodsorder->packingprice+$goodsorder->sendingprice;
        //$creditamount=$cost-$goodsorder->cashamount;

        if ($goodsorder->off_amount>0) {
            $useracount_broker=UserAcounts::where('roles_id', 1)->first();
            $offcode=OffCodeUse::find($goodsorder->off_code);



            $transactiongiftv = new Transations();
            $transactiongiftv->user_acounts_id=$useracount_broker->id;
            $transactiongiftv->type='برداشت';
            $transactiongiftv->for='کد تخفیف';
            $transactiongiftv->order_unique_code=$goodsorder->orderuniquecode;
            $transactiongiftv->amount=$goodsorder->off_amount;
            $transactiongiftv->from_to='کد تخفیف  '.$offcode->offcode->code.'به مشتری با شناسه حساب '.$useracount_Customer->id;
            $transactiongiftv->goods_order_id=$goodsorder->id;
            $transactiongiftv->user_account_fromto=$useracount_Customer->id;
            $transactiongiftv->off_code_use_id=$offcode->id;

            $useracount_broker->cash-=$goodsorder->off_amount;
  
            $useracount_broker->deductions+=$goodsorder->off_amount;
            $useracount_broker->transactionsc+=1;
  
            $transactiongiftv->save();
            $useracount_broker->update();
        }


        //--------------تراکنش های پورسانت فروش

        $store=Store::find($goodsorder->store_id);
        $porsant=0;
        if ($store->commission>0) {
            $useracount_broker=UserAcounts::where('roles_id', 1)->first();

            $porsant=($goodsorder->totalamountitems)*($store->commission)/100;


            $transactionporsantb = new Transations();
            $transactionporsantb->user_acounts_id=$useracount_Worker->id;
            $transactionporsantb->type='برداشت';
            $transactionporsantb->for='پورسانت فروش';
            $transactionporsantb->order_unique_code=$goodsorder->orderuniquecode;
            $transactionporsantb->amount=$porsant;
            $transactionporsantb->from_to='به حساب چهارسو ';
            $transactionporsantb->goods_order_id=$goodsorder->id;
            $transactionporsantb->save();

            $transactionporsantv = new Transations();
            $transactionporsantv->user_acounts_id=$useracount_broker->id;
            $transactionporsantv->type='واریز';
            $transactionporsantv->for='پورسانت فروش';
            $transactionporsantv->order_unique_code=$goodsorder->orderuniquecode;
            $transactionporsantv->amount=$porsant;
            $transactionporsantv->from_to='از خدمت رسان فروشنده با شناسه حساب '.$useracount_Worker->id;
            $transactionporsantv->goods_order_id=$goodsorder->id;
            $transactionporsantv->user_account_fromto=$useracount_Worker->id;
            $transactionporsantv->save();


            $useracount_Worker->cash-=$porsant;
            $useracount_broker->cash+=$porsant;

            $useracount_broker->expenses+=$porsant;
            $useracount_Worker->deductions+=$porsant;

            $useracount_broker->transactionsc+=1;
            $useracount_Worker->transactionsc+=1;

            $useracount_broker->update();
            $useracount_Worker->update();
        }
        //--------------------------------------

        //----------تراکنش های کسورات قانونی
        if ($store->legal_deductions>0) {
            $legal=($goodsorder->totalamountitems-$porsant)*($store->legal_deductions)/100;

            $useracountlegal=UserAcounts::where('personal_id', $store->owner_id)->where('type', 'کسورات قانونی')->first();

            if (is_null($useracountlegal)) {
                $useracountlegal=new UserAcounts;
                $useracountlegal->personal_id=$personal->id;

                $useracountlegal->user = 'خدمت رسان';
                $useracountlegal->type = 'کسورات قانونی';
                $useracountlegal->cash=$legal;
                $useracountlegal->expenses=$legal;
                $useracountlegal->deductions=0;
                $useracountlegal->transactionsc=1;

                $useracountlegal->save();
            } else {
                $useracountlegal->cash+=$legal;
                $useracountlegal->expenses+=$legal;
                $useracountlegal->transactionsc+=1;
                $useracountlegal->update();
            }



            $transactionporsantlb = new Transations();
            $transactionporsantlb->user_acounts_id=$useracount_Worker->id;
            $transactionporsantlb->type='برداشت';
            $transactionporsantlb->for='کسورات قانونی';
            $transactionporsantlb->order_unique_code=$goodsorder->orderuniquecode;
            $transactionporsantlb->amount=$legal;
            $transactionporsantlb->from_to='به حساب کسورات قانونی خدمت رسان فروشنده به شناسه '.$useracountlegal->id;
            $transactionporsantlb->goods_order_id=$goodsorder->id;
            $transactionporsantlb->user_account_fromto=$useracountlegal->id;
            $transactionporsantlb->save();
  
            $transactionporsantlv = new Transations();
            $transactionporsantlv->user_acounts_id=$useracountlegal->id;
            $transactionporsantlv->type='واریز';
            $transactionporsantlv->for='کسورات قانونی';
            $transactionporsantlv->order_unique_code=$goodsorder->orderuniquecode;
            $transactionporsantlv->amount=$legal;
            $transactionporsantlv->from_to='از حساب درآمد خدمت رسان فروشنده با شناسه  '.$useracount_Worker->id;
            $transactionporsantlv->goods_order_id=$goodsorder->id;
            $transactionporsantlv->user_account_fromto=$useracount_Worker->id;
            $transactionporsantlv->save();

            $useracount_Worker->update();
        }
        //-----------------------------------


        if ($goodsorder->paytype=='اعتباری') {

            // $transactionbardasht = new Transations();
            // $transactionbardasht->user_acounts_id=$useracount_Customer->id;
            // $transactionbardasht->type='برداشت';
            // $transactionbardasht->for='هزینه سفارش';
            // $transactionbardasht->order_unique_code=$goodsorder->orderuniquecode;
            // $transactionbardasht->amount=$cost;
            // $transactionbardasht->from_to='به حساب خدمت رسان فروشنده با شناسه '.$useracount_Worker->id;
            // $useracount_Customer->cash=$useracount_Customer->cash-$cost;
            // $transactionbardasht->save();
            // $useracount_Customer->update();
    
            $transactionvariz = new Transations();
            $transactionvariz->user_acounts_id=$useracount_Worker->id;
            $transactionvariz->type='واریز';
            $transactionvariz->for='فروش کالا';
            $transactionvariz->order_unique_code=$goodsorder->orderuniquecode;
            $transactionvariz->amount=$goodsorder->creditamount+$goodsorder->off_amount;
            $transactionvariz->from_to='از حساب مشتری با شناسه '.$useracount_Customer->id;
            $transactionvariz->goods_order_id=$goodsorder->id;
            $transactionvariz->user_account_fromto=$useracount_Customer->id;
            $useracount_Worker->cash=$useracount_Worker->cash+$goodsorder->creditamount+$goodsorder->off_amount;
            $transactionvariz->save();

            $useracount_Worker->expenses+=$goodsorder->creditamount+$goodsorder->off_amount;
            $useracount_Worker->transactionsc+=1;

            $useracount_Worker->update();
        } elseif ($goodsorder->paytype=='نقدی') {
            $transactionvariznaghd = new Transations();
      
            //تراکنش های نقدی
 
            $transactionvariznaghd->user_acounts_id=$useracount_Worker->id;
            $transactionvariznaghd->type='واریز';
            $transactionvariznaghd->method='نقدی';
            $transactionvariznaghd->for='فروش کالا';
            $transactionvariznaghd->order_unique_code=$goodsorder->orderuniquecode;
            $transactionvariznaghd->amount=$goodsorder->cashamount;
            $transactionvariznaghd->from_to='به صورت نقد از حساب مشتری با شناسه'.$useracount_Customer->id;
            $transactionvariznaghd->description='این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
            $transactionvariznaghd->goods_order_id=$goodsorder->id;
            $transactionvariznaghd->user_account_fromto=$useracount_Customer->id;
            $transactionvariznaghd->save();
        } else {


               //تراکنش های نقدی
            //$naghd=$cost-$useracount_Customer->cash;
            //$goodsorder->cashamount=$naghd;
    
            $transactionvariz = new Transations();
            $transactionvariz->user_acounts_id=$useracount_Worker->id;
            $transactionvariz->type='واریز';
            $transactionvariz->for='فروش کالا';
            $transactionvariz->order_unique_code=$goodsorder->orderuniquecode;
            $transactionvariz->amount=$goodsorder->creditamount+$goodsorder->off_amount;
            $transactionvariz->from_to='از حساب مشتری با شناسه '.$useracount_Customer->id;
            $transactionvariz->goods_order_id=$goodsorder->id;
            $transactionvariz->user_account_fromto=$useracount_Customer->id;
            $transactionvariz->save();
    
            $transactionvariznaghd = new Transations();
            $transactionvariznaghd->user_acounts_id=$useracount_Worker->id;
            $transactionvariznaghd->type='واریز';
            $transactionvariznaghd->method='نقدی';
            $transactionvariznaghd->for='فروش کالا';
            $transactionvariznaghd->order_unique_code=$goodsorder->orderuniquecode;
            $transactionvariznaghd->amount=$goodsorder->cashamount;
            $transactionvariznaghd->from_to='به صورت نقد از حساب مشتری با شناسه'.$useracount_Customer->id;
            $transactionvariznaghd->description='این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
            $transactionvariznaghd->goods_order_id=$goodsorder->id;
            $transactionvariznaghd->user_account_fromto=$useracount_Customer->id;
            $transactionvariznaghd->save();
    
            $useracount_Worker->expenses+=$goodsorder->creditamount+$goodsorder->off_amount;
            $useracount_Worker->transactionsc+=1;

            $useracount_Worker->cash=$useracount_Worker->cash+$goodsorder->creditamount+$goodsorder->off_amount;
            $useracount_Worker->update();
        }

           


      

        $status->deliver_time=Carbon::now();

        $status->save();
        $goodsorder->update();

        
        return response()->json(
            ['code' => '200','error'=>'کد نحویل درست می باشد'],
            200
        );
    }

    public function cancelorderc(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $goodsorder=GoodsOrders::where('orderuniquecode', $request->code)->first();
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();



        if ($goodsorder->status=='لغو شده' || $goodsorder->status=='تحویل شده' || $goodsorder->status=='ارسال شده' ||$goodsorder->status=='در حال آماده سازی') {
            return response()->json('سفارش در وضعیتی نیست که بتوان ان را لغو کرد', 400);
        }

        $cancelreason='';
       
         
        //$status->cancelreason='لغو شده توسط مشتری در مرحله '.$goodsorder->status;
        $cancelreason='لغو شده توسط مشتری در مرحله '.$goodsorder->status;


        $status=GoodsOrdersStatuses::find($goodsorder->id);

        if (is_null($status)) {
            $status=new GoodsOrdersStatuses;
            $status->id=$goodsorder->id;
            $status->cancel_time=Carbon::now();
            $status->cancelreason=$cancelreason;

            $status->save();
        } else {
            $status->cancel_time=Carbon::now();
            $status->cancelreason=$cancelreason;
            $status->update();
        }


        $goodsorder->status='لغو شده';

        if ($goodsorder->off_code) {
            $offcodeuse=$goodsorder->offcodeuse;
            $offcodeuse->amount=0;
            $offcodeuse->success=0;
            $offcodeuse->update();
            $goodsorder->off_amount=0;
        }

        $goodsorder->update();




        return response()->json($status, 200);
    }

    public function cancelorderp(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $goodsorder=GoodsOrders::where('orderuniquecode', $request->code)->first();
        $personal = Personal::where('personal_mobile', $mobile)->first();



        if ($goodsorder->status=='لغو شده' || $goodsorder->status=='تحویل شده' || $goodsorder->status=='ارسال شده' ||$goodsorder->status=='در حال آماده سازی') {
            return response()->json('سفارش در وضعیتی نیست که بتوان ان را لغو کرد', 400);
        }

        $cancelreason='';

        if ($goodsorder->status=='تایید شده') {
            return response()->json('سفارش در وضعیتی نیست که بتوان ان را لغو کرد', 400);
        }
        //$status->cancelreason='لغو شده توسط خدمت رسان فروشنده در مرحله '.$goodsorder->status;
        $cancelreason='لغو شده توسط خدمت رسان فروشنده در مرحله '.$goodsorder->status;




        $status=GoodsOrdersStatuses::find($goodsorder->id);

        if (is_null($status)) {
            $status=new GoodsOrdersStatuses;
            $status->id=$goodsorder->id;
            $status->cancel_time=Carbon::now();
            $status->cancelreason=$cancelreason;

            $status->save();
        } else {
            $status->cancel_time=Carbon::now();
            $status->cancelreason=$cancelreason;
            $status->update();
        }


        $goodsorder->status='لغو شده';

        if ($goodsorder->off_code) {
            $offcodeuse=$goodsorder->offcodeuse;
            $offcodeuse->amount=0;
            $offcodeuse->success=0;
            $offcodeuse->update();
            $goodsorder->off_amount=0;
        }

        $goodsorder->update();




        return response()->json($status, 200);
    }


    public function getgoodsordersacustomer(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $orders=GoodsOrders::where('cunsomers_id', $customer->id)
        ->where(function ($query) {
            $query->where('status', 'تحویل شده')
            ->orWhere('status', 'لغو شده');
        })
           ->get();




        foreach ($orders as $order) {

            //$order['images']=$order->images;
            //$order['statuseses']=$order->statuses;
            $order->items=[];
            $order->counts=[];
            $order->questions=[];
            $order->answers=[];
            $order['store_name']=$order->store->store_name;
        }


        return response()->json([
            'data'=>$orders
        ], 200);
    }

    public function getallordersapersonal(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $store=Store::where('owner_id', $personal->id)->first();

        $orders=GoodsOrders::where('store_id', $store->id)
        ->where(function ($query) {
            $query->where('status', 'تحویل شده')
         ->orWhere('status', 'لغو شده');
        })
        ->get();



        foreach ($orders as $order) {

            //$order['images']=$order->images;
            //$order['statuseses']=$order->statuses;
            $order['customer_mobile']=$order->cunsomers->customer_mobile;
            $order['customer_name']=$order->cunsomers->customer_firstname.' '.$order->cunsomers->customer_lastname;
            $order['delivercode']=00000;
            $order->items=[];
            $order->counts=[];
            $order->questions=[];
            $order->answers=[];
        }


        return response()->json([
            'data'=>$orders
        ], 200);
    }
}
