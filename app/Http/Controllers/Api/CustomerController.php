<?php

namespace App\Http\Controllers\Api;

use App\App\Models\Customers\CustomerAddress;
use App\Http\Controllers\Controller;
use App\Models\Acounting\Transations;
use App\Models\Acounting\UserAcounts;
use App\Models\App\LoginSmsCode;
use App\Models\App\Slideshow;
use App\Models\Category;
use App\Models\City\City;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Neighborhood;
use App\Models\Orders\Order;
use App\Models\Rating\OrderRating;
use App\Models\Rating\SpecialScore;
use App\Models\Services\Service;
use App\Models\Services\ServiceCategory;
use App\Models\Store\GoodsOrders;
use App\Models\Store\GoodsOrdersStatuses;
use App\Models\Store\Product;
use App\Models\Store\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Orders\Offer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerController extends Controller
{

    public function verify(Request $request)
    {

        $code = LoginSmsCode::where('phone', $request->mobile)->latest()->first();

        if ($code->code == $request->code) {
            if ($code->created_at < Carbon::now()->subMinutes(5)) {

                return response()->json(['message' => 'متاسفانه کد ارسال اعتبار ندارد'], 400);
            }
        } else {

            //return response()->json(['message' => $code], 400);

            return response()->json(['message' => 'کد وارد شده صحیح نمی باشد'], 400);
        }

        $cunsomer = Cunsomer::where('customer_mobile', $request->mobile)->first();
        $check_cunsomer = Cunsomer::where([
            'customer_mobile' => $request->mobile,
        ])->count();
        if ($check_cunsomer) {
            Cunsomer::where('customer_mobile', $request->mobile)
                ->update([
                    'firebase_token' => $request->fcmtoken,
                ]);

            $token = JWTAuth::fromUser($cunsomer);
            return response()->json([
                'code' => $token,
                'error' => '',
            ], 200);
        } else {
            return response()->json([
                'code' => '',
                'error' => '',
            ], 200);
        }
    }

    public function register(Request $request)
    {

        $cunsomer = Cunsomer::create([
            'customer_firstname' => $request->c_firstname,
            'customer_lastname' => $request->c_lastname,
            'customer_mobile' => $request->c_mobile,
            'firebase_token' => $request->fcmtoken,
            'customer_city' => $request->c_city,

        ]);

        $acountcharge = new UserAcounts();
        $acountcharge->user = 'مشتری';
        $acountcharge->type = 'شارژ';
        $acountcharge->cash = 0;
        $acountcharge->cunsomer_id = $cunsomer->id;
        $acountcharge->save();

        $token = JWTAuth::fromUser($cunsomer);
        return response()->json([
            'code' => $token,
            'error' => '',
        ], 200);
    }

    public function getCustomer(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $response['pic'] = $customer->customer_profile;
        $response['name'] = $customer->customer_firstname;
        $response['lname'] = $customer->customer_lastname;
        $response['phone'] = $customer->customer_mobile;
        $response['codemelli'] = $customer->customer_national_code;

        $response['charge'] = $customer->useracounts[0]->cash;
        $response['hafte'] = $customer->created_at->diffInWeeks(Carbon::now());
        $response['orders'] = (Order::where('customer_id', $customer->id)->count()) + (GoodsOrders::where('cunsomers_id', $customer->id)->count());

        return response()->json(
            $response,
            200
        );
    }

    public function updateProfile(Request $request)
    {

        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        //if ($request->hasFile('customer_profile')) {
        if ($customer->customer_profile) {
            File::delete(public_path() . '/uploads/customers/' . $customer->customer_mobile . '/' . $customer->customer_profile);
        }

        $customer_img = 'photo' . time() . '.' . $request->customer_profile->getClientOriginalExtension();
        $destinationPath = public_path('/uploads/customers/' . $customer->customer_mobile);
        $request->customer_profile->move($destinationPath, $customer_img);
        $customer_profile = 'customers/' . $customer->customer_mobile . '/' . $customer_img;

        Cunsomer::where('customer_mobile', $mobile)
            ->update([
                'customer_profile' => $customer_profile,
            ]);

        return response()->json(
            $customer->fresh(),
            200
        );
    }

    public function updateCustomerData(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');

        Cunsomer::where('customer_mobile', $mobile)
            ->update([
                'customer_firstname' => $request->name,
                'customer_lastname' => $request->lname,
                'customer_national_code' => $request->codemelli,
            ]);

        //$customer = Cunsomer::where('customer_mobile', $mobile)->first();
        return response()->json('ok', 200);
    }

    public function getHomePageDetail(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $settings = DB::table('setting')->get();
        $setting = $settings[0];

        $slideshow = Slideshow::where('place', 'خانه')->where('status', 1)->where('release', '<=', date("Y-m-d"))->where('expiry', '>=', date("Y-m-d"))->get();

        return response()->json(
            [
                'name' => $customer->customer_firstname . ' ' . $customer->customer_lastname,
                'mobile' => $customer->customer_mobile,
                'charge' => $customer->useracounts[0]->cash,
                'city' => $customer->customer_city,
                'profilepic' => $customer->customer_profile,
                'shomareposhtibani' => $setting->shomareposhtibani,
                'shomareoperator' => $setting->shomareoperator,
                'telegramposhtibani' => $setting->telegramposhtibani,
                'linkappworker' => $setting->linkappservicer,
                'linklaw' => $setting->linklaw,
                'linkfaq' => $setting->linkfaq,
                'slideshow' => $slideshow,

            ],
            200
        );
    }

    public function getAllOrders(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        $customer_model = new Cunsomer();
        //$orders =  $customer_model->getOrders($customer->id);
        $orders = Order::where('customer_id', $customer->id)
            ->where(function ($query) {
                $query->where('order_type', 'معلق')
                    ->orWhere('order_type', 'شروع نشده')
                    ->orWhere('order_type', 'در حال انجام')
                    ->orWhere('order_type', 'مناقصه')
                    ->orWhere('order_type', 'انجام شده');
            })
            ->latest()->get();

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

                        $order->order_time_second = null;
                        $order->order_date_second = null;
                    } else {

                        $order->order_time_first = $order->order_time_second;
                        $order->order_date_first = $order->order_date_second;

                        $order->order_time_second = null;
                        $order->order_date_second = null;
                    }
                }
            }
        }

        return response()->json([
            'data' => $orders,
        ], 200);
    }

    public function getAllArchiveOrders(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        $customer_model = new Cunsomer();
        //$orders =  $customer_model->getOrders($customer->id);
        $orders = Order::where('customer_id', $customer->id)
            ->where(function ($query) {
                $query->where('order_type', 'تسویه شده')
                    ->orWhere('order_type', 'لغو شده');
            })
            ->latest()->get();

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

                        $order->order_time_second = null;
                        $order->order_date_second = null;
                    } else {

                        $order->order_time_first = $order->order_time_second;
                        $order->order_date_first = $order->order_date_second;

                        $order->order_time_second = null;
                        $order->order_date_second = null;
                    }
                }
            }
        }
        return response()->json([
            'data' => $orders,
        ], 200);
    }

    public function getOrder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        $code = $request->code;
        $customer_model = new Cunsomer();
        $order = $customer_model->getOrder($customer->id, $code);
        $service = $customer_model->getOrderService($customer->id, $code);
        $order_personal = $order->personal;
        $specialscores = [];
        if (!is_null($order_personal)) {
            $personal_array = [
                'personal_firstname' => $order_personal->personal_firstname,
                'personal_lastname' => $order_personal->personal_lastname,
                'personal_last_diploma' => $order_personal->personal_last_diploma,
                'personal_mobile' => $order_personal->personal_mobile,
            ];
        } else {
            $personal_array = [
                'personal_firstname' => '',
                'personal_lastname' => '',
                'personal_last_diploma' => '',
                'personal_mobile' => '',
            ];
        }

        $order_detail = $order->orderDetail;
        if (!is_null($order_detail)) {
            if ($order_detail->rated == 0 && $order->order_type == 'تسویه شده') {
                $specialscores = SpecialScore::where('active', 1)->get()->toArray();
            }

            // return $specialscores;
            $details_array = [
                'id' => $order->id,
                'order_reffer_time' => $order_detail->order_reffer_time,
                'order_start_time' => $order_detail->order_start_time,
                'order_start_description' => $order_detail->order_start_description,
                'order_start_time_positions' => $order_detail->order_start_time_positions,
                'order_end_time' => $order_detail->order_end_time,
                'order_end_time_positions' => $order_detail->order_end_time_positions,
                'order_end_time_description' => $order_detail->order_end_time_description,
                'order_cancel_time' => $order_detail->cancel_time,
                'order_recived_price' => $order_detail->order_recived_price,
                'order_pieces_cast' => $order_detail->order_pieces_cast,
                'charge' => $customer->useracounts[0]->cash,
                'rated' => $order_detail->rated,
                'special_scores' => $specialscores,
            ];
        } else {
            $details_array = [
                'order_recived_price' => '',
                'order_pieces_cast' => '',

            ];
        }
        if (!is_null($service)) {
            $service_name = $service->service_title;
            $service_desc = $service->service_desc;
            $servicepic1 = '';
            $servicepic2 = '';
        } else {
            $service_name = 'ندارد';
        }

        // $images = [];
        // foreach ($order->orderImages as $key => $image) {
        //     $images[$image->image_type] = $image->image_url;

        // }

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

                    $order->order_time_second = null;
                    $order->order_date_second = null;
                } else {

                    $order->order_time_first = $order->order_time_second;
                    $order->order_date_first = $order->order_date_second;

                    $order->order_time_second = null;
                    $order->order_date_second = null;
                }
            }
        }

        $array = [
            'service_name' => $service_name,
            'service_desc' => $service_desc,
            'service_pic1' => $servicepic1,
            'service_pic2' => $servicepic2,
            'order_unique_code' => $order->order_unique_code,
            'order_desc' => $order->order_desc,
            'order_time_first' => $order->order_time_first,
            'order_time_second' => $order->order_time_second,
            'order_date_first' => $order->order_date_first,
            'order_date_second' => $order->order_date_second,
            'order_type' => $order->order_type,
            'order_address' => $order->order_address,
            'images' => $order->orderImages,

        ];

        $pay = [];

        if ($order->order_type == 'انجام شده') {

            $cunsomeracount = $customer->useracounts[0]->cash;

            if (!is_null($order->orderDetail->order_recived_price)) {

                //$cunsomeracount+=$order->off_amount;
                $useracountcustomerwithoff = $cunsomeracount + $order->off_amount;
                $cost = $order->orderDetail->order_recived_price + $order->orderDetail->order_pieces_cast;

                if ($useracountcustomerwithoff >= $cost) {
                    $order->paytype = 'اعتباری';

                    $order->creditamount = $cost - $order->off_amount;
                    $order->cashamount = 0;
                } else {
                    if ($order->off_amount > 0) {
                        if ($order->offcodeuse->offcode->dolaw == 3) {
                            //$cunsomeracount -= $order->off_amount;
                            $useracountcustomerwithoff -= $order->off_amount;
                            $order->off_amount = 0;
                        }
                    }

                    if ($useracountcustomerwithoff > 0) {
                        $order->paytype = 'نقدی-اعتباری';

                        $order->creditamount = $cunsomeracount;
                        $order->cashamount = $cost - $useracountcustomerwithoff;
                    } else {
                        $order->paytype = 'نقدی';

                        $order->creditamount = 0;
                        $order->cashamount = $cost;
                    }
                }

                if (strlen($order->off_code) > 0) {
                    if ($order->off_amount == 0) {

                        $pay['offreason'] = 'کد تخفیف به دلیل عدم بهره مندی از شرایط اعمال نشد';
                    }
                }
            }
        }

        $pay['paytype'] = $order->paytype;

        $pay['creditamount'] = $order->creditamount;
        $pay['cashamount'] = $order->cashamount;
        $pay['off_amount'] = $order->off_amount;



        if ($order->offer_id) {
            $pay['offer'] = Offer::find($order->offer_id);
        }

        return response()->json(array_merge($array, $personal_array, $details_array, $pay, $specialscores), 200);
    }

    public function getCategories(Request $request)
    {
        // $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        // $mobile = $payload->get('mobile');
        // $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        // $id = $request->id;
        // $pos = '0';
        // $category = ServiceCategory::where('category_parent', $id)->count();
        // // if($category){
        // //     $category = ServiceCategory::where('category_parent', $id)->get();
        // // }else{
        // $category1 = ServiceCategory::where('id', $id)->first();
        // $category2 = ServiceCategory::where('id', $category1->category_parent)->first();
        // if ($category2) {
        //     $category = ServiceCategory::where('category_parent', $category2->id)->get();
        // } else {
        //     $category = ServiceCategory::where('category_parent', $category1->category_parent)->get();
        // }
        // }
        $category = Category::where('parent_id', 0)->orderBy('name', 'asc')->get();
        foreach ($category as $key => $categ) {

            $cat['iditem'] = $categ->id;
            $cat['title'] = $categ->name;
            $cat['icon'] = $categ->picture;
            //$cat['icon']='personals/09156833780/photo-1584535352.jpg';

            $catego = Category::where('parent_id', $categ->id)->get();
            // $services = Service::where('service_category_id', $categ->id)->get();
            // $stores = Store::where('store_type', $categ->id)->get();

            // if ($categ->id == $id) {
            //     $pos = (string) $key;
            // }

            // $chilist = [];

            $catchitems = [];
            // $servchitems = [];
            // $srorchitems = [];

            if (count($catego)) {
                foreach ($catego as $kes => $categor) {

                    $chil['iditem'] = $categor->id;
                    $chil['title'] = $categor->name;
                    $chil['icon'] = $categor->picture;
                    //$chil['icon']='personals/09156833780/photo-1584535352.jpg';
                    $chil['type'] = '2';

                    $catchitems[$kes] = $chil;
                }
            } else {
                $catchitems[] = null;
            }
            // foreach ($services as $kes => $service) {

            //     $chil['iditem'] = $service->id;
            //     $chil['title'] = $service->service_title;
            //     $chil['icon'] = $service->service_icon;
            //     //$chil['icon']='personals/09156833780/photo-1584535352.jpg';

            //     $chil['type'] = '4';

            //     $servchitems[$kes] = $chil;
            // }
            // foreach ($stores as $kes => $store) {

            //     $chil['iditem'] = $store->id;
            //     $chil['title'] = $store->store_name;
            //     $chil['icon'] = $store->store_icon;
            //     //$chil['icon']='personals/09156833780/photo-1584535352.jpg';

            //     $chil['type'] = '5';

            //     $srorchitems[$kes] = $chil;
            // }

            // $cat['items'] = array_merge($catchitems, $servchitems, $srorchitems);
            $cat['items'] = $catchitems;
            $cate[$key] = $cat;
        }

        return response()->json([
            'data' => $cate,
            'error' => [
                'message' => '$pos',
            ],

        ], 200);

        // if($categoryzirdaste){

        //     $categoryzirdastes = ServiceCategory::where('category_parent', $categoryzirdaste->id)->first();

        //     if($categoryzirdastes){
        //         $chil['type']='2';
        //     }else{
        //         $chil['type']='3';
        //     }

        // }else{
        //     $chil['type']='3';
        // }
    }

    public function getCategoriesbyloc(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        $id = $request->id;
        $pos = '0';

        $city = City::where('city_name', $request->city)->first();

        if (is_null($city)) {

            return $this->index();
        }
        $neighbourhood = Neighborhood::where('city_id', $city->id)->where('name', $request->neighbourhood)->first();

        if (is_null($neighbourhood)) {

            return $this->getCategories($request);
        }
        $category = ServiceCategory::where('category_parent', $id)->count();
        // if($category){
        //     $category = ServiceCategory::where('category_parent', $id)->get();
        // }else{
        $category1 = ServiceCategory::where('id', $id)->first();
        $category2 = ServiceCategory::where('id', $category1->category_parent)->first();
        if ($category2) {
            $category = ServiceCategory::where('category_parent', $category2->id)->get();
        } else {
            $category = ServiceCategory::where('category_parent', $category1->category_parent)->get();
        }

        // }
        foreach ($category as $key => $categ) {

            $cat['iditem'] = $categ->id;
            $cat['title'] = $categ->category_title;
            $cat['icon'] = $categ->category_icon;
            //$cat['icon']='personals/09156833780/photo-1584535352.jpg';

            $catego = ServiceCategory::where('category_parent', $categ->id)->get();
            $services = Service::where('service_category_id', $categ->id)->get();
            $stores = Store::where('store_type', $categ->id)
                ->whereHas('neighborhoods', function ($q) use ($neighbourhood) {
                    $q->where('id', $neighbourhood->id);
                })
                ->get();

            if ($categ->id == $id) {
                $pos = (string) $key;
            }

            $chilist = [];

            $catchitems = [];
            $servchitems = [];
            $srorchitems = [];

            foreach ($catego as $kes => $categor) {

                $chil['iditem'] = $categor->id;
                $chil['title'] = $categor->category_title;
                $chil['icon'] = $categor->category_icon;
                //$chil['icon']='personals/09156833780/photo-1584535352.jpg';
                $chil['type'] = '2';

                $catchitems[$kes] = $chil;
            }
            foreach ($services as $kes => $service) {

                $chil['iditem'] = $service->id;
                $chil['title'] = $service->service_title;
                $chil['icon'] = $service->service_icon;
                //$chil['icon']='personals/09156833780/photo-1584535352.jpg';

                $chil['type'] = '4';

                $servchitems[$kes] = $chil;
            }
            foreach ($stores as $kes => $store) {

                $chil['iditem'] = $store->id;
                $chil['title'] = $store->store_name;
                $chil['icon'] = $store->store_icon;
                //$chil['icon']='personals/09156833780/photo-1584535352.jpg';

                $chil['type'] = '5';

                $srorchitems[$kes] = $chil;
            }

            $cat['items'] = array_merge($catchitems, $servchitems, $srorchitems);
            $cate[$key] = $cat;
        }

        return response()->json([
            'data' => $cate,
            'error' => [
                'message' => $pos,
            ],

        ], 200);

        // if($categoryzirdaste){

        //     $categoryzirdastes = ServiceCategory::where('category_parent', $categoryzirdaste->id)->first();

        //     if($categoryzirdastes){
        //         $chil['type']='2';
        //     }else{
        //         $chil['type']='3';
        //     }

        // }else{
        //     $chil['type']='3';
        // }
    }

    public function getServices(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        $id = $request->id;
        $categorymainchildsnum = ServiceCategory::where('category_parent', $id)->count();
        if ($categorymainchildsnum) {

            $categorymainchilds = ServiceCategory::where('category_parent', $id)->get();

            foreach ($categorymainchilds as $keys => $categorymainchild) {

                $catres['iditem'] = $categorymainchild->id;
                $catres['title'] = $categorymainchild->category_title;
                //$catres['icon']='personals/09156833780/photo-1584535352.jpg';
                $catres['icon'] = $categorymainchild->category_icon;

                $services = Service::where('service_category_id', $categorymainchild->id)->get();

                $items = [];
                foreach ($services as $keyss => $service) {

                    $serv['iditem'] = $service->id;
                    $serv['title'] = $service->service_title;
                    //$serv['icon']='personals/09156833780/photo-1584535352.jpg';
                    $serv['icon'] = $service->service_icon;
                    $serv['type'] = 4;

                    $items[$keyss] = $serv;
                }

                $stores = Store::where('store_type', $categorymainchild->id)->get();

                foreach ($stores as $keyss => $store) {

                    $serv['iditem'] = $store->id;
                    $serv['title'] = $store->store_name;
                    $serv['icon'] = $store->store_icon;
                    $serv['type'] = 5;

                    $items[$keyss] = $serv;
                }

                $catres['items'] = $items;
                $result[$keys] = $catres;
            }

            return response()->json([
                'data' => $result,
                'error' => [
                    'message' => '1',
                ],
            ], 200);
        } else {

            $categoryselected = ServiceCategory::where('id', $id)->first();
            $categoryparents = ServiceCategory::where('id', $categoryselected->category_parent)->first();
            if ($categoryparents) {
                $categorychilds = ServiceCategory::where('category_parent', $categoryparents->id)->get();

                foreach ($categorychilds as $kef => $categorychild) {

                    $services = Service::where('service_category_id', $categorychild->id)->get();

                    $catres['iditem'] = $categorychild->id;
                    $catres['title'] = $categorychild->category_title;
                    //$catres['icon']='personals/09156833780/photo-1584535352.jpg';
                    $catres['icon'] = $categorychild->category_icon;

                    $servres = [];

                    foreach ($services as $key => $service) {

                        $serv['iditem'] = $service->id;
                        $serv['title'] = $service->service_title;
                        //$serv['icon']='personals/09156833780/photo-1584535352.jpg';
                        $serv['icon'] = $service->service_icon;
                        $serv['type'] = 4;

                        $servres[$key] = $serv;
                    }

                    $stores = Store::where('store_type', $categorychild->id)->get();

                    foreach ($stores as $keyss => $store) {

                        $serv['iditem'] = $store->id;
                        $serv['title'] = $store->store_name;
                        $serv['icon'] = $store->store_icon;
                        $serv['type'] = 5;

                        $servres[$keyss] = $serv;
                    }

                    $catres['items'] = $servres;
                    $result[$kef] = $catres;
                }

                return response()->json([
                    'data' => $result,
                ], 200);
            } else {

                $services = Service::where('service_category_id', $categoryselected->id)->get();

                $catres['iditem'] = $categoryselected->id;
                $catres['title'] = $categoryselected->category_title;
                $catres['icon'] = 'personals/09156833780/photo-1584535352.jpg';

                foreach ($services as $key => $service) {

                    $serv['iditem'] = $service->id;
                    $serv['title'] = $service->service_title;
                    //$serv['icon']='personals/09156833780/photo-1584535352.jpg';
                    $serv['icon'] = $service->service_icon;
                    $serv['type'] = 4;

                    $servres[$key] = $serv;
                }

                $stores = Store::where('store_type', $categoryselected->id)->get();

                foreach ($stores as $keyss => $store) {

                    $serv['iditem'] = $store->id;
                    $serv['title'] = $store->store_name;
                    $serv['icon'] = $store->store_icon;
                    $serv['type'] = 5;

                    $servres[$keyss] = $serv;
                }

                $catres['items'] = $servres;
                $servres = [];
                $result[0] = $catres;

                return response()->json([
                    'data' => $result,
                ], 200);
            }
        }
    }

    public function getCategoryArrange(Request $request)
    {
        // $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        // $mobile = $payload->get('mobile');
        // $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        // $id = $request->id;
        // $category = ServiceCategory::where('id', $id)->first();
        $basic_categories = Category::where('parent_id',0)->orderBy('name','asc')->get();

        // $idcatparnt = $category->category_parent;
        $result = [];
        foreach ($basic_categories as $key => $category) {
            
        }

        // for ($i = 0; $idcatparnt; $i++) {

        //     $categoryparent = ServiceCategory::where('id', $idcatparnt)->first();
        //     if ($categoryparent) {
        //         $cat['iditem'] = $categoryparent->id;
        //         $cat['title'] = $categoryparent->category_title;
        //         //$cat['icon']=$categoryparent->category_icon;
        //         $cat['icon'] = 'personals/09156833780/photo-1584535352.jpg';

        //         $idcatparnt = $categoryparent->category_parent;

        //         $result[$i] = $cat;
        //     } else {
        //         break;
        //     }
        // }
        // unset($result[$i - 1]);

        return response()->json([
            'data' => $result,
        ], 200);
    }

    public function getCustomerAddresses(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        $customer_model = new Cunsomer();
        $addresses = $customer_model->getAddresses($customer->id);
        $array = [];

        foreach ($addresses as $key => $address) {
            $array['addresses'][$key + 1]['title'] = $address->title;
            $array['addresses'][$key + 1]['address'] = $address->address;
        }

        return response()->json([
            'data' => $addresses,
        ], 200);
    }

    public function submitAddress(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        if (is_null($customer)) {
            return response(
                ['error' => 'خطای احراز هویت'],
                404
            );
        }
        $customer_broker = $customer->brokers;
        // شاید این مشتری متعلق به چند کارگزاری باشد
        if (is_array($customer_broker) && count($customer_broker) !== 0) {
            foreach ($customer_broker as $key => $broker) {
                CustomerAddress::create([
                    'title' => $request->title,
                    'city' => $request->city,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'neighbourhood' => $request->neighbourhood,
                    'address' => $request->address,
                    'broker_id' => $broker,
                    'customer_id' => $customer->id,
                ]);
            }
        } else {
            CustomerAddress::create([
                'title' => $request->title,
                'city' => $request->city,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'neighbourhood' => $request->neighbourhood,
                'address' => $request->address,
                'broker_id' => null,
                'customer_id' => $customer->id,
            ]);
        }

        return response()->json(
            ['data' => 'ادرس با موفقیت ثبت شد'],
            200
        );
    }

    public function getTransactions(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $useracounts = $customer->useracounts[0];
        $transactions = Transations::where('user_acounts_id', $useracounts->id)->orderBy('id', 'desc')->paginate(15);

        return response()->json(
            $transactions,
            200
        );
    }

    public function getUserAccount(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $useracounts = $customer->useracounts[0];

        return response()->json(
            $useracounts,
            200
        );
    }

    public function lastorder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $order = Order::where('customer_id', $customer->id)
            ->where(function ($query) {
                $query->where('order_type', 'معلق')
                    ->orWhere('order_type', 'مناقصه')
                    ->orWhere('order_type', 'شروع نشده');
            })
            ->latest()->first();

        if ($order) {

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

                        $order->order_time_second = null;
                        $order->order_date_second = null;
                    } else {

                        $order->order_time_first = $order->order_time_second;
                        $order->order_date_first = $order->order_date_second;

                        $order->order_time_second = null;
                        $order->order_date_second = null;
                    }
                }
            }

            return response()->json(
                $order,
                200
            );
        } else {

            return response()->json(
                'nok',
                404
            );
        }
    }

    public function lastgoodsorder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $order = GoodsOrders::where('cunsomers_id', $customer->id)
            ->where(function ($query) {
                $query->where('status', 'معلق')
                    ->orWhere('status', 'تایید فروشنده');
            })
            ->latest()->first();

        if ($order) {

            $items = explode(',', $order->items);
            if ($order->prices) {
                $prices = explode(',', $order->prices);
            } else {
                $prices = null;
            }
            $products = [];
            foreach ($items as $key => $item) {

                $product = Product::find($item);
                if ($prices) {
                    $product->product_price = $prices[$key];
                }

                $products[] = $product;
            }
            $order['customer_mobile'] = $order->personal_mobile;
            $order['customer_name'] = $order->store->store_name;

            $order['products'] = $products;
            $order->items = explode(',', $order->items);
            $order->counts = explode(',', $order->counts);
            $order['images'] = $order->images;
            $status = GoodsOrdersStatuses::find($order->id);

            $order['accept_time'] = $status->accept_time ?? '';
            $order['preparation_time'] = $status->preparation_time ?? '';
            $order['send_time'] = $status->send_time ?? '';
            $order['deliver_time'] = $status->deliver_time ?? '';
            $order['cancel_time'] = $status->cancel_time ?? '';

            $order->questions = [];
            $order->answers = [];

            return response()->json(
                $order,
                200
            );
        } else {

            return response()->json(
                'nok',
                200
            );
        }
    }

    public function getCustomerAddressesneighbourhood(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();
        $customer_model = new Cunsomer();
        $addresses = $customer_model->getAddresses($customer->id);

        $store = Store::find($request->store_id);
        $neighbourhood = $store->neighborhoods->pluck('name')->toArray();

        $addressesin = [];
        foreach ($addresses as $address) {
            if (in_array($address->neighbourhood, $neighbourhood)) {

                $addressesin[] = $address;
            }
        }

        return response()->json([
            'data' => $addressesin,
        ], 200);
    }

    public function rateorder(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $customer = Cunsomer::where('customer_mobile', $mobile)->first();

        $order = Order::find($request->order_id);
        $detail = $order->orderDetail;
        $detail->rated = 1;

        $rate = new OrderRating;
        $rate->order_id = $order->id;
        if ($order->personal_id) {
            $rate->personal_id = $order->personal_id;
        } else {
            $rate->personal_id = $order->personals[0]->id;
        }
        $rate->customer_id = $customer->id;
        $rate->score = $request->score;
        if ($request->specials) {
            $rate->special_scores = implode(' , ', $request->specials);
        }
        if ($request->desc) {
            $rate->description = $request->desc;
        }

        $rate->save();

        $detail->update();

        return response()->json([
            'code' => 200,
        ], 200);
    }
}
