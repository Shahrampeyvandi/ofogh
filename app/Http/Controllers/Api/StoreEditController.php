<?php

namespace App\Http\Controllers\Api;

use App\App\Models\Customers\CustomerAddress;
use App\Http\Controllers\Controller;
use App\Models\Acounting\UserAcounts;
use App\Models\Services\ServiceCategory;
use App\Models\Services\Service;
use App\Models\Orders\Order;
use App\Models\Neighborhood;
use App\Models\Store\Product;
use App\Models\Store\GoodsOrdersStatuses;
use App\Models\User;
use App\Models\Acounting\Transations;
use App\Models\City\City;
use Illuminate\Http\Request;
use App\Models\Cunsomers\Cunsomer;
use App\Models\Personals\Personal;
use App\Models\Acounting\OffCode;
use App\Models\Acounting\OffCodeUse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Store\GoodsOrders;
use App\Models\Store\Store;
use App\Models\StoreEdit\ProductEdit;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\App\Slideshow;
use Illuminate\Support\Facades\File;

class StoreEditController extends Controller
{
    public function editProduct(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
        $product_id = $request->id;
        $store_id = $personal->store->id;
        $type = 'ویرایش محصول';
        $user_ip = \Request::getClientIp(true);

        $product = Product::find($product_id);

        if($request->product_name!==null && $request->product_name!=="" && $request->product_name !== $product->product_name) {
            $final_new_name = $request->product_name;
            $old_name = $product->product_name;
        }else{
            $final_new_name = null;
            $old_name = null;
        }

        if($request->product_description!==null && $request->product_description!=="" && $request->product_description !== $product->product_description){
            $final_new_description = $request->product_description;
            $old_description = $product->product_description;
        }else{
            $final_new_description = null;
            $old_description = null;
        }

        if($request->type!==null && $request->type!=="" && $request->type !== $product->type){
            $final_new_product_type = $request->type;
            $old_product_type = $product->type;
        }else{
            $final_new_product_type = null;
            $old_product_type = null;
        }
        
        // ProductEdit::create([
        //     'type'=> $type,
        //     'product_id'=> $product_id,
        //     'store_id'=> $store_id,
        //     'new_name'=> $final_new_name,
        //     'new_description'=> $final_new_description,
        //     'new_product_type'=> $final_new_product_type,
        //     'old_name'=> $old_name,
        //     'old_description'=> $old_description,
        //     'old_product_type'=> $old_product_type,
        //     'ip'=> $user_ip
        // ]);

        $productedit=new ProductEdit;
        $productedit->type=$type;
        $productedit->store_id=$store_id;
        $productedit->product_id=$product_id;

        $productedit->new_name=$final_new_name;
        $productedit->new_description=$final_new_description;
        $productedit->new_product_type=$final_new_product_type;

        $productedit->old_name=$old_name;
        $productedit->old_description=$old_description;
        $productedit->old_product_type=$old_product_type;


        $productedit->ip=$user_ip;
        $productedit->save();

        return response()->json([
            'code'=>200,
            'data'=>$productedit->id,
            'message'=>'بعد از تایید پشتیبانی ویرایش محصول انجام خواهد شد'
        ],200);
    }

    public function deleteProduct(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $product_id = $request->product_id;
        $store_id = $personal->store->id;
        $type = 'حذف محصول';
        $user_ip = \Request::getClientIp(true);

        ProductEdit::create([
            'type'=> $type,
            'product_id'=> $product_id,
            'store_id'=> $store_id,
            'ip'=> $user_ip
        ]);

        return response()->json([
            'code'=>200,
            'message'=>'بعد از تایید پشتیبانی محصول شما حذف خواهد شد'
        ],200);
    }

    public function createProduct(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $store_id = $personal->store->id;
        $type = 'محصول جدید';
        $user_ip = \Request::getClientIp(true);

        $productedit=new ProductEdit;
        $productedit->type=$type;
        $productedit->store_id=$store_id;
        $productedit->new_name=$request->product_name;
        $productedit->new_description=$request->product_description;
        $productedit->new_product_type=$request->type;
        $productedit->new_price=$request->product_price;
        $productedit->ip=$user_ip;
        $productedit->save();

        // ProductEdit::create([
        //     'type'=> $type,
        //     'store_id'=> $store_id,
        //     'new_name'=> $request->product_name,
        //     'new_description'=> $request->product_description,
        //     'new_product_type'=> $request->type,
        //     'new_price'=> $request->product_price,
        //     'ip'=> $user_ip
        // ]);

        return response()->json([
            'code'=>200,
            'data'=>$productedit->id,
            'message'=>'بعد از تایید پشتیبانی محصول شما افزوده خواهد شد'
        ],200);
    }

    public function changePriceProduct(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $product_id = $request->product_id;
        $store_id = $personal->store->id;
        $type = 'ویرایش محصول';
        $user_ip = \Request::getClientIp(true);
        
        $product = Product::where('id',$product_id)->first();
        
        ProductEdit::create([
            'type'=> $type,
            'product_id'=> $product_id,
            'store_id'=> $store_id,
            'new_price'=> $request->new_price,
            'old_price'=> $product->product_price,
            'applied'=> 1,
            'ip'=> $user_ip
        ]);

        Product::where('id',$product_id)->update([
            'product_price'=>$request->new_price
        ]);

        return response()->json([
            'code'=>200,
            'message'=>'انجام گردید'
        ],200);
    
    }

    public function uploadproductimage(Request $request)
    {
        $image = $request->image;
        $product_edit_id = $request->product_edit_id;

        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();
        
        $productEdit = ProductEdit::where('id',$product_edit_id)->first();

        $file = 'photo-' .time(). '.' . $image->getClientOriginalExtension();
        $image->move(public_path('uploads/storeChanges/'.$productEdit->store_id), $file);
        $image_url = 'storeChanges/' .$productEdit->store_id.'/'. $file;

        if ($productEdit->type=='ویرایش محصول') {
           $old_picture = $productEdit->product->product_picture;
        }elseif ($productEdit->type=='محصول جدید') {
            $old_picture = null;
         }

        ProductEdit::where('id',$product_edit_id)->update([
            'new_picture'=> $image_url,
            'old_picture'=> $old_picture
        ]);


        return response()->json('ok',200);
    }

    public function productEdit_count(Request $request)
    {
        $payload = JWTAuth::parseToken($request->header('Authorization'))->getPayload();
        $mobile = $payload->get('mobile');
        $personal = Personal::where('personal_mobile', $mobile)->first();

        $store_id = $personal->store->id;

        $productEdit_count = ProductEdit::where('store_id',$store_id)->where('applied',0)->count();
    }
}