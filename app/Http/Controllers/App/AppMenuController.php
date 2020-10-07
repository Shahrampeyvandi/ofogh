<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Services\ServiceCategory;
use App\Models\Services\Service;
use App\Models\Store\Store;
use App\Models\App\AppMenu;



class AppMenuController extends Controller
{
    public function index()
    {

        $category_parent_list = ServiceCategory::where('category_parent',0)->get();
       $count = ServiceCategory::where('category_parent',0)->count();
        $list ='<option data-parent="0" value="0" >بدون دسته بندی</option>';
       foreach ($category_parent_list as $key => $item) {
           
           $list .= '<option data-id="'.$item->id.'" value="'.$item->id.'" class="level-1">'.$item->category_title.' 
            '.(count(ServiceCategory::where('category_parent',$item->id)->get()) ? '&#xf104;  ' : '' ).'
           </option>';
         if (ServiceCategory::where('category_parent',$item->id)->count()) {
             $count += ServiceCategory::where('category_parent',$item->id)->count();
            foreach (ServiceCategory::where('category_parent',$item->id)->get() as $key1 => $itemlevel1) {
                $list .= '<option data-parent="'.$item->id.'" value="'.$itemlevel1->id.'" class="level-2">'.$itemlevel1->category_title.'
                '.(count(ServiceCategory::where('category_parent',$itemlevel1->id)->get()) ? '&#xf104;  ' : '' ).'
                </option>';
                
                
             if (ServiceCategory::where('category_parent',$itemlevel1->id)->count()) {
                $count += ServiceCategory::where('category_parent',$itemlevel1->id)->count();
                foreach (ServiceCategory::where('category_parent',$itemlevel1->id)->get() as $key2 => $itemlevel2) {
                    $list .= '<option data-parent="'.$itemlevel1->id.'" value="'.$itemlevel2->id.'" class="level-3">'.$itemlevel2->category_title.'
                    '.(count(ServiceCategory::where('category_parent',$itemlevel2->id)->get()) ? '&#xf104;  ' : '' ).'
                    </option>';
                   
                   
                   if (ServiceCategory::where('category_parent',$itemlevel2->id)->count()) {
                    $count += ServiceCategory::where('category_parent',$itemlevel2->id)->count();
                    foreach (ServiceCategory::where('category_parent',$itemlevel2->id)->get() as $key3 => $itemlevel3) {
                        $list .= '<option data-parent="'.$itemlevel2->id.'" value="'.$itemlevel3->id.'" class="level-4">'.$itemlevel3->category_title.'
                        '.(count(ServiceCategory::where('category_parent',$itemlevel3->id)->get()) ? '&#xf104;  ' : '' ).'
                        </option>';
                    
                        if (ServiceCategory::where('category_parent',$itemlevel3->id)->count()) {
                            $count += ServiceCategory::where('category_parent',$itemlevel3->id)->count();
                            foreach (ServiceCategory::where('category_parent',$itemlevel3->id)->get() as $key4 => $itemlevel4) {
                                $list .= '<option data-parent="'.$itemlevel3->id.'" value="'.$itemlevel4->id.'" class="level-4">'.$itemlevel4->category_title.'
                                
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

      $all_categories = ServiceCategory::latest()->get();

      $services = Service::all();
      $stores = Store::all();
      $appmenus = AppMenu::all();


      foreach($appmenus as $keym=>$appmenu){

        $array = unserialize( $appmenu->item );

    
        if($appmenu->type == 'دسته بندی'){


            foreach($array as $keyn=>$arr){
    
                $category = ServiceCategory::where('id', $arr)->first();
    
    
                $array[$keyn]=$category['category_title'];
    

    
                
    
            }
    
            $appmenus[$keym]->item=$array;


        }else if($appmenu->type == 'خدمت های دسته'){


            foreach($array as $keyn=>$arr){
    
                $category = ServiceCategory::where('id', $arr)->first();
    
    
                $array[$keyn]=$category['category_title'];
    

    
                
    
            }
    
            $appmenus[$keym]->item=$array;


        }else if($appmenu->type == 'فروشگاه های دسته'){


            foreach($array as $keyn=>$arr){
    
                $category = ServiceCategory::where('id', $arr)->first();
    
    
                $array[$keyn]=$category['category_title'];
    

    
                
    
            }
    
            $appmenus[$keym]->item=$array;


        }else if($appmenu->type == 'خدمت'){



            foreach($array as $keyn=>$arr){
    

                $service = Service::where('id', $arr)->first();
    
    
                $array[$keyn]=$service['service_title'];
    

    
                
    
            }
    
            $appmenus[$keym]->item=$array;




        }else if($appmenu->type == 'فروشگاه'){


            foreach($array as $keyn=>$arr){
    
                $store = Store::where('id', $arr)->first();
    
    
                $array[$keyn]=$store['store_name'];
    
    
                
    
            }
    
            $appmenus[$keym]->item=$array;



        }

    


      }
    //dd($appmenus);


      //dd($services);


        return view('User.App.ManageAppMenu',compact(['list','count','all_categories','services','stores','appmenus']));
    




    

        //return view('User.Acounting.ManageAppMenu', compact(['personals','cansomers']));
    }

    public function submit(Request $request)
    {

        $validation = $this->getValidationFactory()->make($request->all(), [
            'priority' => 'required',
            'type' => 'required',
            


        ]);

        if ($validation->fails()) {

            //return response()->json(['messsage' => 'invalid'], 400);
            alert()->error('باید تمامی فیلد های الزامل را پر کنید!', 'ثبت صورت نپذیرفت')->autoclose(2000);
            //return 'error';
            return back();

        }

        //dd($request);
        if($request->spechoffer){

            $spechoffer=1;

        }else{
            $spechoffer=0;

        }

        if($request->type == 'دسته بندی'){


            

            $arra=array();

            array_push($arra, $request->category );

            $string = serialize( $arra );




            $service = AppMenu::create([
                'priority' => $request->priority,
                'title' => $request->title,
                'type' => $request->type,
                'item' => $string,
                'special_offer' => $spechoffer,
                'description' => $request->description,
            ]);
        }else if($request->type == 'خدمت های دسته'){


            

            $arra=array();

            array_push($arra, $request->category );

            $string = serialize( $arra );




            $service = AppMenu::create([
                'priority' => $request->priority,
                'title' => $request->title,
                'type' => $request->type,
                'item' => $string,
                'special_offer' => $spechoffer,
                'description' => $request->description,
            ]);
        }else if($request->type == 'فروشگاه های دسته'){


            

            $arra=array();

            array_push($arra, $request->category );

            $string = serialize( $arra );




            $service = AppMenu::create([
                'priority' => $request->priority,
                'title' => $request->title,
                'type' => $request->type,
                'item' => $string,
                'special_offer' => $spechoffer,
                'description' => $request->description,
            ]);
        }else if($request->type == 'فروشگاه'){


            //sdd($request->store);


            $string = serialize( $request->store );


            //dd($request);
            $service = AppMenu::create([
                'priority' => $request->priority,
                'title' => $request->title,
                'type' => $request->type,
                'item' => $string,
                'special_offer' => $spechoffer,
                'description' => $request->description,
            ]);
        }else if($request->type == 'خدمت'){



            $string = serialize( $request->service );


            //dd($request);
            $service = AppMenu::create([
                'priority' => $request->priority,
                'title' => $request->title,
                'type' => $request->type,
                'item' => $string,
                'special_offer' => $spechoffer,
                'description' => $request->description,
            ]);
        }


       

       

        alert()->success('منو با موفقیت انجام گردید!', 'ثبت موفق')->autoclose(2000);

        return back();
    }

    public function delete(Request $request)
    {
        //dd($request);
        foreach ($request->array as $appmenuid) {
            //$checkout = CheckoutPersonals::find($checkoutid);
        
            $appmneu = AppMenu::find($appmenuid);

            $appmneu->delete();

          

            
         }
        
        //return 'error';
        //return back;
        alert()->success('با موفقیت حذف گردید', 'حذف موفق')->autoclose(2000);
        return 'success';
    }


    public function applinkshow(Request $request){


        $store=Store::find($request->id);



        return view('User.App.AppLink',compact('store'));
    }
}
