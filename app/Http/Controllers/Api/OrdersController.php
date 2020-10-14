<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acounting\OffCode;
use App\Models\Acounting\OffCodeUse;
use App\Models\Acounting\Transations;
use App\Models\Acounting\UserAcounts;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Orders\ImageOrders;
use App\Models\Orders\Offer;
use App\Models\Orders\Order;
use App\Models\Personals\Personal;
use App\Models\Services\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Facades\JWTAuth;

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
        $orders1 = $personal->order->where('order_type', 'مناقصه');
        //$orders2 = $personal->order->where('order_type', 'شروع نشده');
        $orderst=[];
        foreach ($orders1 as  $order) {
            $service = Service::where('id', $order->service_id)->first()->service_title;
            $order['service_name'] = $service;

            $offer = Offer::where('personal_id', $personal->id)->where('order_id', $order->id)->first();
            if ($offer) {
                $order['canoffer'] = 2;
            } else {
                $order['canoffer'] = 0;
            }
            $orderst[]=$order;
        }
        return response()->json([
            'data' => $orderst,
        ], 200);
    }

    public function allRelatedOrders(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
        //$orders = $personal->order;
        $orders = Order::where('personal_id', $personal->id)
            ->where(function ($query) {
                $query->where('order_type', 'شروع نشده')
                    ->orWhere('order_type', 'در حال انجام')
                    ->orWhere('order_type', 'انجام شده');
            })
            ->get();

        $order_array = [];
        foreach ($orders as $key => $item) {
            if ($item->order_type == 'تسویه شده') {
                continue;
            }
            if ($item->order_type == 'لغو شده') {
                continue;
            }
            array_push($order_array, $item);
        }
        foreach ($order_array as $key => $order) {
            $service = Service::where('id', $order->service_id)->first()->service_title;
            $order['service_name'] = $service;

            if ($order->final_date) {
                $order->order_time_first = $order->final_time;
                $order->order_date_first = $order->final_date;

                $order->order_time_second = '';
                $order->order_date_second = '';
            } else {
                if ($order->selected_time) {
                    if ($order->selected_time == 1) {

                        // $order->order_time_first='';
                        // $order->order_date_first='';

                        $order->order_time_second = '';
                        $order->order_date_second = '';
                    } else {
                        $order->order_time_first = $order->order_time_second;
                        $order->order_date_first = $order->order_date_second;

                        $order->order_time_second = '';
                        $order->order_date_second = '';
                    }
                }
            }
            // if (count($order->orderImages)) {
            //   foreach ($order->orderImages as $key => $image) {
            //     if($image->image_type == 'faktor') $order['faktor'] = $image->image_url;
            //     if($image->image_type == 'image1') $order['image1'] = $image->image_url;
            //     if($image->image_type == 'image2') $order['image2'] = $image->image_url;
            //     if($image->image_type == 'image3') $order['image3'] = $image->image_url;

            //   }
            // }

            // if ($order->orderDetail) {

            //   $cost=$order->orderDetail->order_recived_price+$order->orderDetail->order_pieces_cast;
            //   $order['cost'] = $cost;
            //   $order['endtime'] = $order->orderDetail->order_end_time;

            //   $cunsomeracount= Cunsomer::where('customer_mobile',$order->order_username_customer)->first()->useracounts[0]->cash;

            //   if($cunsomeracount>=$cost){
            //     $order['cunsomer_charge'] = $cost;
            //   }else{
            //     $order['cunsomer_charge'] = $cunsomeracount;
            //   }
            // }
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
        $orders = Order::where('personal_id', $personal->id)
            ->where(function ($query) {
                $query->where('order_type', 'تسویه شده')
                    ->orWhere('order_type', 'لغو شده');
            })
            ->get();
        // $orders = $personal->order->where('order_type', 'تسویه شده');
        //$ordersl = $personal->order->where('order_type', 'لغو شده');

        foreach ($orders as $key => $order) {
            $service = Service::where('id', $order->service_id)->first()->service_title;
            $order['service_name'] = $service;
            if ($order->final_date) {
                $order->order_time_first = $order->final_time;
                $order->order_date_first = $order->final_date;

                $order->order_time_second = '';
                $order->order_date_second = '';
            } else {
                if ($order->selected_time) {
                    if ($order->selected_time == 1) {

                        // $order->order_time_first='';
                        // $order->order_date_first='';

                        $order->order_time_second = '';
                        $order->order_date_second = '';
                    } else {
                        $order->order_time_first = $order->order_time_second;
                        $order->order_date_first = $order->order_date_second;

                        $order->order_time_second = '';
                        $order->order_date_second = '';
                    }
                }
            }
        }
        // foreach ($ordersl as $key => $orderl) {
        //     $service = Service::where('id', $orderl->service_id)->first()->service_title;
        //     $orderl['service_name'] = $service;
        //     if ($orderl->selected_time == 1) {

        //         // $order->order_time_first='';
        //         // $order->order_date_first='';

        //         $orderl->order_time_second = '';
        //         $orderl->order_date_second = '';

        //     } else {

        //         $orderl->order_time_first = $order->order_time_second;
        //         $orderl->order_date_first = $order->order_date_second;

        //         $orderl->order_time_second = '';
        //         $orderl->order_date_second = '';

        //     }
        // }

        $ords = [];
        foreach ($orders as $key => $or) {
            $ords[] = $or;
        }
        // foreach ($ordersl as $key => $orl) {
        //     $ords[] = $orl;
        // }
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

            if ($order->final_date) {
                $order->order_time_first = $order->final_time;
                $order->order_date_first = $order->final_date;

                $order->order_time_second = '';
                $order->order_date_second = '';
            } else {
                if ($order->selected_time) {
                    if ($order->selected_time == 1) {

                        // $order->order_time_first='';
                        // $order->order_date_first='';

                        $order->order_time_second = '';
                        $order->order_date_second = '';
                    } else {
                        $order->order_time_first = $order->order_time_second;
                        $order->order_date_first = $order->order_date_second;

                        $order->order_time_second = '';
                        $order->order_date_second = '';
                    }
                }
            }

            if (count($order->orderImages)) {
                foreach ($order->orderImages as $key => $image) {
                    if ($image->image_type == 'faktor') {
                        $order['faktor'] = $image->image_url;
                    }

                    if ($image->image_type == 'image1') {
                        $order['image1'] = $image->image_url;
                    }

                    if ($image->image_type == 'image2') {
                        $order['image2'] = $image->image_url;
                    }

                    if ($image->image_type == 'image3') {
                        $order['image3'] = $image->image_url;
                    }
                }
            }

            if ($order->orderDetail) {
                $cost = $order->orderDetail->order_recived_price + $order->orderDetail->order_pieces_cast;
                $order['cost'] = $cost;
                $order['endtime'] = $order->orderDetail->order_end_time;
                $order->creditamount += $order->off_amount;

                if ($order->order_type == 'انجام شده') {
                    $cunsomeracount = Cunsomer::where('customer_mobile', $order->order_username_customer)->first()->useracounts[0]->cash;

                    if (!is_null($order->orderDetail->order_recived_price)) {
                        $cunsomeracount += $order->off_amount;
                        $cost = $order->orderDetail->order_recived_price + $order->orderDetail->order_pieces_cast;

                        if ($cunsomeracount >= $cost) {
                            $order->paytype = 'اعتباری';

                            $order->creditamount = $cost;
                            $order->cashamount = 0;

                            // جهت نسخه های قدیمی
                            $order['cunsomer_charge'] = $cost;
                        } else {
                            if ($order->off_amount > 0) {
                                if ($order->offcodeuse->offcode->dolaw == 3) {
                                    $cunsomeracount -= $order->off_amount;
                                }
                            }

                            $order['cunsomer_charge'] = $cunsomeracount;

                            if ($cunsomeracount > 0) {
                                $order->paytype = 'نقدی-اعتباری';

                                $order->creditamount = $cunsomeracount;
                                $order->cashamount = $cost - $cunsomeracount;
                            } else {
                                $order->paytype = 'نقدی';

                                $order->creditamount = 0;
                                $order->cashamount = $cost;
                            }
                        }
                    }
                }
            }

            $order['images'] = $order->orderImages;

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
            'order_type' => 'شروع نشده',
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
                'code' => '202',
                'message' => 'درخواست شروع به کار توسط شما ثبت شده است',
            ], 200);
        }
        Order::where('order_unique_code', $Code)->update([
            'order_type' => 'در حال انجام',
        ]);
        $order = Order::where('order_unique_code', $Code)->first();

        $positions[] = $request->positionslat;
        $positions[] = $request->positionslon;

        $order->orderDetail()->create([
            'order_start_time' => Carbon::now(),
            'order_start_description' => $request->description,
            'order_start_time_positions' => serialize($positions),
        ]);

        if ($order->orderDetail) {
            $cost = $order->orderDetail->order_recived_price + $order->orderDetail->order_pieces_cast;
            $order['cost'] = $cost;
            $order['endtime'] = $order->orderDetail->order_end_time;

            $cunsomeracount = Cunsomer::where('customer_mobile', $order->order_username_customer)->first()->useracounts[0]->cash;

            if ($cunsomeracount >= $cost) {
                $order['cunsomer_charge'] = $cost;
            } else {
                $order['cunsomer_charge'] = $cunsomeracount;
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'شروع به کار انجام گردید',
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
                'code' => '400',
                'message' => 'درخواست پایان کار توسط شما ثبت شده است',
            ], 404);
        }

        $order = Order::where('order_unique_code', $Code)->first();
        $order->order_type = 'انجام شده';

        $positions[] = $request->positionslat;
        $positions[] = $request->positionslon;

        $order->orderDetail()->update([
            'order_end_time' => Carbon::now(),
            'order_end_time_description' => $request->description,
            'order_end_time_positions' => serialize($positions),
            'order_recived_price' => $request->order_cast,
            'order_pieces_cast' => $request->pieces_cast,

        ]);

        if ($order->off_code) {
            $offcodeverify = new OffCodeController();
            $offcodeuse = $order->offcodeuse;
            $amountoff = $offcodeverify->checkoffcodegoodsorder($offcodeuse->offcode, $order->service_id, $request->order_cast, $order->customer_id, 'خدمت', true);

            if (strlen($amountoff) > 20) {
                $amountoff = 0;
            }
            $offcodeuse->amount = $amountoff;
            $offcodeuse->update();
            $order->off_amount = $amountoff;
        }

        $order->update();

        $customer = Cunsomer::find($order->customer_id);
        $customer->deptor=1;
        $customer->update();

        if ($order->orderDetail) {
            $cost = $order->orderDetail->order_recived_price + $order->orderDetail->order_pieces_cast;
            $order['cost'] = $cost;
            $order['endtime'] = $order->orderDetail->order_end_time;

            $cunsomeracount = Cunsomer::where('customer_mobile', $order->order_username_customer)->first()->useracounts[0]->cash;

            if ($cunsomeracount >= $cost) {
                $order['cunsomer_charge'] = $cost;
            } else {
                $order['cunsomer_charge'] = $cunsomeracount;
            }
        }

        return response()->json([
            'code' => 200,
            'message' => 'پایان کار با موفقیت انجام گردید',
        ], 200);

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

        //if ($imageCode == '4') $name = 'faktor';
        //if ($imageCode == '1') $name = 'image1';
        //if ($imageCode == '2') $name = 'image2';
        //if ($imageCode == '3') $name = 'image3';

        $file = $imageCode . '_' . time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('/uploads/orders/' . $orderdata->id), $file);
        $image_url = 'orders/' . $orderdata->id . '/' . $file;

        $orderimage = ImageOrders::where('order_id', $orderdata->id)->where('type', $imageCode)->first();

        if ($orderimage) {
            File::delete(public_path() . '/uploads/' . $orderimage->link);

            $orderimage->link = $image_url;
            $orderimage->update();
        } else {
            $orderdata->orderImages()->create([
                'type' => $imageCode,
                'link' => $image_url,
            ]);
        }

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
        $cunsomer = Cunsomer::where('customer_mobile', $orderdata->order_username_customer)->first();

        if (Order::where('order_unique_code', $Code)
            ->where('order_type', 'تسویه شده')
            ->whereHas('personals', function ($q) use ($personal) {
                $q->where('id', $personal->id);
            })
            ->count()
        ) {
            return response()->json([
                'code' => 200,
                'message' => 'درخواست تسویه حساب توسط شما ثبت شده است',
            ], 404);
        }


        $cost = $orderdata->orderDetail->order_recived_price + $orderdata->orderDetail->order_pieces_cast;

        $useracount_Worker = $personal->useracounts[1];
        $useracount_Customer = $cunsomer->useracounts[0];
        $useracountcustomerwithoff = $cunsomer->useracounts[0]->cash;

        $useracountcustomerwithoff += $orderdata->off_amount;

        if ($useracountcustomerwithoff >= $cost) {
            $orderdata->paytype = 'اعتباری';

            $orderdata->creditamount = $cost - $orderdata->off_amount;
            $orderdata->cashamount = 0;
        } else {
            if ($orderdata->off_amount > 0) {
                if ($orderdata->offcodeuse->offcode->dolaw == 3) {
                    $useracountcustomerwithoff -= $orderdata->off_amount;
                    $orderdata->off_amount = 0;
                }
            }

            if ($useracountcustomerwithoff > 0) {
                $orderdata->paytype = 'نقدی-اعتباری';

                $orderdata->creditamount = $useracount_Customer->cash;
                $orderdata->cashamount = $cost - $useracountcustomerwithoff;
            } else {
                $orderdata->paytype = 'نقدی';

                $orderdata->creditamount = 0;
                $orderdata->cashamount = $cost - $orderdata->off_amount;
            }
        }

        if ($cost == 0) {
            $orderdata->order_type = 'تسویه شده';
            $orderdata->update();

            return response()->json([
                'code' => 200,
                'message' => 'درخواست تسویه با موفقیت انجام شد',
            ], 200);
        }


        $serviceorder = $orderdata->relatedService;
        if($serviceorder->paytype=='اعتباری'){
            if($orderdata->paytype!='اعتباری'){
                return response()->json([
                    'code' => 201,
                    'message' => 'شیوه پرداخت این سفارش اعتباری است اما حساب مشتری شارژ کافی ندارد',
                ], 200);
            }
        }
        if ($serviceorder->twosteppay==0) {
            $orderdata->orderDetail()->update([
                'checkout_status' => 1,
            ]);
        
      

            //--------------تراکنش های پورسانت خدمت
            $porsant = 0;
            if ($serviceorder->service_percentage > 0) {
                $role = Role::where('name', $serviceorder->service_role)->first();
                $useracount_broker = UserAcounts::where('roles_id', $role->id)->first();

                $porsant = ($orderdata->orderDetail->order_recived_price) * ($serviceorder->service_percentage) / 100;

                $transactionporsantb = new Transations();
                $transactionporsantb->user_acounts_id = $useracount_Worker->id;
                $transactionporsantb->type = 'برداشت';
                $transactionporsantb->for = 'پورسانت خدمت';
                $transactionporsantb->order_unique_code = $orderdata->order_unique_code;
                $transactionporsantb->amount = $porsant;
                $transactionporsantb->from_to = 'به حساب کارگذاری ' . $role->name;
                $transactionporsantb->order_id = $orderdata->id;
                $transactionporsantb->role_id = $role->id;
                $transactionporsantb->save();

                $transactionporsantv = new Transations();
                $transactionporsantv->user_acounts_id = $useracount_broker->id;
                $transactionporsantv->type = 'واریز';
                $transactionporsantv->for = 'پورسانت خدمت';
                $transactionporsantv->order_unique_code = $orderdata->order_unique_code;
                $transactionporsantv->amount = $porsant;
                $transactionporsantv->from_to = 'از خدمت رسان با شناسه حساب ' . $useracount_Worker->id;
                $transactionporsantv->order_id = $orderdata->id;
                $transactionporsantv->user_account_fromto = $useracount_Worker->id;
                $transactionporsantv->save();

                $useracount_Worker->cash -= $porsant;
                $useracount_broker->cash += $porsant;

                $useracount_broker->expenses += $porsant;
                $useracount_Worker->deductions += $porsant;

                $useracount_broker->transactionsc += 1;
                $useracount_Worker->transactionsc += 1;

                $useracount_broker->update();
                $useracount_Worker->update();
            }
            //--------------------------------------

            //----------تراکنش های کسورات قانونی
            if ($serviceorder->legal_deductions > 0) {
                $legal = ($orderdata->orderDetail->order_recived_price - $porsant) * ($serviceorder->legal_deductions) / 100;

                $useracountlegal = UserAcounts::where('personal_id', $personal->id)->where('type', 'کسورات قانونی')->first();

                if (is_null($useracountlegal)) {
                    $useracountlegal = new UserAcounts;
                    $useracountlegal->personal_id = $personal->id;

                    $useracountlegal->user = 'خدمت رسان';
                    $useracountlegal->type = 'کسورات قانونی';
                    $useracountlegal->cash = $legal;
                    $useracountlegal->expenses = $legal;
                    $useracountlegal->deductions = 0;
                    $useracountlegal->transactionsc = 1;

                    $useracountlegal->save();
                } else {
                    $useracountlegal->cash += $legal;
                    $useracountlegal->expenses += $legal;
                    $useracountlegal->transactionsc += 1;
                    $useracountlegal->update();
                }

                $transactionporsantb = new Transations();
                $transactionporsantb->user_acounts_id = $useracount_Worker->id;
                $transactionporsantb->type = 'برداشت';
                $transactionporsantb->for = 'کسورات قانونی';
                $transactionporsantb->order_unique_code = $orderdata->order_unique_code;
                $transactionporsantb->amount = $legal;
                $transactionporsantb->from_to = 'به حساب کسورات قانونی خدمت رسان به شناسه ' . $useracountlegal->id;
                $transactionporsantb->order_id = $orderdata->id;
                $transactionporsantb->user_account_fromto = $useracountlegal->id;
                $transactionporsantb->save();

                $transactionporsantv = new Transations();
                $transactionporsantv->user_acounts_id = $useracountlegal->id;
                $transactionporsantv->type = 'واریز';
                $transactionporsantv->for = 'کسورات قانونی';
                $transactionporsantv->order_unique_code = $orderdata->order_unique_code;
                $transactionporsantv->amount = $legal;
                $transactionporsantv->from_to = 'از حساب درآمد خدمت رسان با شناسه  ' . $useracount_Worker->id;
                $transactionporsantv->order_id = $orderdata->id;
                $transactionporsantv->user_account_fromto = $useracount_Worker->id;
                $transactionporsantv->save();

                $useracount_Worker->update();
            }
            //-----------------------------------
        }

        if ($orderdata->off_amount > 0) {
            $useracount_broker = UserAcounts::where('roles_id', 1)->first();
            $offcode = OffCodeUse::find($orderdata->off_code);

            $transactiongiftv = new Transations();
            $transactiongiftv->user_acounts_id = $useracount_broker->id;
            $transactiongiftv->type = 'برداشت';
            $transactiongiftv->for = 'کد تخفیف';
            $transactiongiftv->order_unique_code = $orderdata->order_unique_code;
            $transactiongiftv->amount = $orderdata->off_amount;
            $transactiongiftv->from_to = 'کد تخفیف  ' . $offcode->offcode->code . 'به مشتری با شناسه حساب ' . $useracount_Customer->id;

            $transactiongiftv->order_id = $orderdata->id;
            $transactiongiftv->user_account_fromto = $useracount_Customer->id;
            $transactiongiftv->off_code_use_id = $offcode->id;

            $useracount_broker->cash -= $orderdata->off_amount;

            $useracount_broker->deductions += $orderdata->off_amount;

            $useracount_broker->transactionsc += 1;

            $transactiongiftv->save();
            $useracount_broker->update();
        }

        //--------------------------------------


        if ($orderdata->paytype == 'اعتباری') {
            $transactionbardasht = new Transations();
            $transactionbardasht->user_acounts_id = $useracount_Customer->id;
            $transactionbardasht->type = 'برداشت';
            $transactionbardasht->for = 'هزینه سفارش';
            $transactionbardasht->order_unique_code = $orderdata->order_unique_code;
            $transactionbardasht->amount = $orderdata->creditamount;
            $transactionbardasht->from_to = 'به حساب خدمت رسان با شناسه ' . $useracount_Worker->id;
            $transactionbardasht->order_id = $orderdata->id;
            $transactionbardasht->user_account_fromto = $useracount_Worker->id;
            $transactionbardasht->save();

            $useracount_Customer->cash = $useracount_Customer->cash - $orderdata->creditamount;
            $useracount_Customer->deductions += $orderdata->creditamount;
            $useracount_Customer->transactionsc += 1;


            if ($serviceorder->twosteppay==0) {
                $transactionvariz = new Transations();
                $transactionvariz->user_acounts_id = $useracount_Worker->id;
                $transactionvariz->type = 'واریز';
                $transactionvariz->for = 'انجام سفارش';
                $transactionvariz->order_unique_code = $orderdata->order_unique_code;
                $transactionvariz->amount = $cost;
                $transactionvariz->from_to = 'از حساب مشتری با شناسه ' . $useracount_Customer->id;
                $transactionvariz->order_id = $orderdata->id;
                $transactionvariz->user_account_fromto = $useracount_Customer->id;
                $transactionvariz->save();

                $useracount_Worker->cash = $useracount_Worker->cash + $cost;
                $useracount_Worker->expenses += $cost;
                $useracount_Worker->transactionsc += 1;
                $useracount_Worker->update();
            }



            $transactions = Transations::where('user_acounts_id', $useracount_Customer->id)->where('type', 'برداشت')->first();
            if (is_null($transactions)) {
                if ($cost > 0) {
                    $service = Service::find($orderdata->service_id);
                    $settings = DB::table('setting')->first();

                    //$this->sendsms($mobile,'newcustomeroff',$settings->offfirstporder,null,null);

                    $amountgift = ($orderdata->orderDetail->order_recived_price) * ($settings->offfirstporder) / 100;

                    $transactiongift = new Transations();
                    $transactiongift->user_acounts_id = $useracount_Customer->id;
                    $transactiongift->type = 'واریز';
                    $transactiongift->for = 'شارژ هدیه';
                    $transactiongift->order_unique_code = $orderdata->order_unique_code;
                    $transactiongift->amount = $amountgift;
                    $transactiongift->from_to = 'هدیه اولین سفارش نام خدمت ' . $service->service_title . 'نام کارگذاری ' . $service->service_role;

                    $transactiongift->order_id = $orderdata->id;

                    $transactiongiftv = new Transations();
                    $transactiongiftv->user_acounts_id = $useracount_broker->id;
                    $transactiongiftv->type = 'برداشت';
                    $transactiongiftv->for = 'شارژ هدیه';
                    $transactiongiftv->order_unique_code = $orderdata->order_unique_code;
                    $transactiongiftv->amount = $amountgift;
                    $transactiongiftv->from_to = 'هدیه اولین سفارش نام خدمت ' . $service->service_title . 'به مشتری با شناسه حساب ' . $useracount_Customer->id;

                    $transactiongiftv->order_id = $orderdata->id;
                    $transactiongiftv->user_account_fromto = $useracount_Customer->id;

                    $useracount_broker->cash -= $amountgift;

                    $transactiongift->save();
                    $transactiongiftv->save();

                    $useracount_Customer->expenses += $amountgift;
                    $useracount_broker->deductions += $amountgift;

                    $useracount_Customer->transactionsc += 1;
                    $useracount_broker->transactionsc += 1;

                    $useracount_Customer->cash = $useracount_Customer->cash + $amountgift;

                    $useracount_broker->update();

                    $this->sendsms($cunsomer->customer_mobile, 'freechargeafterorder', $amountgift, null, null);
                }
            }

            $useracount_Customer->update();
        } elseif ($orderdata->paytype == 'نقدی-اعتباری') {
            $transactionbardasht = new Transations();
            $transactionbardasht->user_acounts_id = $useracount_Customer->id;
            $transactionbardasht->type = 'برداشت';
            $transactionbardasht->for = 'هزینه سفارش';
            $transactionbardasht->order_unique_code = $orderdata->order_unique_code;
            $transactionbardasht->amount = $orderdata->creditamount;
            $transactionbardasht->from_to = 'به حساب خدمت رسان با شناسه ' . $useracount_Worker->id;
            $transactionbardasht->order_id = $orderdata->id;
            $transactionbardasht->user_account_fromto = $useracount_Worker->id;
            $transactionbardasht->save();

            $useracount_Customer->deductions += $orderdata->creditamount;
            $useracount_Customer->transactionsc += 1;
            $useracount_Customer->cash = $useracount_Customer->cash - $orderdata->creditamount;
            $useracount_Customer->update();


            if ($serviceorder->twosteppay==0) {
                $transactionvariz = new Transations();
                $transactionvariz->user_acounts_id = $useracount_Worker->id;
                $transactionvariz->type = 'واریز';
                $transactionvariz->for = 'انجام سفارش';
                $transactionvariz->order_unique_code = $orderdata->order_unique_code;
                $transactionvariz->amount = $useracountcustomerwithoff;
                $transactionvariz->from_to = 'از حساب مشتری با شناسه ' . $useracount_Customer->id;
                $transactionvariz->order_id = $orderdata->id;
                $transactionvariz->user_account_fromto = $useracount_Customer->id;
                $transactionvariz->save();

                $useracount_Worker->expenses += $useracountcustomerwithoff;
                $useracount_Worker->transactionsc += 1;
                $useracount_Worker->cash = $useracount_Worker->cash + $useracount_Customer->cash;
                $useracount_Worker->update();
            }

            //تراکنش های نقدی
            // $naghd=$cost-$useracount_Customer->cash;

            $transactionbardashtnaghd = new Transations();
            $transactionbardashtnaghd->user_acounts_id = $useracount_Customer->id;
            $transactionbardashtnaghd->type = 'برداشت';
            $transactionbardashtnaghd->method = 'نقدی';
            $transactionbardashtnaghd->for = 'هزینه سفارش';
            $transactionbardashtnaghd->order_unique_code = $orderdata->order_unique_code;
            $transactionbardashtnaghd->amount = $orderdata->cashamount;
            $transactionbardashtnaghd->from_to = 'به صورت نقدی به حساب خدمت رسان با شناسه ' . $useracount_Worker->id;
            $transactionbardashtnaghd->description = 'این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
            $transactionbardashtnaghd->order_id = $orderdata->id;
            $transactionbardashtnaghd->user_account_fromto = $useracount_Worker->id;
            $transactionbardashtnaghd->save();

            if ($serviceorder->twosteppay==0) {
                $transactionvariznaghd = new Transations();
                $transactionvariznaghd->user_acounts_id = $useracount_Worker->id;
                $transactionvariznaghd->type = 'واریز';
                $transactionvariznaghd->method = 'نقدی';
                $transactionvariznaghd->for = 'انجام سفارش';
                $transactionvariznaghd->order_unique_code = $orderdata->order_unique_code;
                $transactionvariznaghd->amount = $orderdata->cashamount;
                $transactionvariznaghd->from_to = 'به صورت نقد از حساب مشتری با شناسه' . $useracount_Customer->id;
                $transactionvariznaghd->description = 'این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
                $transactionvariznaghd->order_id = $orderdata->id;
                $transactionvariznaghd->user_account_fromto = $useracount_Customer->id;
                $transactionvariznaghd->save();
            }
        } else {


            //تراکنش های نقدی
            $transactionbardashtnaghd = new Transations();
            $transactionbardashtnaghd->user_acounts_id = $useracount_Customer->id;
            $transactionbardashtnaghd->type = 'برداشت';
            $transactionbardashtnaghd->method = 'نقدی';
            $transactionbardashtnaghd->for = 'هزینه سفارش';
            $transactionbardashtnaghd->order_unique_code = $orderdata->order_unique_code;
            $transactionbardashtnaghd->amount = $orderdata->cashamount;
            $transactionbardashtnaghd->from_to = 'به صورت نقدی به حساب خدمت رسان با شناسه ' . $useracount_Worker->id;
            $transactionbardashtnaghd->description = 'این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
            $transactionbardashtnaghd->order_id = $orderdata->id;
            $transactionbardashtnaghd->user_account_fromto = $useracount_Worker->id;
            $transactionbardashtnaghd->save();


            if ($serviceorder->twosteppay==0) {
                $transactionvariznaghd = new Transations();
                $transactionvariznaghd->user_acounts_id = $useracount_Worker->id;
                $transactionvariznaghd->type = 'واریز';
                $transactionvariznaghd->method = 'نقدی';
                $transactionvariznaghd->for = 'انجام سفارش';
                $transactionvariznaghd->order_unique_code = $orderdata->order_unique_code;
                $transactionvariznaghd->amount = $orderdata->cashamount;
                $transactionvariznaghd->from_to = 'به صورت نقد از حساب مشتری با شناسه' . $useracount_Customer->id;
                $transactionvariznaghd->description = 'این تراکنش به صورت نقدی و بدون اعمال در حساب مشتری ثبت گردید';
                $transactionvariznaghd->order_id = $orderdata->id;
                $transactionvariznaghd->user_account_fromto = $useracount_Customer->id;
                $transactionvariznaghd->save();
            }
        }

        $orderdata->order_type = 'تسویه شده';
        $orderdata->update();

        $customer = Cunsomer::find($orderdata->customer_id);
        $customer->deptor=0;
        $customer->update();

        return response()->json([
            'code' => 200,
            'message' => 'دریافت هزینه با موفقیت انجام گردید',
        ], 200);
    }

    public function ordersofday(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $datenow = Carbon::today();
        $days = (int) $request->days;
        if ($days > 0) {
            $datenow = $datenow->addDays($days);
        }

        $date = (string) $datenow;

        $orders = $personal->order;
        $order_array = [];
        foreach ($orders as $key => $item) {
            if ($item->order_type == 'تسویه شده') {
                continue;
            }
            if ($item->order_type == 'لغو شده') {
                continue;
            }
            if ($item->selected_number == 1) {
                if ($item->order_date_first == $date) {
                    $item->order_time_second = '';
                    $item->order_date_second = '';

                    array_push($order_array, $item);
                }
            } else {
                if ($item->order_date_second == $date) {
                    $item->order_time_first = $order->order_time_second;
                    $item->order_date_first = $order->order_date_second;

                    $item->order_time_second = '';
                    $item->order_date_second = '';

                    array_push($order_array, $item);
                }
            }
        }
        foreach ($order_array as $key => $order) {
            $service = Service::where('id', $order->service_id)->first()->service_title;
            $order['service_name'] = $service;
        }

        return response()->json([
            'data' => $order_array,
        ], 200);
    }
}
