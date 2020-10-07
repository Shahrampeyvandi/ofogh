<?php

namespace App\Http\Controllers\Notifications;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notifications\PannelNotifications;
use App\Models\User;

class PannelNotificationsController extends Controller
{
    public function index(){

        $user=auth()->user();

        $notifications=PannelNotifications::where('users_id',$user->id)->where('read',0)->get();
        if(is_null($notifications)){
            $notifications=[];
        }

        return response()->json(
           $notifications
        ,200);

    }

    public function get(Request $request){

        



        $notification=PannelNotifications::find($request->id);

        $notification->read=1;

        $notification->update();
        
        $time=\Morilog\Jalali\Jalalian::forge($notification->created_at)->format('%Y-%m-%d H:i:s');

        $notification['time']=$time;


        return response()->json(
           $notification
        ,200);

    }
}
