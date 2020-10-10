<?php

namespace App\Http\Controllers\User;

use App\Models\City\City;
use App\Models\Store\Store;
use App\Models\Store\StoreWorkingHours;
use App\Models\Neighborhood;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;
use App\Models\Store\Product;
use App\Models\Services\Service;
use App\Models\Personals\Personal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Models\Services\ServiceCategory;

class StoreController extends Controller
{
  public function index()
  {

    $data['products'] = Product::latest()->get();


    return view('User.Stores.StoresList', $data);
  }

  public function saveproduct(Request $request)
  {
    // dd($request->all());

    if ($request->has('product_picture')) {
      $file = 'product' . time() . '.' . $request->product_picture->getClientOriginalExtension();
      $request->product_picture->move(public_path('uploads/products/' . $request->product_name), $file);
      $product_picture = 'uploads/products/' . $request->product_name . '/' . $file;
    } else {
      $product_picture = '';
    }

    $product =   Product::create([
      'product_name' => $request->product_name,
      'type' => 'normal',
      'product_picture' => $product_picture,
      'product_description' => $request->product_descripton,
      'product_price' => $request->product_price,
      'discount' => $request->discount,
      'count' => $request->count,
      'category_id' => $request->category,
    ]);

    alert()->success('محصول با موفقیت ذخیره شد')->persistent('بستن');
    return back();
  }



  public function EditProduct($id)
  {

    $data['product'] = Product::find($id);
    return view('User.Stores.EditProduct', $data);
  }


  public function SaveEditProduct(Request $request, $id)
  {

    // dd($request->all());

    $product = Product::where('id', $id)->first();

    if ($request->has('product_picture')) {
      File::delete(public_path() . '/' . $product->product_picture);
      $file = 'product' . time() . '.' . $request->product_picture->getClientOriginalExtension();
      $request->product_picture->move(public_path('uploads/products/' . $request->product_name), $file);
      $product_picture = 'uploads/products/' . $request->product_name . '/' . $file;
      $product->product_picture = $product_picture;
    }

    $product->product_name = $request->product_name;

    $product->product_description = $request->product_description;
    $product->product_price = $request->product_price;
    $product->discount = $request->discount;
    $product->count = $request->count;
    if ($request->category !== null) {
      $product->category_id = $request->category;
    }
    $product->update();
    alert()->success('محصول با موفقیت ویرایش شد')->persistent('بستن');
    return redirect()->route('Pannel.Services.Stores');
  }

  public function deleteStore(Request $request)
  {

    foreach ($request->array as $key => $item) {

      $product = Product::find($item);
      File::delete(public_path() . '/' . $item->product_picture);
      $product->delete();


      alert()->success('محصول با موفقیت حذف شد')->persistent('بستن');
      return back();
    }
  }
}
