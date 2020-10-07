<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\Personals\Personal;
use Illuminate\Support\Facades\DB;
use App\Models\Personals\PersonalsPosition;
use App\Http\Controllers\Controller;
use App\Models\Services\ServiceCategory;
use App\Models\Orders\Order;
use Carbon\Carbon;
use App\Models\Services\Service;


class TrackPersonalController extends Controller
{

    public function OnlinePersonals(Request $request)
    {

      $servicename=null;
      $online=[];
      $person=[];

        if($request->service){
            

            $service=Service::find($request->service);

            $servicename=$service->service_title;
            $personals=$service->personal;

            $key=0;
            foreach($personals as $personal){

                $fff=null;
                //$fff=$personal->positions->where('created_at', '>', Carbon::now()->subMinutes(10))->first();
                $fff=PersonalsPosition::where('personal_id',$personal->id)
                ->where('created_at', '>', Carbon::now()->subMinutes(10))
                ->latest()
                ->first();

                // $fff=DB::table('personals_positions')

                // ->where('personal_id',11)

                // ->where('created_at', '>', Carbon::now()->subMinutes(10))

                //     ->get()
                //     ->keyBy('personal_id');

                if($fff){
                

                    $online[$key]=$fff;
                    $person[$key]=$personal;

                    //dd($fff);

                    $key=+1;
                }

            }

    //dd($online);


        }else{


        $online = DB::table('personals_positions')

            ->where('created_at', '>', Carbon::now()->subMinutes(10))
        
     


                ->get()
                ->keyBy('personal_id');


             
        $person=array();
        foreach ($online as $key=>$personal) {
            $person[$key] = Personal::find($personal->personal_id);

        }
    }

    if (auth()->user()->hasRole('admin_panel')) {
        $orders = Order::latest()->get();
        $services = Service::all();
        $category_parent_list = ServiceCategory::where('category_parent', 0)->get();
        $count = ServiceCategory::where('category_parent', 0)->count();
        $list = '<option data-parent="0" value="0" class="level-1">بدون دسته بندی</option>';
        foreach ($category_parent_list as $key => $item) {

            $list .= '<option data-id="' . $item->id . '" value="' . $item->id . '" class="level-1">' . $item->category_title . ' 
         ' . (count(ServiceCategory::where('category_parent', $item->id)->get()) ? '&#xf104;  ' : '') . '
        </option>';
            if (ServiceCategory::where('category_parent', $item->id)->count()) {
                $count += ServiceCategory::where('category_parent', $item->id)->count();
                foreach (ServiceCategory::where('category_parent', $item->id)->get() as $key1 => $itemlevel1) {
                    $list .= '<option data-parent="' . $item->id . '" value="' . $itemlevel1->id . '" class="level-2">' . $itemlevel1->category_title . '
             ' . (count(ServiceCategory::where('category_parent', $itemlevel1->id)->get()) ? '&#xf104;  ' : '') . '
             </option>';


                    if (ServiceCategory::where('category_parent', $itemlevel1->id)->count()) {
                        $count += ServiceCategory::where('category_parent', $itemlevel1->id)->count();
                        foreach (ServiceCategory::where('category_parent', $itemlevel1->id)->get() as $key2 => $itemlevel2) {
                            $list .= '<option data-parent="' . $itemlevel1->id . '" value="' . $itemlevel2->id . '" class="level-3">' . $itemlevel2->category_title . '
                 ' . (count(ServiceCategory::where('category_parent', $itemlevel2->id)->get()) ? '&#xf104;  ' : '') . '
                 </option>';


                            if (ServiceCategory::where('category_parent', $itemlevel2->id)->count()) {
                                $count += ServiceCategory::where('category_parent', $itemlevel2->id)->count();
                                foreach (ServiceCategory::where('category_parent', $itemlevel2->id)->get() as $key3 => $itemlevel3) {
                                    $list .= '<option data-parent="' . $itemlevel2->id . '" value="' . $itemlevel3->id . '" class="level-4">' . $itemlevel3->category_title . '
                     ' . (count(ServiceCategory::where('category_parent', $itemlevel3->id)->get()) ? '&#xf104;  ' : '') . '
                     </option>';

                                    if (ServiceCategory::where('category_parent', $itemlevel3->id)->count()) {
                                        $count += ServiceCategory::where('category_parent', $itemlevel3->id)->count();
                                        foreach (ServiceCategory::where('category_parent', $itemlevel3->id)->get() as $key4 => $itemlevel4) {
                                            $list .= '<option data-parent="' . $itemlevel3->id . '" value="' . $itemlevel4->id . '" class="level-4">' . $itemlevel4->category_title . '
                             
                             </option>';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    } else {
        $category_parent_list = ServiceCategory::where('category_parent', 0)->get();
        $count = ServiceCategory::where('category_parent', 0)->count();
        $list = '<option data-parent="0" value="0" class="level-1">بدون دسته بندی</option>';
        foreach ($category_parent_list as $key => $item) {
            $list .= '<option data-id="' . $item->id . '" value="' . $item->id . '" class="level-1">' . $item->category_title . ' 
         ' . (count(ServiceCategory::where('category_parent', $item->id)->get()) ? '&#xf104;  ' : '') . '
        </option>';
            foreach (ServiceCategory::where('category_parent', $item->id)->get() as $key => $subitem) {
                $list .= '<option data-parent="' . $item->id . '" value="' . $subitem->id . '" class="level-2">' . $subitem->category_title . '</option>';
            }
        }
        if (auth()->user()->roles->first()->broker == 1) {
            $services = Service::where('service_role', auth()->user()->roles->first()->name)->get();
            $service_array =   Service::where('service_role', auth()->user()->roles->first()->name)->pluck('id')->toArray();
            $orders = Order::whereIn('service_id', $service_array)->get();
          
        }
        if (auth()->user()->roles->first()->sub_broker !== null) {
            $role_name = Role::where('id', auth()->user()->roles->first()->sub_broker)->name;
            $services = Service::where('service_role', $role_name)->get();
            $service_array = Service::where('service_role', $role_name)->pluck('id')->toArray();
            $orders = Order::whereIn('service_id', $service_array)->get();
        }
    }
         
    
          

        return view('User.OnlinePersonals', compact('online','person','list','count','servicename'));
    }

    public function TrackPersonals(Request $request)
    {

        $khedmatResans = Personal::all();

        $id = null;
        $khedmatResan = null;
        $servicepositions= null;
        //  $khedmatResan = Personal::find($request->personal);

        if (!empty($request->date)) {

            $id = [$request->personal, $request->date];

            $khedmatResan = DB::table('personals_positions')
                ->where('personal_id', '=', $request->personal)
                ->whereDate('created_at', '=', $this->convertDate($request->date))
                ->get();


                $personal=Personal::find($request->personal);
                $maindate=substr($this->convertDate($request->date),0,10);
                $orders=$personal->order;

                $servicepositions=[];

                foreach($orders as $key=>$order){

                    $detail=$order->orderDetail;

                    if($detail){

                        if($detail->order_start_time_positions){
                            
                            $date=substr($detail->order_start_time,0,10);

                            if($maindate == $date){

                            $positions=unserialize($detail->order_start_time_positions);

                            $post['lat']=$positions[0];
                            $post['lon']=$positions[1];
                            $post['type']='شروع کار';
                            $post['time']=substr($detail->order_start_time,11);
                            $post['code']=$order->order_unique_code;
                            $servicepositions[]=$post;
                        }
                    }



                        if($detail->order_end_time_positions){

                            $date=substr($detail->order_end_time,0,10);

                            if($maindate == $date){


                            $positions=unserialize($detail->order_end_time_positions);

                            $post['lat']=$positions[0];
                            $post['lon']=$positions[1];
                            $post['type']='پایان کار';
                            $post['time']=substr($detail->order_end_time,11);
                            $post['code']=$order->order_unique_code;
                            $servicepositions[]=$post;
                        }
                    }
                    }
                }


               // dd($servicepositions);

        }


        // $users = DB::table('personals_positions')
        //    ->where('name', '=', 'John')
        //    ->where(function ($query) {
        //        $query->orWhere('personal_id', '=', '1')
        //        ->whereDate('created_at', '2020-02-04');

        //    })
        //    ->get();

        return view('User.TrackPersonals', compact(['khedmatResans', 'khedmatResan', 'id','servicepositions']));
    }

}
