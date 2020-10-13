<?php

namespace App\Http\Controllers\Api;

use App\App\Models\Customers\CustomerAddress;
use App\Http\Controllers\Controller;
use App\Models\Acounting\UserAcounts;
use App\Models\Services\ServiceCategory;
use App\Models\Services\Service;
use App\Models\Orders\Order;
use App\Models\Neighborhood;
use App\Models\Store\Product;
use App\Models\Store\GoodsOrdersStatuses;
use App\Models\User;
use App\Models\Acounting\Transations;
use App\Models\City\City;
use Illuminate\Http\Request;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Personals\Personal;
use App\Models\Acounting\OffCode;
use App\Models\Acounting\OffCodeUse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Store\GoodsOrders;
use App\Models\Store\Store;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\App\Slideshow;
use Illuminate\Support\Facades\File;

class OffCodeController extends Controller
{

    public function firstcheckoffcode(Request $request){

        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $code=$request->code;
        $khka=$request->type;

        

        return $this->checkoffcode($code,$customer->id,$khka,$request->id,$request->amount,false);


    }

    public function checkoffcode(string $code,int $idcustomer,string $khka,int $idservorstor,int $amount=0,$number){

        $offcode=OffCode::where('code',$code)
        ->where(function ($query)  {
            $query->where('expiration','>=',date("Y-m-d"))
            ->orWhere('expiration',null);                        
            })
        ->first();

        if(is_null($offcode)){

            return response()->json([
                'message'=>'کد تخفیف اعتبار ندارد',
                'code'=>'405'
            ],200);
        }

        if($offcode->max_count<=OffCodeUse::where('off_code_id',$offcode->id)->where('cunsomer_id',$idcustomer)->where('success',1)->count()){

            return response()->json([
                'message'=>'شما قبلا از این کد استفاده کرده اید',
                'code'=>'405'
            ],200);


        }

        if($offcode->publish_mode=='خصوصی'){

            $members=explode(',',$offcode->members);

            if(!in_array($idcustomer,$members)){


    return response()->json([
                'message'=>'این کد برای شما قابل استفاده نمی باشد',
                'code'=>'405'
            ],200);


            }


        }

        if($khka==$offcode->do_on){

           // if($khka=='کالا'){

                return $this->checkoffcodegoodsorder($offcode,$idservorstor,$amount,$idcustomer,$number);



           // }else{

           //    return $this->checkoffcodeorder($offcode,$idservorstor,$number);

           // }





        }else{


            return response()->json([
                'message'=>'این کد مربوط به این دسته نمی باشد',
                'code'=>'405'
            ],200);
        }


    }

    public function checkoffcodegoodsorder(OffCode $offcode,int $store_id,int $amount,int $idcustomer,$number){

        switch($offcode->dolaw){
            case 0:


            break;
            case 1:


               
                if(!$number){

                    $order=Order::where('customer_id',$idcustomer)
                    ->where(function ($query)  {
                        $query->where('order_type','معلق')
                        ->orWhere('order_type','شروع نشده')
                        ->orWhere('order_type','در حال انجام')
                        ->orWhere('order_type','انجام شده')
                        ->orWhere('order_type','تسویه شده');                        
                        })->count();
    
                    if($order>0){
    
                        return response()->json([
                            'message'=>'این کد تنها مخصوص اولین سفارش خدمت است',
                            'code'=>'405'
                        ],200);
    
                    }
    

                    return response()->json([
                        'message'=>'بعد از اتمام سفارش میزان تخفیف از فاکتور شما کسر خواهد شد',
                        'data'=> $offcode->off_amount,
                        'data2'=> $offcode->off_type,
                        'code'=>'200'
                    ],200);

                }



            break;
            case 2:

                $order=GoodsOrders::where('cunsomers_id',$idcustomer)
                ->where(function ($query) {
                    $query->where('status','معلق')
                    ->orWhere('status','تایید فروشنده')
                    ->orWhere('status','در حال آماده سازی')
                    ->orWhere('status','ارسال شده')
                    ->orWhere('status','تحویل شده');
                   })
                ->count();
    
                if($order>0){

                    return response()->json([
                        'message'=>'این کد تنها مخصوص اولین سفارش کالا است',
                        'code'=>'405'
                    ],200);

                }




            break;
            case 3:

                if(!$number){
                if($offcode->off_type=='درصدی'){
                   
                    return response()->json([
                        'message'=>'تنها در صورت پرداخت اعتباری سفارش خدمت امکان استفاده از این تخفیف وجود خواهد داشت',
                        'data'=> $offcode->off_amount,
                        'data2'=> 'درصدی',
                        'code'=>'200'
                    ],200);
                
                    }else{

                        return response()->json([
                            'message'=>'تنها در صورت پرداخت اعتباری سفارش خدمت امکان استفاده از این تخفیف وجود خواهد داشت',
                            'data'=> $offcode->off_amount,
                            'data2'=> 'رقمی',
                            'code'=>'200'
                        ],200);
        
        
                    }
                }else{


                     
                }

                

            break;
            case 4:

                if(!$number){
                if($offcode->off_type=='درصدی'){
                    return $this->offcodepricepercent($offcode->min_amount,$offcode->max_amount,$amount,$offcode->off_amount,false);
                    // return response()->json([
                    //     'message'=>'تنها در صورت پرداخت اعتباری سفارش کالا امکان استفاده از کد وجود خواهد داشت',
                    //     'data'=> $this->offcodepricepercent($offcode->min_amount,$offcode->max_amount,$amount,$offcode->off_amount,true),
                    //     'code'=>'200'
                    // ],200);
                
                    }else{

                        return $this->offcodepricestatic($offcode->min_amount,$amount,$offcode->off_amount,false);
                        // return response()->json([
                        //     'message'=>'تنها در صورت پرداخت اعتباری سفارش کالا امکان استفاده از کد وجود خواهد داشت',
                        //     'data'=> $this->offcodepricestatic($offcode->min_amount,$amount,$offcode->off_amount,true),
                        //     'code'=>'200'
                        // ],200);
        
        
        
                    }
                }else{

                    if($offcode->off_type=='درصدی'){
                        return $this->offcodepricepercent($offcode->min_amount,$offcode->max_amount,$amount,$offcode->off_amount,true);
                    
                        }else{
    
                            return $this->offcodepricestatic($offcode->min_amount,$amount,$offcode->off_amount,true);
            
        
            
                        }

                     
                }

            break;
            default:
            return response()->json([
                'message'=>'امکان اعمال کد تخفیف وجود ندارد',
                'code'=>'405'
            ],200);
        }
    

        if($offcode->off_type=='درصدی'){
            return $this->offcodepricepercent($offcode->min_amount,$offcode->max_amount,$amount,$offcode->off_amount,$number);
            }else{

                return $this->offcodepricestatic($offcode->min_amount,$amount,$offcode->off_amount,$number);


            }



    }


    public function offcodepricepercent(int $min,int $max,int $amount,int $offamount,$number){

        if($amount<$min){

            return response()->json([
                'message'=>'مبلغ سفارش کمتر از کف اعمال تخفیف است',
                'code'=>'405'
            ],200);

        }


        $off=$amount*$offamount/100;

        if($off>$max){


            if($number){


                return $max;
            }
            return response()->json([
                'data'=> $max,
                'message'=>'تخفیف اعمال شد',
                'code'=>'200'
            ],200);


        

        }else{

            if($number){


                return $off;
            }

            return response()->json([
                'data'=> $off,
                'message'=>'تخفیف اعمال شد',
                'code'=>'200'
            ],200);



        }




    }

    
    public function offcodepricestatic(int $min,int $amount,int $offamount,$number){

        if($amount<$min){

            return response()->json([
                'message'=>'مبلغ سفارش کمتر از کف اعمال تخفیف است',
                'code'=>'405'
            ],200);

        }


        if($number){


            return $offamount;
        }

        return response()->json([
            'data'=> $offamount,
            'code'=>'200'
        ],200);





    }
    

    public function checkoffcodeorder(OffCode $offcode,int $service_id,$number){





    }
}