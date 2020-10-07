<?php

namespace App\Http\Schedules;

use App\Models\Notifications\Notifications;
use App\Models\Personals\Personal;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Notifications\PannelNotifications;
use App\Models\User;
use App\Models\Services\Service;
use Spatie\Permission\Models\Role;

class NotificationScheduler
{

  
    public function __invoke()
    {
 echo date('Y-m-d H:00:00')."asdas".PHP_EOL;

        $notifications=Notifications::where('sent',0)->where('send',date('Y-m-d H:00:00'))->get();
        if(count($notifications)){


            foreach($notifications as $notification){

            if($notification->sent == 0){
            //$notification = Notifications::find($notification->id);

         

       
            $notification->sent=1;
            $notification->send = date('Y-m-d H:i:s');



            $array = unserialize( $notification->list );

            if($notification->to == 'مشتری ها'){

                foreach($array as $key=>$fard){

                    if($fard == 0){
                        $members = Cunsomer::all();

                    }else{
                        $members[] = Cunsomer::find($fard);

                    }
                }

                    foreach($members as $member){

                    if($notification->how == 'پیامک'){

                        $this->sendsms($member->customer_mobile,$notification->text,$notification->smstemplate);

                    }else if($notification->how == 'نوتیفیکیشن'){

                        $this->sendnotification($member->firebase_token/$notification->title,$notification->text);


                    }else{

                        $this->sendsms($member->customer_mobile,$notification->text,$notification->smstemplate);
                        $this->sendnotification($member->firebase_token,$notification->title,$notification->text);


                    }


                }
            
            }else if($notification->to == 'خدمت رسان ها'){


                foreach($array as $key=>$fard){

                    if($fard == 0){

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


                    }else{
                        $members[] = Personal::find($fard);

                    }
                }

                    foreach($members as $member){



                    if($notification->how == 'پیامک'){

                        $this->sendsms($member->personal_mobile,$notification->text,$notification->smstemplate);

                    }else if($notification->how == 'نوتیفیکیشن'){

                        $this->sendnotification($member->firebase_token,$notification->title,$notification->text);


                    }else{

                        $this->sendsms($member->personal_mobile,$notification->text,$notification->smstemplate);
                        $this->sendnotification($member->firebase_token,$notification->title,$notification->text);


                    }


                }

            

            }else{


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

                            echo date('Y-m-d H:00:00')."sendnotification".PHP_EOL;

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
        }
    }
}

        
        
    }





    public function sendsms($phone , $text,$template){
        echo date('Y-m-d H:00:00')."sms".PHP_EOL;

        $apikey = '5079544B44782F41475237506D6A4C46713837717571386D6D784636486C666D';

        $receptor = $phone;
        $token = $text;
        $template = $template;
        $api = new \Kavenegar\KavenegarApi($apikey);

        try {
            $api->VerifyLookup($receptor, $token, null, null, $template);
        } catch (\Kavenegar\Exceptions\ApiException $e) {

            //return response()->json(['message' => 'مشکل پنل پیامکی پیش آمده است =>' . $e->errorMessage()], 400);
            return response()->json(['code'=> $token ,'error' => 'مشکل پنل پیامکی پیش آمده است =>' . $e->errorMessage()
            ],500);

        } catch (\Kavenegar\Exceptions\HttpException $e) {

            return response()->json(['code'=> $token,'error' => 'مشکل اتصال پیش امده است =>' . $e->errorMessage()],500);

        }

        return response()->json(['code' => $token], 200);
       // return response()->json(['data'=> ['code' => $token] ],200);

    }

    public function sendnotification($firebasetoken ,$title, $text){
        echo date('Y-m-d H:00:00')."sendnotification".PHP_EOL;

        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $notification = [
            'title' => $text,
            'sound' => true,
        ];

        $extraNotificationData = ["message" => $title, "moredata" => $title];

        $fcmNotification = [
            //'registration_ids' => $tokenList, //multple token array
            'to'        => $firebasetoken, //single token
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
