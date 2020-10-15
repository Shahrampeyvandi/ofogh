<?php

namespace App\Http\Controllers\App;

use App\Models\Category;
use App\Models\App\AppMenu;
use App\Models\Store\Store;
use Illuminate\Http\Request;
use App\Models\Services\Service;
use App\Models\Cunsomers\Cunsomer;
use App\Http\Controllers\Controller;
use App\Models\Services\ServiceCategory;
use App\Models\Store\Product;

class AppMenuController extends Controller
{
    public function index()
    {

        $data['all_categories'] = Category::latest()->get();
        $category_parent_list = Category::where('parent_id', 0)->get();
        $count = Category::where('parent_id', 0)->count();
        $list = '<option data-parent="0" value="0" >بدون دسته بندی</option>';
        foreach ($category_parent_list as $key => $item) {
            $list .= '<option data-id="' . $item->id . '" value="' . $item->id . '" class="level-1">' . $item->name . ' 
            ' . (count(Category::where('parent_id', $item->id)->get()) ? '&#xf104;  ' : '') . '
           </option>';
            if (Category::where('parent_id', $item->id)->count()) {
                $count += Category::where('parent_id', $item->id)->count();
                foreach (Category::where('parent_id', $item->id)->get() as $key1 => $itemlevel1) {
                    $list .= '<option data-parent="' . $item->id . '" value="' . $itemlevel1->id . '" class="level-2">' . $itemlevel1->name . '
                ' . (count(Category::where('parent_id', $itemlevel1->id)->get()) ? '&#xf104;  ' : '') . '
                </option>';
                    if (Category::where('parent_id', $itemlevel1->id)->count()) {
                        $count += Category::where('parent_id', $itemlevel1->id)->count();
                        foreach (Category::where('parent_id', $itemlevel1->id)->get() as $key2 => $itemlevel2) {
                            $list .= '<option data-parent="' . $itemlevel1->id . '" value="' . $itemlevel2->id . '" class="level-3">' . $itemlevel2->name . '
                    ' . (count(Category::where('parent_id', $itemlevel2->id)->get()) ? '&#xf104;  ' : '') . '
                    </option>';


                            if (Category::where('parent_id', $itemlevel2->id)->count()) {
                                $count += Category::where('parent_id', $itemlevel2->id)->count();
                                foreach (Category::where('parent_id', $itemlevel2->id)->get() as $key3 => $itemlevel3) {
                                    $list .= '<option data-parent="' . $itemlevel2->id . '" value="' . $itemlevel3->id . '" class="level-4">' . $itemlevel3->name . '
                        ' . (count(Category::where('parent_id', $itemlevel3->id)->get()) ? '&#xf104;  ' : '') . '
                        </option>';

                                    if (Category::where('parent_id', $itemlevel3->id)->count()) {
                                        $count += Category::where('parent_id', $itemlevel3->id)->count();
                                        foreach (Category::where('parent_id', $itemlevel3->id)->get() as $key4 => $itemlevel4) {
                                            $list .= '<option data-parent="' . $itemlevel3->id . '" value="' . $itemlevel4->id . '" class="level-4">' . $itemlevel4->name . '
                                
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
        $all_categories = Category::latest()->get();


        $appmenus = AppMenu::all();


        foreach ($appmenus as $keym => $appmenu) {

            $array = unserialize($appmenu->item);

            if ($appmenu->type == 'لیست دسته بندی') {

                foreach ($array as $keyn => $arr) {

                    $category = Category::where('id', $arr)->first();

                    $array[$keyn] = $category['name'];
                }

                $appmenus[$keym]->item = $array;
            } else if ($appmenu->type == 'محصول های دسته') {

                foreach ($array as $keyn => $arr) {

                    $category = Category::where('id', $arr)->first();


                    $array[$keyn] = $category['name'];
                }

                $appmenus[$keym]->item = $array;
            } else if ($appmenu->type == 'محصول انتخابی') {

                foreach ($array as $keyn => $arr) {

                    $product = Product::where('id', $arr)->first();

                    $array[$keyn] = $product['product_name'];
                }

                $appmenus[$keym]->item = $array;
            }
        }
        // dd($appmenus);


        //dd($services);


        return view('User.App.ManageAppMenu', compact(['list', 'count', 'all_categories', 'appmenus']));







        //return view('User.Acounting.ManageAppMenu', compact(['personals','cansomers']));
    }

    public function submit(Request $request)
    {


        $validation = $this->getValidationFactory()->make($request->all(), [
            'priority' => 'required',
            'type' => 'required',
        ]);

        if ($validation->fails()) {

            alert()->error('باید تمامی فیلد های الزامل را پر کنید!', 'ثبت صورت نپذیرفت')->autoclose(2000);
            return back();
        }
        if ($request->spechoffer) {

            $spechoffer = 1;
        } else {
            $spechoffer = 0;
        }
        $arra = array();

        if ($request->type == 'لیست دسته بندی') {
            $string = serialize($request->store);
        } elseif ($request->type == 'محصول انتخابی') {
            $string = serialize($request->service);
        } else {
            array_push($arra, $request->category);
            $string = serialize($arra);
        }

        $service = AppMenu::create([
            'priority' => $request->priority,
            'title' => $request->title,
            'type' => $request->type,
            'item' => $string,
            'special_offer' => $spechoffer,
            'description' => $request->description,
        ]);

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


    public function applinkshow(Request $request)
    {


        $store = Store::find($request->id);



        return view('User.App.AppLink', compact('store'));
    }
}
