<?php

namespace App\Http\Controllers\Acounting;

use App\Http\Controllers\Controller;
use App\Models\Acounting\Transations;
use App\Models\Acounting\UserAcounts;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Personals\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Services\Service;
use Spatie\Permission\Models\Role;

class TransactionsController extends Controller
{
    public function personals()
    {
        $personalslist = [];

        if (auth()->user()->hasRole('admin_panel')) {

            $personals = Personal::all();

        } else {
            foreach (auth()->user()->roles as $key => $role) {
                if ($role->broker == 1) {
                    foreach (Service::where('service_role', $role->name)->get() as $key => $service) {
                        foreach ($service->personal as $key => $personal) {
                            $personalslist[] = $personal;
                        }
                    }
                } else {
                    $role_name = Role::where('id', $role->sub_broker)->first()->name;
                    $user = User::whereHas('roles', function ($q) use ($role_name) {
                        $q->where('name', $role_name);
                    })->first();

                    foreach (Service::where('service_role', $role_name)->get() as $key => $service) {
                        foreach ($service->personal as $key => $personal) {
                            $personalslist[] = $personal;
                        }
                    }
                }
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
                $personals[]=$personal;

            }


            $ids[]=$id;
        }



        return view('User.Acounting.TransactionsPersonals', compact('personals'));
    }

    public function customers()
    {

        $cansomers = Cunsomer::all();

        return view('User.Acounting.TransactionsCustomers', compact('cansomers'));
    }

    public function submit(Request $request)
    {

        $validation = $this->getValidationFactory()->make($request->all(), [
            'user_acounts_id' => 'required',
            'amount' => 'required',

        ]);

        // if ($validation->fails()) {

        //     //return response()->json(['messsage' => 'invalid'], 400);
        //     alert()->error('باید تمامی فیلد های الزامل را پر کنید!', 'تراکنش صورت نپذیرفت')->autoclose(2000);
        //     //return 'error';
        //     return back();

        // }

        $transaction = new Transations();

        $transaction->user_acounts_id = $request->useracountid;

        $transaction->type = $request->type;
        $transaction->for = $request->for;
        if ($request->order_unique_code) {
            $transaction->order_unique_code = $request->order_unique_code;
        }
        $transaction->amount = $request->amount;
        if ($request->from_to) {
            $transaction->from_to = $request->from_to;
        }
        if ($request->description) {
            $transaction->description = $request->description;
        }

        $acount = UserAcounts::find($request->useracountid);

        //$transaction->save();

        if ($transaction->type == 'برداشت') {
            //dd($transaction);

            if ($acount->cash < $transaction->amount) {

                alert()->error('متاسفانه این حساب موجودی کافی ندارد!', 'تراکنش نا موفق')->autoclose(2000);

                return back();
            }

            $acount->cash = $acount->cash - $transaction->amount;

        } else {
            $acount->cash = $acount->cash + $transaction->amount;

        }

        // dd($acount);

        $transaction->save();

        $acount->update();

        alert()->success('تراکنش با موفقیت انجام گردید!', 'تراکنش موفق')->autoclose(2000);

        return back();
    }

}
