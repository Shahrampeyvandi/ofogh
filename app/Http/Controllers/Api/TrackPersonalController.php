<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Personals\Personal;
use App\Models\Personals\PersonalsPosition;

class TrackPersonalController extends Controller
{
    public function sendpositions(Request $request)
    {


        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');

     
       
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $position = new PersonalsPosition();
      
        $position->personal_id=$personal->id;

         $position->tool=$request->lan;

         $position->arz=$request->lat;
         $position->save();


        return response()->json([
      ], 200);


    }
  }
