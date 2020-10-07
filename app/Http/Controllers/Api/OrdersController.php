<?php

namespace App\Http\Controllers\Api;

use App\Models\Orders\Order;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Models\Personals\Personal;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Acounting\UserAcounts;
use App\Models\Services\Service;
use App\Models\Acounting\Transations;
use Carbon\Carbon;

class OrdersController extends Controller
{
  public function referredOrders(Request $request)
  {

    $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
    $mobile = $payload->get('mobile');
    $personal = Personal::where('personal_mobile', $mobile)->first();
    //$orders = $personal->order->where('order_type','ارجاع داده شده');
    $orders = $personal->order->where('order_type', 'معلق');

    foreach ($orders as $key => $order) {
      $service = Service::where('id', $order->service_id)->first()->service_title;
      $order['service_name'] = $service;
    }

    $ords = [];
    foreach ($orders as $key => $or) {
      $ords[] = $or;
    }
    return response()->json([
      'data' => $ords,
    ], 200);
  }


  public function offeringOrders(Request $request)
  {
    $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
    $mobile = $payload->get('mobile');
    $personal = Personal::where('personal_mobile', $mobile)->first();
    $orders = $personal->order->where('order_type', 'پیشنهاد داده شده');
    foreach ($orders as $key => $order) {
      $service = Service::where('id', $order->service_id)->first()->service_title;
      $order['service_name'] = $service;
    }
    return response()->json([
      'data' => $orders,
    ], 200);
  }

  public function allRelatedOrders(Request $request)
  {

    $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
    $mobile = $payload->get('mobile');
    $personal = Personal::where('personal_mobile', $mobile)->first();
    $orders = $personal->order;
    $order_array =[];
   foreach ($orders as $key => $item) {
    if($item->order_type == 'تسویه شده'){
      continue;
    }
    array_push($order_array,$item);
   }
    foreach ($order_array as $key => $order) {
    
      $service = Service::where('id', $order->service_id)->first()->service_title;
      $order['service_name'] = $service;
      if (count($order->orderImages)) {
        foreach ($order->orderImages as $key => $image) {
          if($image->image_type == 'faktor') $order['faktor'] = $image->image_url;
          if($image->image_type == 'image1') $order['image1'] = $image->image_url;
          if($image->image_type == 'image2') $order['image2'] = $image->image_url;
          if($image->image_type == 'image3') $order['image3'] = $image->image_url;
  
        }
      }

      if ($order->orderDetail) {
        
        $cost=$order->orderDetail->order_recived_price+$order->orderDetail->order_pieces_cast;
        $order['cost'] = $cost;
        $order['endtime'] = $order->orderDetail->order_end_time;
  
        $cunsomeracount= Cunsomer::where('customer_mobile',$order->order_username_customer)->first()->useracounts[0]->cash;
  
  
        if($cunsomeracount>=$cost){
          $order['cunsomer_charge'] = $cost;
        }else{
          $order['cunsomer_charge'] = $cunsomeracount;
        }
      }
    }
    return response()->json([
      'data' => $order_array,
    ], 200);
  }

  public function finishedOrders(Request $request)
  {

    $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
    $mobile = $payload->get('mobile');
    $personal = Personal::where('personal_mobile', $mobile)->first();
    //$orders = $personal->order->where('order_type','ارجاع داده شده');
    $orders = $personal->order->where('order_type', 'تسویه شده');

    foreach ($orders as $key => $order) {
      $service = Service::where('id', $order->service_id)->first()->service_title;
      $order['service_name'] = $service;
    }

    $ords = [];
    foreach ($orders as $key => $or) {
      $ords[] = $or;
    }
    return response()->json([
      'data' => $ords,
    ], 200);
  }

  public function getOrder(Request $request)
  {
    $Code = $request->order_code;
    $order = Order::where('order_unique_code', $Code)->first();
    if ($order !== null) {
     

      $service = Service::where('id', $order->service_id)->first();
      $order['service_name'] = $service->service_title;
      $order['service_price'] = $service->service_price;
      $order['service_desc'] = $service->service_desc;

    if (count($order->orderImages)) {
      foreach ($order->orderImages as $key => $image) {
        if($image->image_type == 'faktor') $order['faktor'] = $image->image_url;
        if($image->image_type == 'image1') $order['image1'] = $image->image_url;
        if($image->image_type == 'image2') $order['image2'] = $image->image_url;
        if($image->image_type == 'image3') $order['image3'] = $image->image_url;

      }
    }

    if ($order->orderDetail) {
        
      $cost=$order->orderDetail->order_recived_price+$order->orderDetail->order_pieces_cast;
      $order['cost'] = $cost;
      $order['endtime'] = $order->orderDetail->order_end_time;

      $cunsomeracount= Cunsomer::where('customer_mobile',$order->order_username_customer)->first()->useracounts[0]->cash;


      if($cunsomeracount>=$cost){
        $order['cunsomer_charge'] = $cost;
      }else{
        $order['cunsomer_charge'] = $cunsomeracount;
      }
    }

      
      return response()->json(
        $order,
        200
      );
    } else {
      return response()->json([
        'data' => 'سفارشی با این کد درج نشده است',
      ], 404);
    }
  }

  public function refferOrderToPersonal(Request $request)
  {
    $Code = $request->order_code;
    $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
    $mobile = $payload->get('mobile');
    $personal = Personal::where('personal_mobile', $mobile)->first();
    $orderdata = Order::where('order_unique_code', $Code)->first();
    $check_order_personal = $orderdata->personals->where('id', $personal->id);
    if (count($check_order_personal)) {
      return response()->json([
        'data' => '',
        'error' => 'سفارش به خدمت رسان ارجاع شده است',
      ], 404);
    }
    $order = Order::where('order_unique_code', $Code)->update([
      'order_type' => 'شروع نشده'
    ]);

    $personal->order()->attach($orderdata->id);

    return response()->json([
      'data' => $orderdata->fresh(),
    ], 200);
  }

  public function startOrder(Request $request)
  {
    $Code = $request->order_code;
    $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
    $mobile = $payload->get('mobile');
    $personal = Personal::where('personal_mobile', $mobile)->first();
    if (Order::where('order_unique_code', $Code)
      ->where('order_type', 'در حال انجام')
      ->whereHas('personals', function ($q) use ($personal) {
        $q->where('id', $personal->id);
      })
      ->count()
    ) {
      return response()->json([
        'data' => '',
        'error' => 'درخواست شروع به کار توسط شما ثبت شده است',
      ], 404);
    }
    Order::where('order_unique_code', $Code)->update([
      'order_type' => 'در حال انجام'
    ]);
    $order = Order::where('order_unique_code', $Code)->first();

    $positions[]=$request->positionslat;
    $positions[]=$request->positionslon;

    $order->orderDetail()->create([
      'order_start_time' => Carbon::now(),
      'order_start_description' => $request->description,
      'order_start_time_positions' => serialize($positions)
    ]);

     if ($order->orderDetail) {
        
        $cost=$order->orderDetail->order_recived_price+$order->orderDetail->order_pieces_cast;
        $order['cost'] = $cost;
        $order['endtime'] = $order->orderDetail->order_end_time;
  
        $cunsomeracount= Cunsomer::where('customer_mobile',$order->order_username_customer)->first()->useracounts[0]->cash;
  
  
        if($cunsomeracount>=$cost){
          $order['cunsomer_charge'] = $cost;
        }else{
          $order['cunsomer_charge'] = $cunsomeracount;
        }
      }


    return response()->json([
      'data' => $order,
    ], 200);
  }


  // payane kar
  public function endOrder(Request $request)
  {
    $Code = $request->order_code;
    $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
    $mobile = $payload->get('mobile');
    $personal = Personal::where('personal_mobile', $mobile)->first();
    
    if (Order::where('order_unique_code', $Code)
      ->where('order_type', 'انجام شده')
      ->whereHas('personals', function ($q) use ($personal) {
        $q->where('id', $personal->id);
      })
      ->count()
    ) {
      return response()->json([
        'data' => '',
        'error' => 'درخواست پایان کار توسط شما ثبت شده است',
      ], 404);
    }

     Order::where('order_unique_code', $Code)->update([
      'order_type' => 'انجام شده'
    ]);
    $order = Order::where('order_unique_code', $Code)->first();

    $positions[]=$request->positionslat;
    $positions[]=$request->positionslon;

    $order->orderDetail()->update([
      'order_end_time' => Carbon::now(),
      'order_end_time_description' => $request->description,
      'order_end_time_positions' => serialize($positions),
      'order_recived_price' => $request->order_cast,
      'order_pieces_cast' => $request->pieces_cast

    ]);
    
    if ($order->orderDetail) {
        
      $cost=$order->orderDetail->order_recived_price+$order->orderDetail->order_pieces_cast;
      $order['cost'] = $cost;
      $order['endtime'] = $order->orderDetail->order_end_time;

      $cunsomeracount= Cunsomer::where('customer_mobile',$order->order_username_customer)->first()->useracounts[0]->cash;


      if($cunsomeracount>=$cost){
        $order['cunsomer_charge'] = $cost;
      }else{
        $order['cunsomer_charge'] = $cunsomeracount;
      }
    }


    return response()->json([
      'data' => $order,
    
    ], 200);
  }


  public function uploadImages(Request $request)
  {

    $image = $request->image;
     $imageCode = $request->image_type_code;
     $orderCode = $request->order_code;

   
    $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
    $mobile = $payload->get('mobile');
    $personal = Personal::where('personal_mobile', $mobile)->first();
    $orderdata = Order::where('order_unique_code', $orderCode)->first();

    if ($imageCode == '4') $name = 'faktor';
    if ($imageCode == '1') $name = 'image1';
    if ($imageCode == '2') $name = 'image2';
    if ($imageCode == '3') $name = 'image3';

    $file = $name .'_' .time(). '.' . $request->image->getClientOriginalExtension();
    $request->image->move(public_path('/uploads/orders/' . $orderdata->id), $file);
    $image_url = 'orders/'.$orderdata->id . '/' . $file;
    $orderdata->orderImages()->create([
      'image_type' => $name,
      'image_url' => $image_url,
    ]);

    return response()->json([
      'data' => $image_url,
    ], 200);
  }



  // tasvie hesab
  public function reckoningorder(Request $request)
  {
    $Code = $request->order_code;
    $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
    $mobile = $payload->get('mobile');
    $personal = Personal::where('personal_mobile', $mobile)->first();
    $orderdata = Order::where('order_unique_code', $Code)->first();
    $cunsomer=Cunsomer::where('customer_mobile',$orderdata->order_username_customer)->first();

    if (Order::where('order_unique_code', $Code)
      ->where('order_type', 'تسویه شده')
      ->whereHas('personals', function ($q) use ($personal) {
        $q->where('id', $personal->id);
      })
      ->count()
    ) {
      return response()->json([
        'data' => '',
        'error' => 'درخواست تسویه حساب توسط شما ثبت شده است',
      ], 404);
    }



  

    $cost=$orderdata->orderDetail->order_recived_price+$orderdata->orderDetail->order_pieces_cast;
   
    $useracount_Worker=$personal->useracounts[1];
    $useracount_Customer=$cunsomer->useracounts[0];

    if($useracount_Customer->cash>=$cost){

      $transactionbardasht = new Transations();
      $transactionvariz = new Transations();


      $transactionbardasht->user_acounts_id=$useracount_Customer->id;     

      $transactionbardasht->type='برداشت';
      $transactionbardasht->for='هزینه سفارش';
      $transactionbardasht->order_unique_code=$orderdata->order_unique_code;
      $transactionbardasht->amount=$cost;
      $transactionbardasht->from_to='به حساب خدمت رسان با شناسه '.$useracount_Worker->id;

       

      $transactionvariz->user_acounts_id=$useracount_Worker->id;     

      $transactionvariz->type='واریز';
      $transactionvariz->for='انجام سفارش';
      $transactionvariz->order_unique_code=$orderdata->order_unique_code;
      $transactionvariz->amount=$cost;
      $transactionvariz->from_to='از حساب مشتری با شناسه '.$useracount_Customer->id;


      $useracount_Worker->cash=$useracount_Worker->cash+$cost;
      $useracount_Customer->cash=$useracount_Customer->cash-$cost;


      $transactionbardasht->save();
      $transactionvariz->save();
     
      $useracount_Worker->update();
      $useracount_Customer->update();





    }else{
      if($useracount_Customer->cash>0){

        $transactionbardasht = new Transations();
        $transactionvariz = new Transations();

        $transactionbardashtnaghd = new Transations();
        $transactionvariznaghd = new Transations();
  
  
        $transactionbardasht->user_acounts_id=$useracount_Customer->id;     
        $transactionbardasht->type='برداشت';
        $transactionbardasht->for='هزینه سفارش';
        $transactionbardasht->order_unique_code=$orderdata->order_unique_code;
        $transactionbardasht->amount=$useracount_Customer->cash;
        $transactionbardasht->from_to='به حساب خدمت رسان با شناسه '.$useracount_Worker->id;
  
         
  
        $transactionvariz->user_acounts_id=$useracount_Worker->id;     
        $transactionvariz->type='واریز';
        $transactionvariz->for='انجام سفارش';
        $transactionvariz->order_unique_code=$orderdata->order_unique_code;
        $transactionvariz->amount=$useracount_Customer->cash;
        $transactionvariz->from_to='از حساب مشتری با شناسه '.$useracount_Customer->id;
        


        //تراکنش های نقدی
        $naghd=$cost-$useracount_Customer->cash;


        $transactionbardashtnaghd->user_acounts_id=$useracount_Customer->id;     
        $transactionbardashtnaghd->type='برداشت';
        $transactionbardashtnaghd->method='نقدی';
        $transactionbardashtnaghd->for='هزینه سفارش';
        $transactionbardashtnaghd->order_unique_code=$orderdata->order_unique_code;
        $transactionbardashtnaghd->amount=$naghd;
        $transactionbardashtnaghd->from_to='به صورت نقدی به حساب خدمت رسان با شناسه '.$useracount_Worker->id;
        $transactionbardashtnaghd->description='این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';

        
        $transactionvariznaghd->user_acounts_id=$useracount_Worker->id;     
        $transactionvariznaghd->type='واریز';
        $transactionvariznaghd->method='نقدی';
        $transactionvariznaghd->for='انجام سفارش';
        $transactionvariznaghd->order_unique_code=$orderdata->order_unique_code;
        $transactionvariznaghd->amount=$naghd;
        $transactionvariznaghd->from_to='به صورت نقد از حساب مشتری با شناسه'.$useracount_Customer->id;
        $transactionvariznaghd->description='این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';



        $useracount_Worker->cash=$useracount_Worker->cash+$useracount_Customer->cash;
        $useracount_Customer->cash=$useracount_Customer->cash-$useracount_Customer->cash;
  
  
        $transactionbardasht->save();
        $transactionvariz->save();
        $transactionbardashtnaghd->save();
        $transactionvariznaghd->save();
       
        $useracount_Worker->update();
        $useracount_Customer->update();
  



      }else{


        
        $transactionbardashtnaghd = new Transations();
        $transactionvariznaghd = new Transations();



         //تراکنش های نقدی


         $transactionbardashtnaghd->user_acounts_id=$useracount_Customer->id;     
         $transactionbardashtnaghd->type='برداشت';
         $transactionbardashtnaghd->method='نقدی';
         $transactionbardashtnaghd->for='هزینه سفارش';
         $transactionbardashtnaghd->order_unique_code=$orderdata->order_unique_code;
         $transactionbardashtnaghd->amount=$cost;
         $transactionbardashtnaghd->from_to='به صورت نقدی به حساب خدمت رسان با شناسه '.$useracount_Worker->id;
         $transactionbardashtnaghd->description='این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
 
         
         $transactionvariznaghd->user_acounts_id=$useracount_Worker->id;     
         $transactionvariznaghd->type='واریز';
         $transactionvariznaghd->method='نقدی';
         $transactionvariznaghd->for='انجام سفارش';
         $transactionvariznaghd->order_unique_code=$orderdata->order_unique_code;
         $transactionvariznaghd->amount=$cost;
         $transactionvariznaghd->from_to='به صورت نقد از حساب مشتری با شناسه'.$useracount_Customer->id;
         $transactionvariznaghd->description='این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
 
 
 
   
   
         $transactionbardashtnaghd->save();
         $transactionvariznaghd->save();
        


      }
    }



   






    $order = Order::where('order_unique_code', $Code)->update([
      'order_type' => 'تسویه شده'
    ]);


    return response()->json([
      'data' => $orderdata->fresh(),
    ], 200);
  }



 

}
