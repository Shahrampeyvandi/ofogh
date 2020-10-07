<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Acounting\CheckoutPersonals;
use App\Models\Acounting\Transations;
use App\Models\Acounting\UserAcounts;
use App\Models\Personals\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;


class CheckoutExport implements FromArray
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function array(): array
    {

        $checkouts = DB::table('checkout_personals')
        ->where('payed', '=', '0')
        ->get();

        $person=array();
        foreach ($checkouts as $key=>$checkout) {
            $useracount = UserAcounts::find($checkout->user_acounts_id);

         $person[$key]=Personal::find($useracount->personal_id);

         //dd($person);

        }
        //dd($person);


        $checkoutsArray = []; 

        // Define the Excel spreadsheet headers
        $checkoutsArray[] = ['ردیف','شماره شبا','نام خانوادگی','نام','مبلغ','شماره همراه','شناسه تسویه'];
    

        foreach ($person as $key=>$pers) {

            $per=[
                'ردیف'=>$key+1,
                'شماره شبا'=>$checkouts[$key]->shaba,
                'نام خانوادگی'=>$person[$key]->personal_lastname,
                'نام'=>$person[$key]->personal_firstname,
                'مبلغ'=>$checkouts[$key]->amount,
                'شماره همراه'=>$person[$key]->personal_mobile,
                'شناسه تسویه'=>$checkouts[$key]->id
            ];
            $checkoutsArray[] = $per;

        }


        return $checkoutsArray;

        // return [
        //     [185, 2, 3],
        //     [4, 585, 6]
        // ];
    }
    // public function collection()
    // {

    //     $checkouts = DB::table('checkout_personals')
    //     ->where('payed', '=', '0')
    //     ->get();

    //     $person=array();
    //     foreach ($checkouts as $key=>$checkout) {
    //         $useracount = UserAcounts::find($checkout->user_acounts_id);

    //      $person[$key]=Personal::find($useracount->personal_id);

    //      //dd($person);

    //     }
    //     //dd($person);


    //     $checkoutsArray = []; 

    //     // Define the Excel spreadsheet headers
    //     //$checkoutsArray[] = ['شناسه تسویه', 'شماره همراه','نام خانوادگی','مبلغ','شماره شبا'];
    

    //     foreach ($person as $key=>$pers) {

    //         $per=[

    //             'شناسه تسویه'=>$checkouts[$key]->id,
    //             'شماره همراه'=>$person[$key]->personal_mobile,
    //             'نام خانوادگی'=>$person[$key]->personal_lastname,
    //             'مبلغ'=>$checkouts[$key]->amount,
    //             'شماره شبا'=>$checkouts[$key]->shaba
    //         ];
    //         $checkoutsArray[] = $per;

    //     }

    //     return new Collection([

    //         [1, 2, 3],
    //         [4, 5, 6]
    //     ]);
    //     //return CheckoutPersonals::all();
    // }
}
