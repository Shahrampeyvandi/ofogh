

<?php

namespace App\Http\Controllers\Acounting;

use App\Http\Controllers\Controller;
use App\Models\Acounting\CheckoutPersonals;
use App\Models\Acounting\Transations;
use App\Models\Acounting\UserAcounts;
use App\Models\Personals\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\CheckoutExport;
use Maatwebsite\Excel\Facades\Excel;


class CheckoutPersonalsController extends Controller
{
    public function index()
    {

        $personals = Personal::all();

        return view('User.Acounting.CheckoutPersonals', compact('personals'));

    }

    public function submit(Request $request)
    {


        $validation = $this->getValidationFactory()->make($request->all(), [
            'personals' => 'required',


        ]);


        if ($validation->fails()) {

            //return response()->json(['messsage' => 'invalid'], 400);
            alert()->error('باید حساب یکی از خدمت گذاران را انتخاب کنید.', 'تسویه ایجاد نشد')->autoclose(2000);
            return 'error';
            //return back();

        }

        $personals = Personal::all();


        if($request->personals = 'all'){

            //dd($personals[1]->useracounts[0]);

            foreach($personals as $person){


                if(0>=$person->useracounts[1]->cash){
                    //alert()->error('موجودی این خدمت گذار منفی شده!', 'تسویه ایجاد نشد')->autoclose(2000);
                }else{
                $checkout = new CheckoutPersonals();
                $checkout->user_acounts_id = $person->useracounts[1]->id;
                $checkout->payed = '0';
                $checkout->amount = $person->useracounts[1]->cash;
                $checkout->shaba = 'IR4354354354353';
        
                // dd($checkout);
        
                $person->useracounts[1]->checkoutpersonals()->save($checkout);
                //}
                }
        


            }


        }else{

        



        //foreach ($request->personals as $person){

        $personal = Personal::find($request->personals);

        //$personal->useracounts->checkoutpersonals();

        // $checkout=$personal->useracounts[0]->checkoutpersonals;

        // ->create{[
        //     'user_acounts_id' => $personal->useracounts[0]->id,
        //     'payed'=>'0',
        //     'type' => 'برداشت',
        //     'amount'=>$personal->useracounts[0]->cash,
        //     'shaba'=>'IR565645435435435'
        // ]};

        if(0>=$personal->useracounts[1]->cash){
            alert()->error('موجودی این خدمت گذار منفی شده!', 'تسویه ایجاد نشد')->autoclose(2000);
        }else{
        $checkout = new CheckoutPersonals();
        $checkout->user_acounts_id = $personal->useracounts[1]->id;
        $checkout->payed = '0';
        $checkout->amount = $personal->useracounts[1]->cash;
        $checkout->shaba = 'IR4354354354353';

        // dd($checkout);

        $personal->useracounts[1]->checkoutpersonals()->save($checkout);
        //}
        }

    }
        return back();
        //return sala;
    }

    public function pay(Request $request)
    {

        //$personals = Personal::all();

         foreach ($request->array as $checkoutid) {
            //$checkout = CheckoutPersonals::find($checkoutid);
        
            $checkout = CheckoutPersonals::find($checkoutid);

            $bool = $checkout->payed;
             if($bool){
            //    // return 'failed';
                alert()->error('این پرداخت قبلا انجام شده است', 'پرداخت نا موفق')->autoclose(2000);
             return 'error';
            }else{

            //dd($checkout);
            $useracount = UserAcounts::find($checkout->user_acounts_id);

            if(0> $useracount->cash){
                alert()->error('موجودی این خدمت گذار منفی شده!', 'پرداخت نا موفق')->autoclose(2000);
             return 'error';
            }else{

            //$tranatioin=$useracount->tranations;
            $tranatioin = new Transations();

            $tranatioin->user_acounts_id = $useracount->id;

            $tranatioin->type = 'برداشت';

            $tranatioin->for = 'تسویه';
            $tranatioin->amount = $checkout->amount;
            $tranatioin->from_to = $checkout->shaba;

            $useracount->cash = $useracount->cash - $checkout->amount;

           // dd($useracount);
           //dd($tranatioin);

           $tranatioin->save();

           // $useracount->transactions()->save($tranatioin);

             $checkout->payed='1';
             $checkout->transations_id = $tranatioin->id;
             $checkout->payed_at = $tranatioin->created_at;

             $checkout->update();

            
             $useracount->update();
            }
            }
         }
        
        //return 'error';
        //return back;
        alert()->success('پرداخت با موفقیت انجام گردید!', 'پرداخت موفق')->autoclose(2000);
        return 'success';
    }

    public function delete(Request $request)
    {
        
        foreach ($request->array as $checkoutid) {
            //$checkout = CheckoutPersonals::find($checkoutid);
        
            $checkout = CheckoutPersonals::find($checkoutid);

            $checkout->delete();

          

            
         }
        
        //return 'error';
        //return back;
        alert()->success('با موفقیت حذف گردید', 'حذف موفق')->autoclose(2000);
        return 'success';
    }

    public function export()
    {

        // $checkouts = DB::table('checkout_personals')
        // ->where('payed', '=', '0')
        // ->get();

        // $person=array();
        // foreach ($checkouts as $key=>$checkout) {
        //     $useracount = UserAcounts::find($checkout->user_acounts_id);

        //  $person[$key]=Personal::find($useracount->personal_id);

        //  //dd($person);

        // }
        // //dd($person);


        // $checkoutsArray = []; 

        // // Define the Excel spreadsheet headers
        // $checkoutsArray[] = ['شناسه تسویه', 'شماره همراه','نام خانوادگی','مبلغ','شماره شبا'];
    

        // foreach ($person as $key=>$pers) {

        //     $per=[

        //         'شناسه تسویه'=>$checkouts[$key]->id,
        //         'شماره همراه'=>$person[$key]->personal_mobile,
        //         'نام خانوادگی'=>$person[$key]->personal_lastname,
        //         'مبلغ'=>$checkouts[$key]->amount,
        //         'شماره شبا'=>$checkouts[$key]->shaba
        //     ];
        //     $checkoutsArray[] = $per;

        // }
    
        // //dd($checkoutsArray);
    

        // Excel::create('payments', function($excel) use ($checkoutsArray) {

        //     // Set the spreadsheet title, creator, and description
        //     $excel->setTitle('Payments');
        //     $excel->setCreator('Laravel')->setCompany('WJ Gilmore, LLC');
        //     $excel->setDescription('payments file');
    
        //     // Build the spreadsheet, passing in the payments array
        //     $excel->sheet('sheet1', function($sheet) use ($checkoutsArray) {
        //         $sheet->fromArray($checkoutsArray, null, 'A1', false, false);
        //     });
    
        // })->download('xlsx');
    
        // $export = new CheckoutExport([
        //     [1, 2, 3],
        //     [4, 3, 6]
        // ]);

        //return Excel::download($export, 'invoices.xlsx');
        return Excel::download(new CheckoutExport, 'checkouts.xlsx');

        //return Excel::download(new CheckoutExport, 'excel.xlsx');

    
    }
}
