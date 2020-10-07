<?php

namespace App\Http\Controllers\Notifications;

use App\Http\Controllers\Controller;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Notifications\Notifications;
use App\Models\Notifications\PannelNotifications;
use App\Models\Personals\Personal;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Services\Service;
use Spatie\Permission\Models\Role;

class NotificationsController extends Controller
{

    public function index()
    {

        $notifications = Notifications::all();

        $cunsomers = Cunsomer::all();
        $personals = Personal::all();

        $users = User::all();

        foreach ($notifications as $key => $notification) {

            $arrays = unserialize($notification->list);

            $list = [];

            if ($notification->to == 'مشتری ها') {

                foreach ($arrays as $kem => $array) {

                    if ($array == '0') {
                        $list[] = 'همه';
                        break;
                    }

                    $cunsomer = Cunsomer::find($array);

                    $list[] = $cunsomer->customer_lastname;

                }

            }else if($notification->to == 'خدمت رسان ها'){

                foreach ($arrays as $kem => $array) {

                    if ($array == '0') {
                        $list[] = 'همه';
                        break;
                    }

                    $personal = Personal::find($array);

                    $list[] = $personal->personal_lastname;

                }


            } else {

                foreach ($arrays as $kem => $array) {

                    if ($array == '0') {
                        $list[] = 'همه';
                        break;
                    }

                    $user = User::find($array);

                    $list[] = $user->user_lastname;

                }

            }

            $notifications[$key]['list'] = $list;

        }

        return view('User.Notifications.Notification', compact(['notifications', 'cunsomers', 'personals', 'users']));
    }

    public function submit(Request $request)
    {

        if ($request->to == 'مشتری ها') {

            $validation = $this->getValidationFactory()->make($request->all(), [
                'title' => 'required',
                'text' => 'required',
                'to' => 'required',
                'how' => 'required',
                'cunsomers' => 'required',

            ]);

        } else if ($request->to == 'خدمت رسان ها') {

            $validation = $this->getValidationFactory()->make($request->all(), [
                'title' => 'required',
                'text' => 'required',
                'to' => 'required',
                'how' => 'required',
                'personals' => 'required',

            ]);

        } else {

            $validation = $this->getValidationFactory()->make($request->all(), [
                'title' => 'required',
                'text' => 'required',
                'to' => 'required',
                'how' => 'required',
                'users' => 'required',

            ]);

            if ($request->how == 'نوتیفیکیشن' || $request->how == 'هردو') {

                alert()->error('امکان ارسال نوتیفیکیشن برای کاربران پنل وجود ندارد', 'ثبت صورت نپذیرفت')->autoclose(2000);
                return back();
            }

        }

        if ($validation->fails()) {

            //return response()->json(['messsage' => 'invalid'], 400);
            alert()->error('باید تمامی فیلد های الزامل را پر کنید!', 'ثبت صورت نپذیرفت')->autoclose(2000);
            //return 'error';
            return back();

        }

        $notification = new Notifications;

        $notification->title = $request->title;
        $notification->text = $request->text;
        $notification->to = $request->to;
        $notification->how = $request->how;
        $notification->smstemplate = $request->smstemplate;
        $notification->desc = $request->desc;
        if ($request->datesend) {

            $date = substr($this->convertDate($request->datesend), 0, 10) . ' ' . $request->timesend . ':00:00';
            $notification->send = $date;
        }
        if ($request->to == 'مشتری ها') {
            $list = serialize($request->cunsomers);

        } else if ($request->to == 'خدمت رسان ها') {
            $list = serialize($request->personals);
        } else {

            $list = serialize($request->users);

        }

        
        if (auth()->user()->hasRole('admin_panel')) {


        } else {
           

            foreach (auth()->user()->roles as $key => $role) {
                if ($role->broker == 1) {
                    $notification->broker = $role->name;
                } 
            }

        }

        $notification->list = $list;

        $notification->save();

        alert()->success('نوتیفیکیشن با موفقیت انجام گردید!', 'ثبت موفق')->autoclose(2000);

        return back();
    }

    public function send(Request $request)
    {

        foreach ($request->array as $notificationsid) {

            $notification = Notifications::find($notificationsid);

            if ($notification->sent == 1) {

                alert()->error('این نوتیفیکیشن قبلا ارسال شده است', 'امکان ارسال مجدد این نوتیفیکیشن وجود ندارد')->autoclose(5000);

                return 'failed';
            } else if (!is_null($notification->send)) {
                alert()->error('این نوتیفیکیشن برای ارسال زمان بندی شده است', 'امکان ارسال این نوتیفیکیشن وجود ندارد')->autoclose(5000);

                return 'failed';
            }

            $notification->send = date('Y-m-d H:i:s');
            $notification->sent = 1;

            $array = unserialize($notification->list);

            if ($notification->to == 'مشتری ها') {

                foreach ($array as $key => $fard) {

                    if ($fard == 0) {
                        $members = Cunsomer::all();

                    } else {
                        $members[] = Cunsomer::find($fard);

                    }
                }

                    foreach ($members as $member) {

                        if ($notification->how == 'پیامک') {

                            $this->sendsms($member->customer_mobile, $notification->text, $notification->smstemplate);

                        } else if ($notification->how == 'نوتیفیکیشن') {

                            $this->sendnotification($member->firebase_token / $notification->title, $notification->text);

                        } else {

                            $this->sendsms($member->customer_mobile, $notification->text, $notification->smstemplate);
                            $this->sendnotification($member->firebase_token, $notification->title, $notification->text);

                        }

                    }

                
            } else if ($notification->to == 'خدمت رسان ها') {

                foreach ($array as $key => $fard) {

                    if ($fard == 0) {

                        if($notification->broker){

                                    foreach (Service::where('service_role', $role->name)->get() as $key => $service) {
                                        foreach ($service->personal as $key => $personal) {
                                            $personalslist[] = $personal;
                                        }
                                    }
                                
                                    $ids=[];
                                    foreach($personalslist as $key=>$personal){
                            
                                        $id=$personal->id;
                                
                                        $repe=false;
                            
                                        for($x = 0; $x < count($ids); $x++){
                            
                                            if($ids[$x]==$id){
                            
                                            $repe=true;
                            
                                            break;
                                            }
                            
                                        }
                            
                                        if($repe){
                                            $members[]=$personal;
                            
                                        }
                            
                            
                                        $ids[]=$id;
                                    }
                            
                            



                        }else{

                            $members = Personal::all();


                        }



                    } else {
                        $members[] = Personal::find($fard);

                    }
                }

                    foreach ($members as $member) {

                        if ($notification->how == 'پیامک') {

                            $this->sendsms($member->personal_mobile, $notification->text, $notification->smstemplate);

                        } else if ($notification->how == 'نوتیفیکیشن') {

                            $this->sendnotification($member->firebase_token, $notification->title, $notification->text);

                        } else {

                            $this->sendsms($member->personal_mobile, $notification->text, $notification->smstemplate);
                            $this->sendnotification($member->firebase_token, $notification->title, $notification->text);

                        }

                    }

                

            } else {

                
                foreach ($array as $key => $fard) {

                    if ($fard == 0) {
                        $members = User::all();

                    } else {
                        $members[] = User::find($fard);

                    }
                }

                    foreach ($members as $member) {

                        if ($notification->how == 'پیامک') {

                            $this->sendsms($member->user_mobile, $notification->text, $notification->smstemplate);

                        } else {

                            $pannelnotification=new PannelNotifications;

                            $pannelnotification->title=$notification->title;
                            $pannelnotification->text=$notification->text;
                            $pannelnotification->users_id=$member->id;
                            $pannelnotification->notifications_id=$notification->id;

                            $pannelnotification->save();

                        }

                    }

                

            }

            $notification->update();

            $members=[];
        }


        alert()->success('با موفقیت ارسال گردید', 'ارسال موفق')->autoclose(2000);
        return 'success';
    }

    public function sendsms($phone, $text, $template)
    {

        $apikey = '5079544B44782F41475237506D6A4C46713837717571386D6D784636486C666D';

        $receptor = $phone;
        $token = $text;
        $template = $template;
        $api = new \Kavenegar\KavenegarApi($apikey);

        try {
            $api->VerifyLookup($receptor, $token, null, null, $template);
        } catch (\Kavenegar\Exceptions\ApiException $e) {

            //return response()->json(['message' => 'مشکل پنل پیامکی پیش آمده است =>' . $e->errorMessage()], 400);
            return response()->json(['code' => $token, 'error' => 'مشکل پنل پیامکی پیش آمده است =>' . $e->errorMessage(),
            ], 500);

        } catch (\Kavenegar\Exceptions\HttpException $e) {

            return response()->json(['code' => $token, 'error' => 'مشکل اتصال پیش امده است =>' . $e->errorMessage()], 500);

        }

        return response()->json(['code' => $token], 200);
        // return response()->json(['data'=> ['code' => $token] ],200);

    }

    public function sendnotification($firebasetoken, $title, $text)
    {

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $text,
            'sound' => true,
        ];

        $extraNotificationData = ["message" => $title, "moredata" => $title];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to' => $firebasetoken, //single token
            'notification' => $notification,
            'data' => $extraNotificationData,
        ];

        $serverkey = env('FIREBASE_LEGACY_SERVER_KEY');

        $headers = [
            'Authorization: key=' . $serverkey,
            'Content-Type: application/json',
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

    public function delete(Request $request)
    {
        //dd($request);
        foreach ($request->array as $notificationsid) {
            //$checkout = CheckoutPersonals::find($checkoutid);

            $notification = Notifications::find($notificationsid);

            $notification->delete();

        }

        //return 'error';
        //return back;
        alert()->success('با موفقیت حذف گردید', 'حذف موفق')->autoclose(2000);
        return 'success';
    }
}
