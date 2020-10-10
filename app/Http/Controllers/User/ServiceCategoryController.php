<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Models\Services\ServiceCategory;

class ServiceCategoryController extends Controller
{
    public function CategoryList()
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
        $data['count'] = $count;
        $data['list'] = $list;
        return view('User.ServiceCategoryList', $data);
    }


    public function getData(Request $request)
    {

        $category = Category::where('id', $request->category_id)->first();
        $csrf = csrf_token();
        $category_parent_list = Category::where('parent_id', 0)->get();
        $count = Category::where('parent_id', 0)->count();
        $options = '<option data-parent="0" value="0" >بدون دسته بندی</option>';

        foreach ($category_parent_list as $key => $item) {

            $options .= '<option data-id="' . $item->id . '" value="' . $item->id . '"
            ' . ($category->parent_id == $item->id ? 'class="level-1 after"' : 'class="level-1"') . '
            >' . $item->name . ' 
             ' . (count(Category::where('parent_id', $item->id)->get()) ? '&#xf104;  ' : '') . '
            </option>';
            if (Category::where('parent_id', $item->id)->count()) {
                $count += Category::where('parent_id', $item->id)->count();
                foreach (Category::where('parent_id', $item->id)->get() as $key1 => $itemlevel1) {
                    $options .= '<option data-parent="' . $item->id . '" 
                 ' . ($category->parent_id == $itemlevel1->id ? 'class="level-2 after"' : 'class="level-2"') . '
                 value="' . $itemlevel1->id . '" 
                 
                 >' . $itemlevel1->name . '
                 ' . (count(Category::where('parent_id', $itemlevel1->id)->get()) ? '&#xf104;  ' : '') . '
                 </option>';


                    if (Category::where('parent_id', $itemlevel1->id)->count()) {
                        $count += Category::where('parent_id', $itemlevel1->id)->count();
                        foreach (Category::where('parent_id', $itemlevel1->id)->get() as $key2 => $itemlevel2) {
                            $options .= '<option data-parent="' . $itemlevel1->id . '" 
                     ' . ($category->parent_id == $itemlevel2->id ? 'class="level-3 after"' : 'class="level-3"') . '
                     value="' . $itemlevel2->id . '" >' . $itemlevel2->name . '
                     ' . (count(Category::where('parent_id', $itemlevel2->id)->get()) ? '&#xf104;  ' : '') . '
                     </option>';


                            if (Category::where('parent_id', $itemlevel2->id)->count()) {
                                $count += Category::where('parent_id', $itemlevel2->id)->count();
                                foreach (Category::where('parent_id', $itemlevel2->id)->get() as $key3 => $itemlevel3) {
                                    $options .= '<option data-parent="' . $itemlevel2->id . '" 
                         ' . ($category->parent_id == $itemlevel3->id ? 'class="level-4 after"' : 'class="level-4"') . '
                         value="' . $itemlevel3->id . '" >' . $itemlevel3->name . '
                         ' . (count(Category::where('parent_id', $itemlevel3->id)->get()) ? '&#xf104;  ' : '') . '
                         </option>';

                                    if (Category::where('parent_id', $itemlevel3->id)->count()) {
                                        $count += Category::where('parent_id', $itemlevel3->id)->count();
                                        foreach (Category::where('parent_id', $itemlevel3->id)->get() as $key4 => $itemlevel4) {
                                            $options .= '<option data-parent="' . $itemlevel3->id . '" 
                                 ' . ($category->parent_id == $itemlevel4->id ? 'class="level-5 after"' : 'class="level-5"') . '
                                 value="' . $itemlevel4->id . '" >' . $itemlevel4->name . '
                                 
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


        $list = ' <div class="modal-header">
      <h5 class="modal-title" id="exampleModalLabel">ویرایش دسته بندی خدمات</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form id="edit--form" method="post" action="' . route('Category.Edit.Submit') . '" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="' . $csrf . '">
    <input type="hidden" name="category_id" value="' . $category->id . '">
    <div class="modal-body">
       <div class="row">
       <div class="form-group col-md-12">
       <label for="category_title" class="col-form-label"><span class="text-danger">*</span> عنوان: </label>
       <input type="text" class="form-control" name="category_title"
       value="' . $category->name . '"
       id="category_title">
     </div>
          
       </div>
       <div class="row">
       <div class="form-group col-md-12">
       <label for="recipient-name" class="col-form-label">دسته:</label>
     <select ' . ($count > 1 ?
            'size="' . $count . '"' :  'size="2"') . ' class="form-control" name="parent_id" id="parent_id">
          ' . $options . '
       </select>                      
       
         </div><!-- form-group -->
       </div>
       
        <div class="form-group col-md-6">
        <label for="category_icon" class="col-form-label"> تغییر تصویر:</label>
        <input type="file" class="form-control" name="category_icon" id="category_icon">
      </div>
       
       <div class="row">
       <div class="form-group col-md-12">
       <label for="recipient-name" class="col-form-label">توضیحات عمومی:</label>
       <textarea type="text" class="form-control" name="category_description" id="category_description">' . $category->description . '
       </textarea>
     </div>
         
    
      
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
      <button type="submit" class="btn btn-primary">ارسال</button>
    </div>
    </form>';



        return $list;
    }

    public function SubmitCategoryEdit(Request $request)
    {

        
        $category = Category::where('id', $request->category_id)->first();


        if ($request->has('category_icon')) {

            File::delete(public_path('uploads/category_images/' . $category->picture));

            $fileName = $request->category_title . '.' . $request->category_icon->getClientOriginalExtension();
            $fileNameWithoutEx = pathinfo($fileName, PATHINFO_FILENAME);
            $request->category_icon->move(public_path('uploads/category_images'), $fileName);
            $category->picture = $fileName;
        }

        $category->name = $request->category_title;
        if ($request->has('parent_id')) {
            $category->parent_id = $request->parent_id;
        }
        $category->description = $request->category_description;
        $category->update();



        alert()->success('دسته بندی با موفقیت ویرایش شد', 'عملیات موفق')->autoclose(2000);
        return back();
    }

    public function SubmitServiceCategory(Request $request)
    {

        // dd($request->all());

        if ($request->has('category_icon')) {
            

            $fileName = $request->category_title . '.' . $request->category_icon->getClientOriginalExtension();
            $fileNameWithoutEx = pathinfo($fileName, PATHINFO_FILENAME);
            $request->category_icon->move(public_path('uploads/category_images'), $fileName);
        } else {
            $fileName = '';
        }

        Category::create([
            'name' => $request->category_title,
            'parent_id' => isset($request->parent_id) ? $request->parent_id  : 0,
            'picture' => $fileName,
            'description' => $request->category_description
        ]);

        alert()->success('دسته بندی با موفقیت ثبت شد', 'عملیات موفق')->autoclose(2000);
        return back();
    }

    public function DeleteCategory(Request $request)
    {

        foreach ($request->array as $id) {
            Category::where('id', $id)->delete();
            Category::where('parent_id', $id)->delete();
        }
        return 'success';
    }








    public function OnlinePersonals()
    {
        return view('User.OnlinePersonals');
    }
}
