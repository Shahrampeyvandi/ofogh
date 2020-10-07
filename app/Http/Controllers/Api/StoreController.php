<?php

namespace App\Http\Controllers\Api;

use App\Models\Store\Store;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Store\Product;
use App\Models\Personals\Personal;

class StoreController extends Controller
{
    public function getStore(Request $request)
    {
        $store_id = $request->store_id;
        
        $store = Store::where('id', $store_id)->first();
     
        $personal=Personal::find($store->owner_id);
        $store['store_address']=$store->store_main_street.' '.$store->store_secondary_street.' پلاک '.$store->store_pelak;
        $store['mobile']=$personal->personal_mobile;
        $storeArray = [];
        $storeArray['store_name'] = $store->store_name;
        $storeArray['store_address']=$store->store_main_street.' '.$store->store_secondary_street.' پلاک '.$store->store_pelak;
        $storeArray['store_description'] = $store->store_description;
        $storeArray['store_type'] = $store->store_type;
        $storeArray['store_picture'] = $store->store_picture;
        $storeArray['store_icon'] = $store->store_icon;
        $storeArray['store_city'] = $store->store_city;
        $storeArray['store_main_street'] = $store->store_main_street;
        $storeArray['store_secondary_street'] = $store->store_secondary_street;
        $storeArray['store_pelak'] = $store->store_pelak;
        $storeArray['store_packing_price'] = $store->packing_price;
        $storeArray['store_sending_price'] = $store->sending_price;   
        $storeArray['store_pelak'] = $store->store_pelak;
        foreach ($store->neighborhoods as $key => $neighborhood) {
            $storeArray['neighborhoods'][$key+1]['name'] = $neighborhood->name;
            $storeArray['neighborhoods'][$key+1]['city'] = $neighborhood->city_id;
           
        }
       
        if(!is_null($store)){
            foreach ($store->products as $key => $product) {
               if($product->type == 'primary_product'){
                $storeArray['general_products'][$key+1]['product_name']= $product->product_name;
                $storeArray['general_products'][$key+1]['product_price'] = $product->product_price;
                $storeArray['general_products'][$key+1]['product_picture'] = $product->product_picture;
                $storeArray['general_products'][$key+1]['product_description'] = $product->product_description;
                $storeArray['general_products'][$key+1]['product_status'] = $product->product_status;
               }
               if($product->type == 'secondary_product'){
                $storeArray['sundry_products'][$key+1]['product_name']= $product->product_name;
                $storeArray['sundry_products'][$key+1]['product_price'] = $product->product_price;
                $storeArray['sundry_products'][$key+1]['product_picture'] = $product->product_picture;
                $storeArray['sundry_products'][$key+1]['product_status'] = $product->product_status;
               }
            }
        }
        return response()->json(
            $store,
            200
          );

}


public function productStatus(Request $request)
{
    $product_id = $request->product_id;
    $status = $request->status;
    $product = Product::where('id',$product_id)->update([
        'product_status' => $status
    ]);
    
        return response()->json(
            'done',
            200
          );
}



}
