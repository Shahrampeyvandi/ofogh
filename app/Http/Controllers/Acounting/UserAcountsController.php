<?php

namespace App\Http\Controllers\Acounting;

use App\Http\Controllers\Controller;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Personals\Personal;
use App\Models\Acounting\UserAcounts;
use App\Models\Services\Service;
use Spatie\Permission\Models\Role;

class UserAcountsController extends Controller
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

        return view('User.Acounting.UserAccountsPersonals', compact('personals'));
    }

    public function customers()
    {

        $personals = Personal::all();

        $cansomers = Cunsomer::all();

        $useracounts=UserAcounts::all();

        return view('User.Acounting.UserAccountsCustomers', compact(['personals','cansomers','useracounts']));
    }
}
