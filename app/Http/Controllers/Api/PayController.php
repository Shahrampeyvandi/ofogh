<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Acounting\Paytransactions;
use App\Models\Personals\Personal;
use App\Models\Cunsomers\Cunsomer;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Acounting\Transations;
use App\Models\Acounting\UserAcounts;

class PayController extends Controller
{
    public function gettoken(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $token=null;

        $paytr=new Paytransactions;
        $paytr->mobile=$mobile;
        $paytr->expire=Carbon::now()->addMinutes(2);
        $paytr->amount=$request->amount;


        if($request->type == 'customer'){

            $cunsomer=Cunsomer::where('customer_mobile',$mobile)->first();
            $paytr->type='customer';
            $paytr->cunsomer_id=$cunsomer->id;




        }else if($request->type == 'worker'){

            $personal = Personal::where('personal_mobile',$mobile)->first();
            $paytr->type='worker';
            $paytr->personal_id=$personal->id;

        }


        $paytr->token=md5(uniqid($mobile.microtime(), true));



        $paytr->save();
       
     

    return response()->json([
      'code'=>$paytr->token
    ], 200);

    }

    public function incometocharge(Request $request){

        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile',$mobile)->first();
        $amount=$request->amount;
        $chargeuseracount=$personal->useracounts[0];
        $incomeuseracount=$personal->useracounts[1];

        if($amount > 300000){
            return response()->json([
                'code'=>$amount
              ], 400);
        }
        if($amount > $incomeuseracount->cash){
            return response()->json([
                'code'=>$amount
              ], 400);
        }

        $chargeuseracount->cash=$chargeuseracount->cash+$amount;
        $incomeuseracount->cash=$incomeuseracount->cash-$amount;

        $transactioncharge=new Transations;
        $transactionincome=new Transations;

        $transactioncharge->user_acounts_id=$chargeuseracount->id;
        $transactionincome->user_acounts_id=$incomeuseracount->id;

        $transactioncharge->type='واریز';
        $transactionincome->type='برداشت';

        $transactioncharge->method='اعتباری';
        $transactionincome->method='اعتباری';

        $transactioncharge->for='انتقال از درآمد';
        $transactionincome->for='انتقال به شارژ';

        $transactioncharge->amount=$amount;
        $transactionincome->amount=$amount;

        $transactioncharge->from_to='انتقال از حساب درآمد خدمت رسان با ای دی '.$incomeuseracount->id;
        $transactionincome->from_to='انتقال به حساب شارژ خدمت رسان با ای دی '.$chargeuseracount->id;

        $chargeuseracount->update();
        $incomeuseracount->update();

        $transactioncharge->save();
        $transactionincome->save();


        return response()->json([
            'code'=>$incomeuseracount
          ], 200);
      
    }
}
