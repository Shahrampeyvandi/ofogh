<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Services\Service;
use App\Models\Services\ServiceCategory;
use App\Models\App\Slideshow;

class ServiceController extends Controller
{
    public function getService(Request $request)
    {
      $service_id = $request->service_id;


      $service = Service::where('id', $service_id)->first();

      $slideshow=Slideshow::where('place','خدمات')->where('page_id',$service->id)->where('status',1)->where('release','<=',date("Y-m-d"))->where('expiry','>=',date("Y-m-d"))->get();


      $service['slideshow']=$slideshow;

      if ($service !== null) {
        $serviceArray = [];
        $serviceArray['service_title'] = $service->service_title;
        $serviceArray['service_percentage'] = $service->service_percentage;
        $serviceArray['service_offered_price'] = $service->service_offered_price;
        $serviceArray['service_desc'] = $service->service_desc;
        $serviceArray['service_alerts'] = $service->service_alerts;
        $serviceArray['service_type_send'] = $service->service_type_send;
        $serviceArray['service_price'] = $service->service_price;
        $serviceArray['price_type'] = $service->price_type;
        $serviceArray['service_icon'] = $service->service_icon;
        $serviceArray['service_pic_first'] = $service->service_pic_first;
        $serviceArray['service_pic_second'] = $service->service_pic_second;
        $serviceArray['service_offered_status'] = $service->service_offered_status;
        $serviceArray['service_category'] =  ServiceCategory::where('id',$service->service_category_id)->first()->category_title;

        return response()->json(
          $service,
          200
        );
      } else {
        return response()->json([
          'data' => 'خدمتی با این آی دی درج نشده است',
        ], 404);
      }
    }
  
}
