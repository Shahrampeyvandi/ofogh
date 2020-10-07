<?php

namespace App\Http\Controllers\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notifications\WorkerappNotifications;
use Spatie\Permission\Models\Role;

class WorkerappNotificationsController extends Controller
{
    public function index(){


        $notifications=WorkerappNotifications::all();

        $roles=Role::all();

        foreach($notifications as $key=>$notification){

            $names=unserialize($notification->brokers);



            $notifications[$key]->brokers=$names;

        }

        return view('User.Notifications.WorkerAppNotifications',compact(['notifications','roles']));
    }


    public function submit(Request $request){

        if($request->group == 'همه'){

            $validation = $this->getValidationFactory()->make($request->all(), [
                'title' => 'required',
                'text' => 'required',
                'group' => 'required',
    
            ]);

        }else{

        
        $validation = $this->getValidationFactory()->make($request->all(), [
            'title' => 'required',
            'text' => 'required',
            'group' => 'required',
            'roles' => 'required',

        ]);
        }
        if ($validation->fails()) {

            //return response()->json(['messsage' => 'invalid'], 400);
            alert()->error('باید تمامی فیلد های الزامل را پر کنید!', 'ثبت صورت نپذیرفت')->autoclose(2000);
            //return 'error';
            return back();

        }




        $appnotification=new WorkerappNotifications;
        $appnotification->title=$request->title;
        $appnotification->text=$request->text;
        $appnotification->link=$request->link;
        $appnotification->group=$request->group;
        $appnotification->description=$request->desc;


        if($request->group == 'کارگذاران'){
        
            $appnotification->brokers=serialize($request->roles);

        
        }

        $appnotification->save();


        return back();
    }
}
