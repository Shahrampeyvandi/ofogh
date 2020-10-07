<?php

namespace App\Http\Controllers\User;

use App\App\Models\Customers\CustomerAddress;
use App\Models\User;
use App\Models\Orders\Order;
use App\Models\Acounting\UserAcounts;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\Services\Service;
use App\Models\Cunsomers\Cunsomer;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Personals\Personal;
use App\Models\Services\ServiceCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class OrderController extends Controller
{


    public function OrderList()
    {

        if (auth()->user()->hasRole('admin_panel')) {
            $orders = Order::latest()->get();
            $services = Service::all();
            $category_parent_list = ServiceCategory::where('category_parent', 0)->get();
            $count = ServiceCategory::where('category_parent', 0)->count();
            $list = '<option data-parent="0" value="0" class="level-1">بدون دسته بندی</option>';
            foreach ($category_parent_list as $key => $item) {

                $list .= '<option data-id="' . $item->id . '" value="' . $item->id . '" class="level-1">' . $item->category_title . ' 
             ' . (count(ServiceCategory::where('category_parent', $item->id)->get()) ? '&#xf104;  ' : '') . '
            </option>';
                if (ServiceCategory::where('category_parent', $item->id)->count()) {
                    $count += ServiceCategory::where('category_parent', $item->id)->count();
                    foreach (ServiceCategory::where('category_parent', $item->id)->get() as $key1 => $itemlevel1) {
                        $list .= '<option data-parent="' . $item->id . '" value="' . $itemlevel1->id . '" class="level-2">' . $itemlevel1->category_title . '
                 ' . (count(ServiceCategory::where('category_parent', $itemlevel1->id)->get()) ? '&#xf104;  ' : '') . '
                 </option>';


                        if (ServiceCategory::where('category_parent', $itemlevel1->id)->count()) {
                            $count += ServiceCategory::where('category_parent', $itemlevel1->id)->count();
                            foreach (ServiceCategory::where('category_parent', $itemlevel1->id)->get() as $key2 => $itemlevel2) {
                                $list .= '<option data-parent="' . $itemlevel1->id . '" value="' . $itemlevel2->id . '" class="level-3">' . $itemlevel2->category_title . '
                     ' . (count(ServiceCategory::where('category_parent', $itemlevel2->id)->get()) ? '&#xf104;  ' : '') . '
                     </option>';


                                if (ServiceCategory::where('category_parent', $itemlevel2->id)->count()) {
                                    $count += ServiceCategory::where('category_parent', $itemlevel2->id)->count();
                                    foreach (ServiceCategory::where('category_parent', $itemlevel2->id)->get() as $key3 => $itemlevel3) {
                                        $list .= '<option data-parent="' . $itemlevel2->id . '" value="' . $itemlevel3->id . '" class="level-4">' . $itemlevel3->category_title . '
                         ' . (count(ServiceCategory::where('category_parent', $itemlevel3->id)->get()) ? '&#xf104;  ' : '') . '
                         </option>';

                                        if (ServiceCategory::where('category_parent', $itemlevel3->id)->count()) {
                                            $count += ServiceCategory::where('category_parent', $itemlevel3->id)->count();
                                            foreach (ServiceCategory::where('category_parent', $itemlevel3->id)->get() as $key4 => $itemlevel4) {
                                                $list .= '<option data-parent="' . $itemlevel3->id . '" value="' . $itemlevel4->id . '" class="level-4">' . $itemlevel4->category_title . '
                                 
                                 </option>';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $category_parent_list = ServiceCategory::where('category_parent', 0)->get();
            $count = ServiceCategory::where('category_parent', 0)->count();
            $list = '<option data-parent="0" value="0" class="level-1">بدون دسته بندی</option>';
            foreach ($category_parent_list as $key => $item) {
                $list .= '<option data-id="' . $item->id . '" value="' . $item->id . '" class="level-1">' . $item->category_title . ' 
             ' . (count(ServiceCategory::where('category_parent', $item->id)->get()) ? '&#xf104;  ' : '') . '
            </option>';
                foreach (ServiceCategory::where('category_parent', $item->id)->get() as $key => $subitem) {
                    $list .= '<option data-parent="' . $item->id . '" value="' . $subitem->id . '" class="level-2">' . $subitem->category_title . '</option>';
                }
            }
            if (auth()->user()->roles->first()->broker == 1) {
                $services = Service::where('service_role', auth()->user()->roles->first()->name)->get();
                $service_array =   Service::where('service_role', auth()->user()->roles->first()->name)->pluck('id')->toArray();
                $orders = Order::whereIn('service_id', $service_array)->get();
              
            }
            if (auth()->user()->roles->first()->sub_broker !== null) {
                $role_name = Role::where('id', auth()->user()->roles->first()->sub_broker)->name;
                $services = Service::where('service_role', $role_name)->get();
                $service_array = Service::where('service_role', $role_name)->pluck('id')->toArray();
                $orders = Order::whereIn('service_id', $service_array)->get();
            }
        }
        return view('User.Orders.OrderList', compact(['orders', 'services', 'list', 'count']));
    }

    public function SubmitOrder(Request $request)
    {
        

        if ($request->new_address == null and $request->user_address == null) {
            alert()->error('ادرس وارد نشده است', 'خطا')->autoclose(2000);
            return back();
        }
        $count = 0;
        if (strlen(implode($request->service_name)) == 0) {
            alert()->error('خدمت مورد نظر را انتخاب نمایید', 'خطا')->autoclose(2000);
            return back();
        }
        foreach ($request->category as $key => $item) {
            if ($request->service_name[$key] == null) {
                continue;
            } else {
                if ($request->new_address !== null) {
                    $address = $request->new_address;
                } else {
                    $address = $request->user_address;
                }


                $count += 1;
                $order = Order::create([
                    'service_id' => $request->service_name[$key],
                    'customer_id' => 0,
                    'order_type' => 'معلق',
                    'order_desc' => $request->user_desc,
                    'order_show_mobile' => $request->user_mobile,
                    'order_city' => $request->user_city,
                    'order_firstname_customer' => $request->user_name,
                    'order_lastname_customer' => $request->user_family,
                    'order_username_customer' => $request->user_mobile,
                    'order_broker_name' => 'zitco',
                    'order_time_first' => $request->time_one[$key],
                    'order_time_second' => $request->time_two[$key],
                    'order_date_first' => $request->date_one[$key] !== null ? $this->convertDate($request->date_one[$key]) : '',
                    'order_date_second' => $request->time_two[$key] !== null && $request->date_two[$key] !== null ?  $this->convertDate($request->date_two[$key]) : '',
                    'order_address' => $address
                ]);

                $date = Carbon::parse($order->order_date_first)->timestamp;
                $Code = $this->generateRandomString($order->order_username_customer, $date, $order->id);

                $order->update([
                    'order_unique_code' => $Code
                ]);
            }
        }



        if (auth()->user()->roles->first()->broker !== null) {
            $broker_id = auth()->user()->id;
        }
        if (auth()->user()->roles->first()->sub_broker !== null) {
            $role_id = auth()->user()->roles->first()->sub_broker;
            $broker_id =  User::whereHas('roles', function ($q) use ($role_id) {
                $q->where('id', $role_id);
            })->first()->id;
        }

        $check_in_customers = Cunsomer::where('customer_mobile', $request->user_mobile)->get();
        if (count($check_in_customers) == 0) {
            $customer = Cunsomer::create([
                'customer_firstname' => $request->user_name,
                'customer_lastname' => $request->user_family,
                'customer_mobile' => $request->user_mobile,
                'customer_status' => 1
            ]);

            $order->update([
                'customer_id' => $customer->id
            ]);

            $acountcharge = new UserAcounts();
            $acountcharge->user = 'مشتری';
            $acountcharge->type = 'شارژ';
            $acountcharge->cash = 0;
            $acountcharge->cunsomer_id = $customer->id;
            $acountcharge->save();
            if ($request->new_address !== null) {
                $address = CustomerAddress::create([
                    'address' => $request->new_address,
                    'customer_id' => $customer->id,
                    'broker_id' => $broker_id
                ]);
            }
        } else {
            $order->update([
                'customer_id' => $check_in_customers[0]->id
            ]);

            if ($request->new_address !== null) {
                $address = CustomerAddress::create([
                    'title'=> 'ادرس',
                    'address' => $request->new_address,
                    'customer_id' => $check_in_customers[0]->id,
                    'broker_id' => $broker_id
                ]);
            }
        }
        

        alert()->success($count . ' سفارش با موفقیت ثبت شد ', 'عملیات موفق')->autoclose(3000);
        return back();
    }

    public function getServices(Request $request)
    {
        $options = '<option value="">باز کردن فهرست انتخاب</option>';
        $services = Service::where('service_category_id', $request->data)->get();
        foreach ($services as $key => $service) {
            $options .= '<option value="' . $service->id . '">' . $service->service_title . '</option>';
        }
        return response($options, 200);
    }


    public function checkCustomer(Request $request)
    {
        $customer = Cunsomer::where('customer_mobile', $request->data)->first();
        if ($customer !== null) {
            $addresses =  CustomerAddress::where('customer_id', $customer->id)->get();
            $option_address = '<option value="">باز کردن فهرست انتخاب</option>';
            foreach ($addresses as $key => $address) {
                $option_address .= '<option value="' . $address->address . '">' . $address->address . '</option>';
            }
            return response()->json(['customer' => $customer, 'option_address' => $option_address], 200);
        }
        return 'false';
    }

    public function getPersonals(Request $request)
    {


        $service = Service::where('id', $request->service_id)->first();

        $tr = '';

        foreach ($service->personal->where('personal_status', 1) as $key => $personal) {

            $tr .=  '
            <input type="hidden" value="' . $request->order_id . '" name="order_id"   />
            <input type="hidden" value="' . csrf_token() . '" name="_token"   />
            <tr>
            <td>
              <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
              <input data-id="' . $personal->id . '" type="checkbox" id="' . $key . '" name="personal_id[]" class="custom-control-input" 
              value="' . $personal->id . '"
              ' . (count($personal->order->where('id', $request->order_id))  ? 'checked=""' : '') . '
              >
                <label class="custom-control-label" for="' . $key . '"></label>
              </div>
            </td>
            <td> ' . ($key + 1) . ' </td>
            <td>' . $personal->personal_firstname . '</td>
            <td>' . $personal->personal_lastname . '</td>
            <td>' . $personal->personal_mobile . '</td>
            ' . ($personal->personal_status == 1 ?
                '<td class="text-success">
                <i class="fa fa-check"></i>
            </td>'
                :
                '<td class="text-danger">
                <i class="fa fa-close"></i>
            </td>') . '
            </tr>';
        }

        return $tr;
    }

    public function choisePersonal(Request $request)
    {



        $order =  Order::where('id', $request->order_id)->first();
        $order->personals()->detach();
        foreach ($request->personal_id as $key => $personal_id) {

            $order->personals()->attach($personal_id);
            $sms_status = Service::where('id', $order->service_id)->first()->sms_status;

            if ($sms_status !== null) {
                $mobile = Personal::where('id', $personal_id)->first()->personal_mobile;
                $this->sendSMS($mobile);
            }

            Order::where('id', $request->order_id)->update([
                'order_type' => 'شروع نشده'
            ]);


            $token = Personal::where('id', $personal_id)->first()->firebase_token;

            //$token='fg6DjxT-QF2iqc46NeQEnJ:APA91bErRa0j3OeTu1l9oY4vxGHrQNIJFsqCCYFHyHbaT_PorNd7AelIWuasz0pLmT6Eonh9Y-6HD2aY56oj5uDu6mymekMKhoLfWg9onO7ij70RwXtjeVEJWlx4P01QrJuibyliF05w';

            $this->notification($token, 'یک خدمت جدید برای انجام دارید!');
        }






        alert()->success('خدمت رسان(ها) با موفقیت انتخاب شد.', 'عملیات موفق')->autoclose(2000);
        return back();
    }

    public function sendSMS($mobile)
    {

        $apikey = env('API_KEY');
        $receptor = $mobile;
        //$token = 'خدمات.محصلی.بضروری';
        $token = $mobile;
        $template = 'referredorder';
        $api = new \Kavenegar\KavenegarApi($apikey);
        try {
            $api->VerifyLookup($receptor, $token, null, null, $template);
        } catch (\Kavenegar\Exceptions\ApiException $e) {

            //return response()->json(['message' => 'مشکل پنل پیامکی پیش آمده است =>' . $e->errorMessage()], 400);
            return response()->json([
                'code' => $token, 'error' => 'مشکل پنل پیامکی پیش آمده است =>' . $e->errorMessage()
            ], 500);
        } catch (\Kavenegar\Exceptions\HttpException $e) {

            return response()->json(['code' => $token, 'error' => 'مشکل اتصال پیش امده است =>' . $e->errorMessage()], 500);
        }
    }

    public function choiseChosenPersonal(Request $request)
    {
        $order =  Order::where('id', $request->order_id)->first();
        $order->personals()->detach();
        foreach ($request->personal_id as $key => $personal_id) {
            if (DB::table('order_personal')->where([
                'order_id' => $request->order_id,
                'personal_id' => $request->personal_id
            ])->count()) {
                continue;
            } else {
                $order->personals()->attach($personal_id);
            }
        }



        alert()->success('خدمت رسان(ها) با موفقیت انتخاب شد.', 'عملیات موفق')->autoclose(2000);
        return back();
    }

    public function getDetailOrder(Request $request)
    {


       

      
          $order =  Order::where('id', $request->order_id)->first();
 


        $list = '
        
        <h6>وضعیت سفارش: ' . $order->order_type . '</h6>
        <span>نام مشتری:  </span>
        <span>' . $order->order_firstname_customer . '</span>
        <br><span>نام خانوادگی مشتری: </span>
        <span>' . $order->order_lastname_customer . '</span>
        <br><span>شماره همراه مشتری: </span>
        <span>' . $order->order_username_customer . '</span>
        <br><span>خدمت درخواستی: </span>
        <span>' . $order->relatedService->service_title . '</span>
        <br><span>توضیحات: </span>
        <span>' . $order->order_desc . '</span>
        <br><span>تاریخ و زمان اول درخواستی:</span>
        <span>' . \Morilog\Jalali\Jalalian::forge($order->order_date_first)->format('%d %B %Y') . ' ساعت ' . $order->order_time_first . '</span>
        <br><span>تاریخ و زمان دوم درخواستی: </span>
        <span>' . \Morilog\Jalali\Jalalian::forge($order->order_date_second)->format('%d %B %Y') . ' ساعت ' . $order->order_time_second . '</span>
        ';

        if ($order->orderDetail) {
            $detail = $order->orderDetail;
            $list .= '<br><br><span>جزئیات انجام: </span>
            ';

            if ($detail->order_reffer_time) {
                $list .= ' <br><span>زمان ارجاع:  </span>
                <span>' . \Morilog\Jalali\Jalalian::forge($detail->order_reffer_time)->format('%d %B %Y') . '
                ساعت  '.\Morilog\Jalali\Jalalian::forge($detail->order_reffer_time)->format('%H:i').'
                </span>';
            }
            if ($detail->order_start_time) {
                $list .= ' <br><span>زمان شروع:  </span>
                <span>' . \Morilog\Jalali\Jalalian::forge($detail->order_start_time)->format('%d %B %Y') . '
                ساعت  '.\Morilog\Jalali\Jalalian::forge($detail->order_start_time)->format('%H:i').'
                </span>';
            }
            if ($detail->order_start_description) {
                $list .= ' <br><span>توضیحات شروع کار: </span>
                <span>' . $detail->order_start_description . '</span>';
            }

            if ($detail->order_end_time) {
                $list .= ' <br><span>زمان پایان کار:  </span>
                <span>' . \Morilog\Jalali\Jalalian::forge($detail->order_end_time)->format('%d %B %Y') . '
                ساعت  '.\Morilog\Jalali\Jalalian::forge($detail->order_end_time)->format('%H:i').'
                </span>';
            }

            if ($detail->order_recived_price) {
                $list .= ' <br><span>هزینه دریافتی:  </span>
                    <span>' . $detail->order_recived_price . '</span>';
            }

            if ($detail->order_pieces_cast) {
                $list .= ' <br><span>هزینه قطعات مصرفی:  </span>
                <span>' . $detail->order_pieces_cast . '</span>';
            }
        }
        if (count($order->orderImages)) {
            $list .= '<br><br><span>تصاویر: </span>
            <div class="row">';
            foreach ($order->orderImages as $key => $image) {

                if ($image->image_type == 'faktor')  $title = 'فاکتور';
                if ($image->image_type == 'image1')  $title = 'عکس اول';
                if ($image->image_type == 'image2')  $title = 'عکس دوم';
                if ($image->image_type == 'image3')  $title = 'عکس سوم';

                $list .= '<div class="col-md-6 my-md-2 twxt-center">
           <span> ' . $title . '</span>
           <span> تاریخ :  ' . \Morilog\Jalali\Jalalian::forge($image->created_at)->format('%d %B %Y') . '</span>

            <img class="img-fluid" src="' . asset("uploads/$image->image_url") . '" />
            </div>';
            }
            $list .= '</div>';
        }
        return $list;
    }

    public function getChosenPersonal(Request $request)
    {
        $service = Service::where('id', $request->service_id)->first();
        $tr = '';
        foreach ($service->personal()->where('personal_chosen_status', 1)->where('personal_status', 1)->get() as $key => $personal) {
            $tr .=  '
            <input type="hidden" value="' . $request->order_id . '" name="order_id"   />
            <input type="hidden" value="' . csrf_token() . '" name="_token"   />
            <tr>
            <td>
              <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
              <input data-id="' . $personal->id . '" type="checkbox" id="chosen_' . $key . '"
              ' . (count($personal->order->where('id', $request->order_id))  ? 'checked=""' : '') . '
              name="personal_id[]" class="custom-control-input" value="' . $personal->id . '">
                <label class="custom-control-label" for="chosen_' . $key . '"></label>
              </div>
            </td>
            <td> ' . ($key + 1) . ' </td>
            <td>' . $personal->personal_firstname . '</td>
            <td>' . $personal->personal_lastname . '</td>
            <td>' . $personal->personal_mobile . '</td>
            ' . ($personal->personal_status == 1 ?
                '<td class="text-success">
                <i class="fa fa-check"></i>
            </td>'
                :
                '<td class="text-danger">
                <i class="fa fa-close"></i>
            </td>') . '
            </tr>';
        }

        return $tr;
    }

    public function deleteOrder(Request $request)
    {
        foreach ($request->array as $order) {

            Order::find($order)->personals()->detach();
            Order::where('id', $order)->delete();
        }
        return response()->json(['success' => true]);
    }


    public function notification($token, $title)
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token = $token;

        $notification = [
            'title' => $title,
            'sound' => true,
        ];

        $extraNotificationData = ["message" => $notification, "moredata" => 'وارد چهارسو شوید!'];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $token, //single token
            'notification' => $notification,
            'data' => $extraNotificationData
        ];

        $serverkey = env('FIREBASE_LEGACY_SERVER_KEY');


        $headers = [
            'Authorization: key=' . $serverkey,
            'Content-Type: application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);

        //dd($ch);

        return true;
    }
}
