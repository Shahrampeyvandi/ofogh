<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cunsomers\Cunsomer;
use App\Models\City\City;
use App\Models\Services\ServiceCategory;
use App\Models\Services\Service;
use App\Models\Store\Store;
use App\Models\Store\Product;
use App\Models\App\AppMenu;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\App\Search;
use App\Models\Neighborhood;

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
                       // $cat['icon']='personals/09156833780/photo-1584535352.jpg';
                        $cat['icon']=$servic->service_icon;


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

                        $array[$key]=$cat;

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
                   // $ser['icon']='personals/09156833780/photo-1584535352.jpg';
                    $ser['icon']=$service->service_icon;


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

    public function search(Request $request){


        if(strlen($request->search)>3){
            $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
            $mobile = $payload->get('mobile');
            $customer = Cunsomer::where('customer_mobile', $mobile)->first();
           
            $search=new Search;
            $search->search=$request->search;
            $search->cunsomer_id=$customer->id;
            $search->save();

        }

        $response=[];

        $services = Service::where('service_title','LIKE', '%'.$request->search.'%')->take(5)->get();


        $searchedservice=[];
        $searchedservice['title']='خدمات';
        $searchedservice['type']='خدمت';

        $foreachservice=[];
        foreach($services as $service){


            $searchservice['iditem']=$service->id;
            $searchservice['title']=$service->service_title;
            $searchservice['icon']=$service->service_icon;


            $foreachservice[]=$searchservice;

        }
        $searchedservice['item']=$foreachservice;



        $stores = Store::where('store_name','LIKE', '%'.$request->search.'%')->take(5)->get();

        $searchedstore=[];
        $searchedstore['title']='فروشگاه';
        $searchedstore['type']='فروشگاه';

        $foreachstore=[];
        foreach($stores as $store){


            $storesearch['iditem']=$store->id;
            $storesearch['title']=$store->store_name;
            $storesearch['icon']=$store->store_icon;

            $foreachstore[]=$storesearch;

        }
        $searchedstore['item']=$foreachstore;


        $products=Product::where('product_name','LIKE', '%'.$request->search.'%')->take(10)->get();


        $searchedproduct=[];
        $searchedproduct['title']='محصولات';
        $searchedproduct['type']='فروشگاه';

        $foreachproduct=[];
        foreach($products as $product){


            $productsearch['iditem']=$product->store_id;
            $productsearch['title']=$product->product_name;
            $productsearch['icon']=$product->product_picture;
            $productsearch['type']=$product->id;

            $foreachproduct[]=$productsearch;

        }
        $searchedproduct['item']=$foreachproduct;

        $response[]=$searchedservice;
        $response[]=$searchedstore;
        $response[]=$searchedproduct;

        
        return response()->json([
            'data' => $response,
          ], 200);
    }

    public function indexbl(Request $request)
    {

        $city=City::where('city_name',$request->city)->first();

        if(is_null($city)){


            

            return $this->index();
        }


        $neighbourhood=Neighborhood::where('city_id',$city->id)->where('name',$request->neighbourhood)->first();


        if(is_null($neighbourhood)){


            

            return $this->index();
        }

     

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
                       // $cat['icon']='personals/09156833780/photo-1584535352.jpg';
                        $cat['icon']=$servic->service_icon;


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
    
                $arraystore=[];
                foreach($array as $keyn=>$arr){
        
        

                    $categoryname = ServiceCategory::where('id', $arr)->first();
                    $store = Store::where('store_type', $arr)
                    ->whereHas('neighborhoods',function($q) use ($neighbourhood) {
                        $q->where('id', $neighbourhood->id);
                     })
                    ->get();



                    foreach($store as $key=>$stor){

                        //if($sotre->neighborhoods()->)

                        $cat['iditem']=$stor->id;
                        $cat['title']=$stor->store_name;
                        $cat['icon']=$stor->store_icon;



                        $arraystore[]=$cat;

                    }

                    

        
                    //$array[$keyn]=$category['category_title'];

    
        
                    $appmenus[$keym]->iditem=$arr;


        
                }

                do{
                   
                    $appmenus[$keym]->titlecat=$categoryname->category_title;
    
                    $categoryname = ServiceCategory::where('id', $categoryname->category_parent)->first();
    
    
                   }while($categoryname);
               
                $appmenus[$keym]->item=$arraystore;

    
            }else if($appmenu->type == 'خدمت'){
    
    
    
                foreach($array as $keyn=>$arr){
        
                    $service=null;
    
                    $service = Service::where('id', $arr)
                    ->first();


                        $ser['iditem']=$service->id;
                        $ser['title']=$service->service_title;
                       // $ser['icon']='personals/09156833780/photo-1584535352.jpg';
                        $ser['icon']=$service->service_icon;
    
    
                        //$array[$keyn]=$service['service_title'];
                        $array[$keyn]=$ser;    

                    
        
                    
    
        
                    
        
                }
        
               
                $appmenus[$keym]->item=$array;
    
    
    
    
            }else if($appmenu->type == 'فروشگاه'){
    
                $arrayselectstore=[];
                foreach($array as $keyn=>$arr){
        
                    $store = Store::where('id', $arr)
                    ->whereHas('neighborhoods',function($q) use ($neighbourhood) {
                        $q->where('id', $neighbourhood->id);
                     })
                    ->first();
        
                    if($store){

                    $ser['iditem']=$store->id;
                    $ser['title']=$store->store_name;
                    $ser['icon']=$store->store_icon;
        
        
                    //$array[$keyn]=$store['store_name'];
                    $arrayselectstore[]=$ser;

                    }
                    
        
                }
        
                $appmenus[$keym]->item=$arrayselectstore;
    
    
    
            }
            
        }






            return response()->json([
                'data' => $appmenus,
              ], 200);
    }
}
