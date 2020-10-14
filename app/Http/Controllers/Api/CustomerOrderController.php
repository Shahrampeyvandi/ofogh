<?php

namespace App\Http\Controllers\Api;

use App\App\Models\Customers\CustomerAddress;
use App\Http\Controllers\Controller;
use App\Jobs\RefferOrderToPersonal;
use App\Models\Acounting\OffCode;
use App\Models\Acounting\OffCodeUse;
use App\Models\Acounting\Transations;
use App\Models\Acounting\UserAcounts;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Orders\Offer;
use App\Models\Orders\Order;
use App\Models\Personals\Personal;
use App\Models\Services\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Jobs\RefferOrderToElectedPersonal;

class CustomerOrderController extends Controller
{
    public function saveorder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        if ($customer->deptor) {
            return response()->json([
                'code' => 201,
                'message'=>'قبل از ثبت سفارش جدید باید سفارش قبلی خود را تسویه کنید'
            ], 200);
        }
     
        $service = Service::find($request->id);
        if ($service->paytype=='اعتباری') {
            if ($service->service_price) {
                if ($customer->useracounts[0]->cash<$service->service_price) {
                    return response()->json([
                        'code' => 201,
                        'message'=>'حساب شما اعتباری کافی برای ثبت این سفارش را ندارد'
                    ], 200);
                }
            }
        }

        

        $order = new Order;
        $order->service_id = $service->id;
        $order->customer_id = $customer->id;
        $order->order_type = 'معلق';
        $order->order_desc = $request->order_desc;
        $order->order_show_mobile = $customer->customer_mobile;
        $order->order_firstname_customer = $customer->customer_firstname;
        $order->order_lastname_customer = $customer->customer_lastname;
        $order->order_username_customer = $customer->customer_mobile;
        $order->order_broker_name = $service->service_role;

        $datenow = Carbon::today();

        $daysfirst = (int) $request->order_date_first;
        if ($daysfirst == 0) {
            $order->order_date_first = $datenow;
        } else {
            $order->order_date_first = $datenow->addDays($daysfirst);
        }
        $order->order_time_first = $request->order_time_first;

        $datenow2 = Carbon::today();
        if ($request->order_time_second !== 'null') {
            $dayssecond = (int) $request->order_date_second;
            if ($dayssecond == 0) {
                $order->order_date_second = $datenow2;
            } else {
                $order->order_date_second = $datenow2->addDays($dayssecond);
            }
            $order->order_time_second = $request->order_time_second;
        }

        $address = CustomerAddress::find($request->order_address);
        $order->order_city = $address->city;
        $order->order_address = $address->address;
        $order->address_id = $address->id;

        // بخش ثبت کد تخفیف در اطلاعات سفارش
        if (strlen($request->off_code) > 1) {
            $offcodeverify = new OffCodeController();
            $amountoff = $offcodeverify->checkoffcode($request->off_code, $customer->id, 'خدمت', $service->id, 1000000, true);

            if (strlen($amountoff) > 20) {
                return $amountoff;
            }
            $offcode = OffCode::where('code', $request->off_code)
                ->where(function ($query) {
                    $query->where('expiration', '>=', date("Y-m-d"))
                        ->orWhere('expiration', null);
                })
                ->first();

            $offcodeuse = new OffCodeUse;
            $offcodeuse->off_code_id = $offcode->id;
            $offcodeuse->cunsomer_id = $customer->id;
            $offcodeuse->amount = 0;
            $offcodeuse->save();

            $order->off_amount = 0;
            $order->off_code = $offcodeuse->id;
        }

        $order->save();

        if (strlen($request->off_code) > 1) {
            $offcodeuse->order_id = $order->id;

            $offcodeuse->update();
        }

        $date = Carbon::parse($order->order_date_first)->timestamp;
        $Code = $this->generateRandomString($order->order_username_customer, $date, $order->id);

        $order->update([
            'order_unique_code' => $Code,
        ]);

        $useraccount = $customer->useracounts[0];
        $transactions = Transations::where('user_acounts_id', $useraccount->id)->where('type', 'برداشت')->first();
        if (is_null($transactions)) {
            $settings = DB::table('setting')->first();

            $this->sendsms($mobile, 'newcustomeroff', $settings->offfirstporder, null, null);
        }

        if ($service->service_type_send == 'ارجاع اتوماتیک') {
            $job = (new RefferOrderToPersonal($order))->delay(10);
            $this->dispatch($job);

            $jobelected = (new RefferOrderToElectedPersonal($order))->delay(60);
            $this->dispatch($jobelected);
        }

        return response()->json([
            'code' => 200,
            'data' => $order->order_unique_code,
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

        $order = Order::where('order_unique_code', $request->code)->first();

        $file = $request->type . '_' . time() . '.' . $request->image->getClientOriginalExtension();
        $request->image->move(public_path('/uploads/orders/' . $order->id), $file);
        $image_url = 'orders/' . $order->id . '/' . $file;

        $order->orderImages()->create([
            'type' => $request->type,
            'link' => $image_url,
        ]);

        return response()->json(
            'ok',
            200
        );
    }

    public function cancelorder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $order = Order::where('order_unique_code', $request->code)->first();

        $detail = $order->orderDetail;

        if ($detail) {
            $detail->cancel_time = Carbon::now();

            $detail->cancel_reason = 'لغو شده توسط مشتری در مرحله ' . $order->order_type;
            $detail->cancel_level = $order->order_type;
            $detail->canceller = 'مشتری';

            $detail->update();
        } else {
            $order->orderDetail()->create([
                'cancel_time' => Carbon::now(),
                'cancel_reason' => 'لغو شده توسط مشتری در مرحله ' . $order->order_type,
                'cancel_level' => $order->order_type,
                'canceller' => 'مشتری',
            ]);
        }

        if ($order->off_code) {
            $offcodeuse = $order->offcodeuse;
            $offcodeuse->amount = 0;
            $offcodeuse->success = 0;
            $offcodeuse->update();
            $order->off_amount = 0;
        }

        $order->order_type = 'لغو شده';
        $order->update();

        return response()->json(
            'ok',
            200
        );
    }
}
