<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function generateRandomString($mobile,$date,$orderid) {
       
       
        $mobile_str = substr($mobile,-4);
      
        $randomString = 'T-' . $mobile_str . ' I-' . $orderid . ' D-'. $date ;
        return $randomString;
    }
    public function convertDate($date)
    {

        $date_array=explode('/',$date);
        $year = $date_array[0];
        $month = $date_array[1];
        $day = $date_array[2];
        if(strlen($month) == 1){
            $month = '0'.$month;
        }
        if(strlen($day) == 1){
            $day = '0'.$day;
        }

        $new_date_array = array($year,$month,$day);
        $new_date_string = implode('/',$new_date_array);
        $carbon = \Morilog\Jalali\CalendarUtils::createCarbonFromFormat('Y/m/d', $new_date_string );
        
        return $carbon;
    }
}
