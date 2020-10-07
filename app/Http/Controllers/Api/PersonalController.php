<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Acounting\UserAcounts;
use App\Models\Acounting\PersonalBank;
use App\Models\City\City;
use App\Models\Personals\Personal;
use App\Models\Store\Store;
use App\Models\User;
use App\Models\Acounting\Transations;
use App\Models\Notifications\WorkerappNotifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Morilog\Jalali\Jalalian;
use App\Models\App\LoginSmsCode;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class PersonalController extends Controller
{
    public $loginAfterSignUp = true;
    public function verify(Request $request)
    {

        $code=LoginSmsCode::where('phone',$request->mobile)->latest()->first();

        if($code->code==$request->code){
        if($code->created_at<Carbon::now()->subMinutes(5)){


            return response()->json(['message' => 'متاسفانه کد ارسال اعتبار ندارد'], 400);
        }
    }else{

        //return response()->json(['message' => $code], 400);

        return response()->json(['message' => 'کد وارد شده صحیح نمی باشد'], 400);
    }

        $personal = Personal::where('personal_mobile', $request->mobile)->first();
        $check_personal = Personal::where([
            'personal_mobile' => $request->mobile,
        ])->count();
        if ($check_personal) {
            Personal::where('personal_mobile',  $request->mobile)
            ->update([
                'firebase_token' => $request->fcmtoken,
            ]);
    
            $token = JWTAuth::fromUser($personal);
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

    public function getCities()
    {
        $cities = City::orderBy('city_name', 'ASC')->get();
        return response()->json([
            'data' => $cities,
        ], 200);
    }

    public function register(Request $request)
    {

        $personal = Personal::create([
            'personal_firstname' => $request->p_firstname,
            'personal_lastname' => $request->p_lastname,
            'personal_mobile' => $request->p_mobile,
            'personal_city' => $request->p_city,
            'firebase_token' => $request->fcmtoken,
        ]);

      

        $acountcharge = new UserAcounts();

        $acountcharge->user = 'خدمت رسان';
        $acountcharge->type = 'شارژ';
        $acountcharge->cash = 0;
        $acountcharge->personal_id = $personal->id;

        $acountencome = new UserAcounts();

        $acountencome->user = 'خدمت رسان';
        $acountencome->type = 'درآمد';
        $acountencome->cash = 0;
        $acountencome->personal_id = $personal->id;

        $acountcharge->save();
        $acountencome->save();

        $token = JWTAuth::fromUser($personal);
        return response()->json([
            'code' => $token,
            'error' => '',
        ], 200);
    }

    public function getPersonalDashboardDetail(Request $request)
    {

        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
      
        $broker_name=[];
        //$notifications=WorkerappNotifications::where('group','همه')->take(5)->get();
        $services=$personal->services;

        if(!is_null($services)){
            foreach($services as $service){
                $broker_name[] =$service->service_role; 

                //$notifications[]=$notifications=WorkerappNotifications::where('group','همه')->where('brokers','like','%'.$broker_name.'%')->take(2)->get();
            }
    }else{
        $broker_name[]='qqqqqqqqq';
    }
   //return response()->json($broker_name,200);

    $notifications=WorkerappNotifications::where('group','همه')
    ->orWhere(function ($query) use ($broker_name) {
        
        for ($i = 6; $i < count($broker_name); $i++){
         
                $query->where('group','کارگذاران')
                ->orWhere('brokers','like','%'.$broker_name[$i].'%');
         
            
         }    
    })
    //->whereIn('brokers',$broker_name)
    ->latest()
    ->take(5)
    ->get();

        // $notifications=WorkerappNotifications::where('group','همه')->orWhere(function ($query) {
        //     $query->where('group','کارگذاران')->whereIn('brokers',$broker_name);
        // })
        // ->orderBy('created_at', 'desc')
        // ->take(10)
        // ->get();

        $settings = DB::table('setting')->get();
        $setting=$settings[0];

        return response()->json([
            'profilepic' => $personal->personal_profile,
            'namefname' => $personal->personal_firstname . ' ' . $personal->personal_lastname,
            'incomecash' => $personal->useracounts[1]->cash,
            'chargecash' => $personal->useracounts[0]->cash,
            'emtiaz' => '0',
            'shomareposhtibani'=>$setting->shomareposhtibani,
            'telegramposhtibani'=>$setting->telegramposhtibani,
            'notifications'=>$notifications,

        ], 200);
    }

    public function getPersonal(Request $request)
    {

        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
        if(!is_null($personal->services->first())){
                $broker_name = $personal->services->first()->service_role; 
                $personal->servicess =implode('-', Personal::where('id',1)->first()->services->pluck('service_title')->toArray()); 
                
                $personal->broker_name = $broker_name;
        }else{
            $personal->services =null;
            $personal->broker_name = null;
        }
       


        $settings = DB::table('setting')->get();
        $setting=$settings[0];
        $personal->shomareposhtibani=$setting->shomareposhtibani;
        $personal->aboutlink=$setting->linkfaq;

        $personal['personal_birthday']= Jalalian::forge($personal->personal_birthday)->format('%Y/%m/%d');


        return response()->json(
            $personal,
            200
        );
    }

    public function updatePersonalData(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        Personal::where('personal_mobile', $mobile)
            ->update([
                'personal_firstname' => $request->personal_firstname,
                'personal_lastname' => $request->personal_lastname,
                'personal_birthday' => $this->convertDate($request->personal_birthday)->toDateString(),
                'personal_national_code' => $request->personal_national_code,
                'personal_marriage' => $request->personal_marriage,
                'personal_last_diploma' => $request->personal_last_diploma,
                'personal_home_phone' => $request->personal_home_phone,
                'personal_city' => $request->personal_city,
                'personal_postal_code' => $request->personal_postal_code,
                'personal_address' => $request->personal_address,
                'personal_office_phone' => $request->personal_office_phone,
            ]);
            $personal = Personal::where('personal_mobile', $mobile)->first();
        return response()->json([
            'data' => [
                'personal' => $personal,
            ],
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
        //if ($request->hasFile('personal_profile')) {
            if($personal->personal_profile){
            File::delete(public_path().'/uploads/'. $personal->personal_profile);
            }

            $personal_img = 'photo-'.time().'.'.$request->personal_profile->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/personals/'.$personal->personal_mobile);
            $request->personal_profile->move($destinationPath, $personal_img);
            $personal_profile = 'personals/'.$personal->personal_mobile .'/'.$personal_img;

        Personal::where('personal_mobile', $mobile)
        ->update([
            'personal_profile' => $personal_profile
        ]);
     return response()->json(
            $personal->fresh()
     , 200);

    }

    public function getPersonalStore(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile',$mobile)->first();

        $store =  Store::where('owner_id',$personal->id)->first();
        if(!is_null($store)){
            $store['store_address']=$store->store_main_street.' '.$store->store_secondary_street.' پلاک '.$store->store_pelak;
            $store['mobile']=$personal->personal_mobile;

            $storeArray = [];
            $storeArray['store_name'] = $store->store_name;
            $storeArray['store_address']=$store->store_main_street.' '.$store->store_secondary_street.' پلاک '.$store->store_pelak;
            $storeArray['store_description'] = $store->store_description;
            $storeArray['store_type'] = $store->store_type;
            $storeArray['store_picture'] = $store->store_picture;
            $storeArray['store_icon'] = $store->store_icon;
            $storeArray['store_city'] = $store->store_city;
            $storeArray['store_main_street'] = $store->store_main_street;
            $storeArray['store_secondary_street'] = $store->store_secondary_street;
            $storeArray['store_pelak'] = $store->store_pelak;
            $storeArray['store_packing_price'] = $store->packing_price;
            $storeArray['store_sending_price'] = $store->sending_price;   
            $storeArray['store_pelak'] = $store->store_pelak;
            foreach ($store->neighborhoods as $key => $neighborhood) {
                $storeArray['neighborhoods'][$key+1]['name'] = $neighborhood->name;
                $storeArray['neighborhoods'][$key+1]['city'] = $neighborhood->city_id;
               
            }
           
         
                foreach ($store->products as $key => $product) {
                   if($product->type == 'primary_product'){
                    $storeArray['general_products'][$key+1]['product_name']= $product->product_name;
                    $storeArray['general_products'][$key+1]['product_price'] = $product->product_price;
                    $storeArray['general_products'][$key+1]['product_picture'] = $product->product_picture;
                    $storeArray['general_products'][$key+1]['product_description'] = $product->product_description;
                    $storeArray['general_products'][$key+1]['product_status'] = $product->product_status;
                   }
                   if($product->type == 'secondary_product'){
                    $storeArray['sundry_products'][$key+1]['product_name']= $product->product_name;
                    $storeArray['sundry_products'][$key+1]['product_price'] = $product->product_price;
                    $storeArray['sundry_products'][$key+1]['product_picture'] = $product->product_picture;
                    $storeArray['sundry_products'][$key+1]['product_status'] = $product->product_status;
                   }
                }
            
            return response()->json(
                $store,
                200
              );
        }else{
            return response()->json(
                'error',
                400
              );
        }
    }

    public function changeStoreStatus(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile',$mobile)->first();
        $statusstore = $request->status;

        $store =  Store::where('owner_id',$personal->id)->first();

       
        if(!is_null($store)){

            $store->store_status=$statusstore;

            $store->update();
           // Store::where('owner_id',$personal->id)->update(['store_status',$status]);
            return response()->json(
                $store,
                200
              );
         }else{
            return response()->json(
                'error',
                400
              );
        }
    }



    // public function setFireBaseToken(Request $request)
    // {

    //     $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
    //     $mobile = $payload->get('mobile');
    //     //$personal = Personal::where('personal_mobile', $mobile)->first();
      
    //     Personal::where('personal_mobile', $mobile)
    //     ->update([
    //         'firebase_token' => $request->fcmtoken,
    //     ]);

       

    //     return response()->json('ok', 200);
    // }


    public function getTransactionscharge(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile',$mobile)->first();
       
        $useracounts=$personal->useracounts[0];
        $transactions=Transations::where('user_acounts_id',$useracounts->id)->orderBy('id','desc')->get();


    return response()->json([
      'data'=>$transactions
    ], 200);

    }

    public function getTransactionsincome(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile',$mobile)->first();
       
        $useracounts=$personal->useracounts[1];
        $transactions=Transations::where('user_acounts_id',$useracounts->id)->orderBy('id','desc')->get();


    return response()->json([
      'data'=>$transactions
    ], 200);

    }

    public function getbankinfo(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile',$mobile)->first();
         $bankinfo=$personal->bank;




    return response()->json(
      $bankinfo
    , 200);

    }

    public function savebankinfo(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile',$mobile)->first();
       
        $bankinfo=$personal->bank;
        if($bankinfo){

            $bankinfo->shaba=$request->shaba;
            $bankinfo->name=$request->name;
            $bankinfo->bankname=$request->bankname;

            $bankinfo->update();

        }else{

            $bankinfo= new PersonalBank;
            $bankinfo->personal_id=$personal->id;
            $bankinfo->mobile=$mobile;
            $bankinfo->shaba=$request->shaba;
            $bankinfo->name=$request->name;
            $bankinfo->bankname=$request->bankname;

            $bankinfo->save();


        }


    return response()->json($bankinfo, 200);

    }
}
