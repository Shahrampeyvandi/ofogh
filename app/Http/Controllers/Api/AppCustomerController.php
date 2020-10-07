<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Services\ServiceCategory;
use App\Models\Services\Service;
use App\Models\Store\Store;
use App\Models\App\AppMenu;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class AppCustomerController extends Controller
{
    public function index()
    {


       // $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        //$mobile = $payload->get('mobile');

     

        $appmenus = AppMenu::orderBy('priority', 'ASC')->get();

       
        foreach($appmenus as $keym=>$appmenu){

            $array = unserialize( $appmenu->item );
    
        
            if($appmenu->type == 'دسته بندی'){
    
    
                foreach($array as $keyn=>$arr){
        
                    
                    $category = ServiceCategory::where('category_parent', $arr)->get();
        
                  

                    foreach($category as $key=>$categ){

                        $cat['iditem']=$categ->id;
                        $cat['title']=$categ->category_title;
                    $cat['icon']=$categ->category_icon;
                    //$cat['icon']='personals/09156833780/photo-1584535352.jpg';

                    //$array[$keyn]=$category['category_title'];

                    $categoryzirdaste = ServiceCategory::where('category_parent', $categ->id)->first();
         

                    //$chil['rtue']=$categoryzirdaste;
        
                    $cat['type']='2';



                    $array[$key]=$cat;




                    }
                   // $cat=array();

                    //$services = Service::where('service_category_id', $arr)->first();

                    $appmenus[$keym]->iditem=$arr;

    
        
                    

                }

                $categoryname = ServiceCategory::where('id', $arr)->first();
                $catpar=ServiceCategory::where('id', $categoryname->category_parent)->first();
               do{
                   
                $appmenus[$keym]->titlecat=$categoryname->category_title;

                $categoryname = ServiceCategory::where('id', $categoryname->category_parent)->first();


               }while($categoryname);
   
               


                $appmenus[$keym]->item=$array;


    
    
            }else if($appmenu->type == 'خدمت های دسته'){
    
    
                foreach($array as $keyn=>$arr){
        
                    //$category = ServiceCategory::where('id', $arr)->first();
        
                   // $cat=array();

                    $services = Service::where('service_category_id', $arr)->get();

                    foreach($services as $key=>$servic){

                        $cat['iditem']=$servic->id;
                        $cat['title']=$servic->service_title;
                        $cat['icon']='personals/09156833780/photo-1584535352.jpg';


                        $array[$key]=$cat;


                    }

                   

        
                    //$array[$keyn]=$category['category_title'];

                    $appmenus[$keym]->iditem=$arr;

        
                    
        
                }

                $categoryname = ServiceCategory::where('id', $arr)->first();
                $catpar=ServiceCategory::where('id', $categoryname->category_parent)->first();
               do{
                   
                $appmenus[$keym]->titlecat=$categoryname->category_title;

                $categoryname = ServiceCategory::where('id', $categoryname->category_parent)->first();


               }while($categoryname);
               
        
                $appmenus[$keym]->item=$array;
    
    
            }else if($appmenu->type == 'فروشگاه های دسته'){
    
    
                foreach($array as $keyn=>$arr){
        
        

                    $categoryname = ServiceCategory::where('id', $arr)->first();
                    $store = Store::where('store_type', $arr)->get();

                    foreach($store as $key=>$stor){

                        $cat['iditem']=$stor->id;
                        $cat['title']=$stor->store_name;
                        $cat['icon']=$stor->store_icon;

                        $array[$keyn]=$cat;

                    }

                    

        
                    //$array[$keyn]=$category['category_title'];

    
        
                    $appmenus[$keym]->iditem=$arr;

                    
        
                }

                do{
                   
                    $appmenus[$keym]->titlecat=$categoryname->category_title;
    
                    $categoryname = ServiceCategory::where('id', $categoryname->category_parent)->first();
    
    
                   }while($categoryname);
               
                $appmenus[$keym]->item=$array;
    
    
            }else if($appmenu->type == 'خدمت'){
    
    
    
                foreach($array as $keyn=>$arr){
        
    
                    $service = Service::where('id', $arr)->first();

        
                    $ser['iditem']=$service->id;
                    $ser['title']=$service->service_title;
                    $ser['icon']='personals/09156833780/photo-1584535352.jpg';
        

                    //$array[$keyn]=$service['service_title'];
                    $array[$keyn]=$ser;

    
        
                    
        
                }
        
               
                $appmenus[$keym]->item=$array;
    
    
    
    
            }else if($appmenu->type == 'فروشگاه'){
    
    
                foreach($array as $keyn=>$arr){
        
                    $store = Store::where('id', $arr)->first();
        

                    $ser['iditem']=$store->id;
                    $ser['title']=$store->store_name;
                    $ser['icon']=$store->store_icon;
        
        
                    //$array[$keyn]=$store['store_name'];
                    $array[$keyn]=$ser;

        
                    
        
                }
        
                $appmenus[$keym]->item=$array;
    
    
    
            }
            
        }






            return response()->json([
                'data' => $appmenus,
              ], 200);
    }
}
