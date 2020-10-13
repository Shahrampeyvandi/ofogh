<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acounting\Transations;
use App\Models\Acounting\UserAcounts;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Orders\Offer;
use App\Models\Orders\Order;
use App\Models\Personals\Personal;
use App\Models\Rating\SpecialScore;
use App\Models\Rating\WeeklyScore;
use App\Models\Services\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

class OfferController extends Controller
{
    public function offer(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
        $useracount_Worker = $personal->useracounts[0];

        $order = Order::find($request->order);
        $service = Service::find($order->service_id);

        if ($useracount_Worker->cash < $service->service_offered_price) {
            return response()->json([
                'code' => 400,
                'message' => 'موجودی حساب شما جهت پیشنهاد کافی نمی باشد',
                'data' => $service->service_offered_price,
            ], 200);
        }

        $countoff=Offer::where('order_id',$order->id)->where('personal_id',$personal->id)->count();
        if($countoff>0){
            return response()->json([
                'code' => 400,
                'message' => 'پیشنهاد شما قبلا برای این سفارش به ثبت رسیده است',
                'data' => $service->service_offered_price,
            ], 200);
        }

        $offer = new Offer;
        $offer->personal_id = $personal->id;
        $offer->customer_id = $order->customer_id;
        $offer->order_id = $order->id;
        $offer->amount = $request->amount;
        $offer->description = $request->description;

        $datenow = Carbon::today();
        $daysfirst = (int) $request->date;

        if ($daysfirst > 10) {
            if ($daysfirst == 11) {
                $offer->date = $order->order_date_first;
                $offer->time = $order->order_time_first;
            } else {
                $offer->date = $order->order_date_second;
                $offer->time = $order->order_time_second;
            }
        } else {
            if ($daysfirst == 0) {
                $offer->date = $datenow;
            } else {
                $offer->date = $datenow->addDays($daysfirst);
            }
            $offer->time = $request->time;
        }

        $offer->save();

        if ($service->service_offered_price > 0) {
            $role = Role::where('name', $service->service_role)->first();
            $useracount_broker = UserAcounts::where('roles_id', $role->id)->first();

            $transactionporsantb = new Transations();
            $transactionporsantb->user_acounts_id = $useracount_Worker->id;
            $transactionporsantb->type = 'برداشت';
            $transactionporsantb->for = 'ارسال پیشنهاد';
            $transactionporsantb->order_unique_code = $order->order_unique_code;
            $transactionporsantb->amount = $service->service_offered_price;
            $transactionporsantb->from_to = 'به حساب کارگذاری ' . $role->name;
            $transactionporsantb->order_id = $order->id;
            $transactionporsantb->offer_id = $offer->id;
            $transactionporsantb->role_id = $role->id;
            $transactionporsantb->user_account_fromto = $useracount_broker->id;
            $transactionporsantb->save();

            $transactionporsantv = new Transations();
            $transactionporsantv->user_acounts_id = $useracount_broker->id;
            $transactionporsantv->type = 'واریز';
            $transactionporsantv->for = 'ارسال پیشنهاد';
            $transactionporsantv->order_unique_code = $order->order_unique_code;
            $transactionporsantv->amount = $service->service_offered_price;
            $transactionporsantv->from_to = 'از خدمت رسان با شناسه حساب ' . $useracount_Worker->id;
            $transactionporsantv->order_id = $order->id;
            $transactionporsantv->offer_id = $offer->id;
            $transactionporsantv->user_account_fromto = $useracount_Worker->id;
            $transactionporsantv->save();

            $useracount_broker->cash+=$service->service_offered_price;
            $useracount_Worker->cash-=$service->service_offered_price;

            $useracount_broker->expenses+=$service->service_offered_price;
            $useracount_Worker->deductions+=$service->service_offered_price;

            $useracount_broker->transactionsc+=1;
            $useracount_Worker->transactionsc+=1;

            $useracount_broker->update();
            $useracount_Worker->update();
        }

        $firstoffer=Offer::where('order_id',$order->id)->count();
        if($firstoffer<2){
            $this->sendsms($offer->customer->customer_mobile, 'newoffer', $offer->customer->customer_mobile, null, null);
        }

        return response()->json([
            'code' => 200,
            'message' => 'پیشنهاد با موفقیت ثبت گردید',
        ], 200);
    }

    public function acceptoffer(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $offer = Offer::find($request->offer);
        $order = Order::find($offer->order_id);

        $orderoffers = $order->offers;
        foreach ($orderoffers as $orderoffer) {
            if ($orderoffer->id != $offer->id) {
                $orderoffer->accepted = 2;
                $orderoffer->update();
            }
        }

        $order->personal_id = $offer->personal->id;
        $order->order_type = 'شروع نشده';

        $order->offer_id = $offer->id;
        $order->final_date = $offer->date;
        $order->final_time =  $offer->time;

        $offer->accepted = 1;

        $this->sendsms($offer->personal->customer_mobile, 'offeraccepted', $offer->personal->customer_mobile, null, null);


        $offer->update();
        $order->update();

        return response()->json(
            ['code' => 200],
            200
        );
    }

    public function getpersonalprofile(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $personal = Personal::find($request->personal);

        if ($request->offer) {
            $offer = Offer::find($request->offer);
            if ($offer->read) {
                $offer->read=1;
                $offer->update();
            }
        } else {
            $offer = Offer::where('customer_id', $customer->id)->where('personal_id', $personal->id)->where('accepted', 0)->first();
        }

        $personal = $offer->personal;

        $array = [];

        $array['name'] = $personal->personal_firstname . ' ' . $personal->personal_lastname;

        $array['score'] = $personal->score->score;
        $array['avatar'] = $personal->personal_profile;

        $weeklyscore = WeeklyScore::where('personal_id', $personal->id)->latest()->first();

        if ($weeklyscore->special_scores) {
            $specialsocres = explode(',', $weeklyscore->special_scores);
            foreach ($specialsocres as $specialscore) {
                $score = SpecialScore::find($specialscore);

                $spescores[] = $score;
            }

            $array['special_score'] = $spescores;
        }

        $array['history'] = $personal->created_at->diffInMonths(Carbon::now());
        $array['orders'] = Order::where('personal_id', $personal->id)->count();
        $array['phone'] = $personal->personal_mobile;

        $personal_info = [];
        $personal_info['name'] = $personal->personal_firstname . ' ' . $personal->personal_lastname;
        $personal_info['avatar'] = $personal->personal_profile;
        $offer['personal_info'] = $personal_info;


        $today = Carbon::today();
        $datedo=Carbon::parse($offer->date);
        $createdat=Carbon::parse($offer->created_at)->format('Y-m-d 00:00:00');
        $createdat=Carbon::parse($createdat);

        $daydo =$datedo->diffInDays($today);
        $daycre =$createdat->diffInDays($today);

        $offerdate='';
        if ($offer->date>=$today) {
            $offerdate=$this->getdaytitle($daydo, true);
        }
        $offer['date'] = $offerdate.\Morilog\Jalali\Jalalian::forge($offer->date)->format('%A %d %B');


        $offerdate='';
        $offerdate=$this->getdaytitle($daycre, false);
        $offer['offerdate'] = $offerdate.Carbon::parse($offer->created_at)->format('H:i');





        $array['offer'] = $offer;
        //$array['offer']['personal']=[];

        return response()->json($array, 200);
    }

    public function getofferorder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $Code = $request->order;
        $order = Order::find($Code);
        if ($order !== null) {
            $service = Service::where('id', $order->service_id)->first();
            $order['service_name'] = $service->service_title;
            $order['service_price'] = $service->service_price;
            $order['service_desc'] = $service->service_desc;
            $order['offerprice'] = $service->service_offered_price;

            $order['images'] = $order->orderImages;

            $offerbefor = Offer::where('order_id', $order->id)->where('personal_id', $personal->id)->first();

            if ($order->order_type == 'مناقصه') {
                $order['canoffer'] = 1;
            } else {
                $order['canoffer'] = 0;
            }
            if ($offerbefor) {
                $order['canoffer'] = 2;
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

    public function getoffer(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $offer = Offer::where('order_id', $request->order)->first();

        if ($offer->accepted == 0) {
            $status = 'در انتظار بررسی مشتری';
        } elseif ($offer->accepted == 1) {
            $status = 'پذیرفته شده';
        } else {
            $status = 'رد شده';
        }

        $today = Carbon::today();
        $datedo=Carbon::parse($offer->date);
        $createdat=Carbon::parse($offer->created_at)->format('Y-m-d 00:00:00');
        $createdat=Carbon::parse($createdat);

        $daydo =$datedo->diffInDays($today);
        $daycre =$createdat->diffInDays($today);

        $offerdate='';
        if ($offer->date>=$today) {
            $offerdate=$this->getdaytitle($daydo, true);
        }
        $offer['date'] = $offerdate.\Morilog\Jalali\Jalalian::forge($offer->date)->format('%A %d %B');


        $offerdate='';
        $offerdate=$this->getdaytitle($daycre, false);
        $offer['offerdate'] = $offerdate.Carbon::parse($offer->created_at)->format('H:i');

        $offer['status']=$status;

        return response()->json($offer, 200);

        return response()->json([
            'amount' => $offer->amount,
            'date' => $offer->date,
            'time' => $offer->time,
            'created_at' => $offer->created_at,
            'status' => $status,
        ], 200);
    }

    public function getoffersofordre(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        //$order=Order::find($request->order);
        $offers = Offer::where('order_id', $request->order)
        ->orderBy('created_at','desc')
        ->orderBy('electedpersonal', 'desc')
        ->get();

        $today = Carbon::today();

        foreach ($offers as $offer) {
            $personal = Personal::find($offer->personal_id);

            $personal_info = [];
            $personal_info['name'] = $personal->personal_firstname . ' ' . $personal->personal_lastname;
            $personal_info['avatar'] = $personal->personal_profile;

            $offer['personal_info'] = $personal_info;
            // $offer['personal']['avatar']=$personal->personal_profile;

            $offer->date.=' 00:00:00';
            $datedo=Carbon::parse($offer->date);
            $createdat=Carbon::parse($offer->created_at)->format('Y-m-d 00:00:00');
            $createdat=Carbon::parse($createdat);

            $daydo =$datedo->diffInDays($today);
            $daycre =$createdat->diffInDays($today);

            $offerdate='';
            if ($offer->date>=$today) {
                $offerdate=$this->getdaytitle($daydo, true);
            }
            $offer['date'] = $offerdate.\Morilog\Jalali\Jalalian::forge($offer->date)->format('%A %d %B');


            $offerdate='';
            $offerdate=$this->getdaytitle($daycre, false);

            $offer['offerdate'] = $offerdate.Carbon::parse($offer->created_at)->format('H:i');
        }

        //$order['offers']=$offer;

        return response()->json([
            'data' => $offers,
        ], 200);
    }


    public function getdaytitle($number, $future)
    {
        $title='';
        if ($future) {
            switch ($number) {
                case 0:
                    $title='امروز ';
                break;
                case 1:
                    $title='فردا ';
                break;
                case 2:
                    $title='پس فردا ';
                break;
                default:
                $title='';
            }
        } else {
            switch ($number) {
                    case 0:
                        $title='امروز ';
                    break;
                    case 1:
                        $title='دیروز ';
                    break;
                    case 2:
                        $title='پریروز ';
                    break;
                    default:
                    $title=$number. ' روز قبل ';
                }
        }


        return $title;
    }
}
