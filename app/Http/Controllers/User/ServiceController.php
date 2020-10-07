<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Services\Service;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use App\Models\Services\ServiceCategory;

class ServiceController extends Controller
{
    public function ServiceList()
    {
        
        $brokers ='';
        foreach (Role::where('broker',1)->get() as $key => $role) {
            $brokers .= '<option value="'.$role->name.'">'.$role->name.'</option>';
         }

        $category_parent_list = ServiceCategory::where('category_parent',0)->get();
        $count = ServiceCategory::where('category_parent',0)->count();
         $list ='';
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

      if(auth()->user()->hasRole('admin_panel')){        $services = Service::latest()->get();
      }else{
          if(auth()->user()->roles->first()->broker == 1){
           $role_name = auth()->user()->roles->first()->name;
           $services = Service::where('service_role',$role_name)->latest()->get();
          }

          if(auth()->user()->roles->first()->sub_broker !== null){
             $role_name = Role::where('id',auth()->user()->roles->first()->sub_broker)->first()->name;
             $services = Service::where('service_role',$role_name)->latest()->get();

            }
      }
       
      return view('User.Services.ServiceList',compact(['list','count','services','brokers']));
    }

    public function SubmitService(Request $request)
    {
      
        if (strlen(implode($request->service_city)) == 0) {
            alert()->error('لطفا شهر را انتخاب کنید', 'خطا')->autoclose(2000);
            return back();
        }
        if ($request->has('service_icon')) {
        
            $icon = $request->title . '.' . $request->service_icon->getClientOriginalExtension();
            $request->service_icon->move(public_path('uploads/service_icons/'.$request->title), $icon);
        }else{
            $icon = '';
        }
        if ($request->has('pic_1')){
            $pic1 = $request->title . '.' . $request->pic_1->getClientOriginalExtension();
            $request->pic_1->move(public_path('uploads/service_pics/'.$request->title.'/pic1'), $pic1);
        }else{
            $pic1 = '';
        }

        if ($request->has('pic_2')){
            $pic2 = $request->title . '.' . $request->pic_2->getClientOriginalExtension();
            $request->pic_2->move(public_path('uploads/service_pics/'.$request->title.'/pic2'), $pic2);
        }else{
            $pic2 = '';
        }


       $service = Service::create([
            'service_title' => $request->title,
            'service_category_id' => $request->service_category !== null ? $request->service_category : 0,
            'service_role' => $request->service_role,
            'service_percentage' => $request->service_percentage,
            'service_offered_price' => $request->service_offered_price,
            'service_desc' => $request->service_desc,
            'service_price' => $request->service_price,
            'service_alerts' => $request->service_alerts,
            'service_type_send' => $request->type_send,
            'price_type' => $request->price_type,
            'service_offered_status' => $request->service_offered_status,
            'service_special_category' => $request->service_special_category,
            'service_icon' => $icon,
            'service_pic_first' => $pic1,
            'service_pic_second' => $pic2,
            'sms_status' => $request->sms_status == null ? 0 : $request->sms_status
        ]);

            $service->cities()->attach($request->service_city);


        alert()->success(' خدمت با موفقیت ثبت شد', 'عملیات موفق')->autoclose(2000);
        return back();
    }

    public function DeleteService(Request $request)
    {
        foreach ($request->array as $service) {
            Service::where('id',$service)->first()->personal()->detach();
            Service::where('id',$service)->delete();
        }
        return 'success';
    }

    public function getData(Request $request)
    {
        
        $brokers ='';
        foreach (Role::where('broker',1)->get() as $key => $role) {
         
                $brokers .= '<option value="'.$role->name.'">'.$role->name.'</option>';
            
        }
        
        $service = Service::where('id',$request->category_id)->first();
        if ($service->service_pic_first	!== null && $service->service_pic_first	!== '') {

            $image1Url = '/uploads/service_pics/'.$service->service_title.'/pic1/'.$service->service_pic_first;
        }else{
            $image1Url = '/Pannel/img/temp_logo.jpg';
        }
        if ($service->service_pic_second !== null && $service->service_pic_second !== '') {

            $image2Url = '/uploads/service_pics/'.$service->service_title.'/pic2/'.$service->service_pic_second;
        }else{
            $image2Url = '/Pannel/img/temp_logo.jpg';
        }
        $csrf = csrf_token();
        $category = ServiceCategory::where('id',$request->category_id)->first();
        $csrf = csrf_token();
        $category_parent_list = ServiceCategory::where('category_parent',0)->get();
        $count = ServiceCategory::where('category_parent',0)->count();
        $options ='';

        foreach ($category_parent_list as $key => $item) {
           
            $options .= '<option data-id="'.$item->id.'" value="'.$item->id.'"
            '.($service->service_category_id == $item->id ? 'class="level-1 after" selected' : 'class="level-1"' ).'
            >'.$item->category_title.' 
             '.(count(ServiceCategory::where('category_parent',$item->id)->get()) ? '&#xf104;  ' : '' ).'
            </option>';
          if (ServiceCategory::where('category_parent',$item->id)->count()) {
              $count += ServiceCategory::where('category_parent',$item->id)->count();
             foreach (ServiceCategory::where('category_parent',$item->id)->get() as $key1 => $itemlevel1) {
                 $options .= '<option data-parent="'.$item->id.'" 
                 '.($service->service_category_id == $itemlevel1->id ? 'class="level-2 after" selected' : 'class="level-2"' ).'
                 value="'.$itemlevel1->id.'" 
                 
                 >'.$itemlevel1->category_title.'
                 '.(count(ServiceCategory::where('category_parent',$itemlevel1->id)->get()) ? '&#xf104;  ' : '' ).'
                 </option>';
                 
                 
              if (ServiceCategory::where('category_parent',$itemlevel1->id)->count()) {
                 $count += ServiceCategory::where('category_parent',$itemlevel1->id)->count();
                 foreach (ServiceCategory::where('category_parent',$itemlevel1->id)->get() as $key2 => $itemlevel2) {
                     $options .= '<option data-parent="'.$itemlevel1->id.'" 
                     '.($service->service_category_id == $itemlevel2->id ? 'class="level-3 after" selected' : 'class="level-3"' ).'
                     value="'.$itemlevel2->id.'" >'.$itemlevel2->category_title.'
                     '.(count(ServiceCategory::where('category_parent',$itemlevel2->id)->get()) ? '&#xf104;  ' : '' ).'
                     </option>';
                    
                    
                    if (ServiceCategory::where('category_parent',$itemlevel2->id)->count()) {
                     $count += ServiceCategory::where('category_parent',$itemlevel2->id)->count();
                     foreach (ServiceCategory::where('category_parent',$itemlevel2->id)->get() as $key3 => $itemlevel3) {
                         $options .= '<option data-parent="'.$itemlevel2->id.'" 
                         '.($service->service_category_id == $itemlevel3->id ? 'class="level-4 after" selected' : 'class="level-4"' ).'
                         value="'.$itemlevel3->id.'" >'.$itemlevel3->category_title.'
                         '.(count(ServiceCategory::where('category_parent',$itemlevel3->id)->get()) ? '&#xf104;  ' : '' ).'
                         </option>';
                     
                         if (ServiceCategory::where('category_parent',$itemlevel3->id)->count()) {
                             $count += ServiceCategory::where('category_parent',$itemlevel3->id)->count();
                             foreach (ServiceCategory::where('category_parent',$itemlevel3->id)->get() as $key4 => $itemlevel4) {
                                 $options .= '<option data-parent="'.$itemlevel3->id.'" 
                                 '.($service->service_category_id == $itemlevel4->id ? 'class="level-5 after" selected' : 'class="level-5"' ).'
                                 value="'.$itemlevel4->id.'" >'.$itemlevel4->category_title.'
                                 
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
$list = '<div class="modal-body">
<div id="wizard2">
<form id="example-advanced-form1" method="post" action="'.route('Service.Edit.Submit').'" enctype="multipart/form-data">
<input type="hidden" name="_token" value="'.$csrf.'">
<input type="hidden" name="service_id" value="'.$service->id.'">
    <h3>خدمت</h3>
    <section>
        
            <div class="form-group wd-xs-300">
                <label>عنوان </label>
                <input type="text" id="title" name="title"
                value="'.$service->service_title.'"
                class="form-control" placeholder="نام" >
                
            </div><!-- form-group -->
            
                <div class="form-group wd-xs-300">
                    <label for="recipient-name" class="col-form-label">دسته:</label>
                    <select '.( $count > 1 ?
                    'size="'.($count+1).'"' :  'size="3"'
                 ). ' class="form-control" name="service_category" id="service_category">
                     '.$options.'
                    </select>
                                      
                <div class="valid-feedback">
                    صحیح است!
                </div>
            </div><!-- form-group -->
            <div class="row">
                <div class="form-group col-md-6">
                    <label>درصد پورسانت </label>
                    <input type="number" name="service_percentage" 
                    value="'.$service->service_percentage.'"
                    id="service_percentage" class="form-control" placeholder="">
                    <div class="valid-feedback">
                        صحیح است!
                    </div>
                </div><!-- form-group -->
                <div class="form-group col-md-6">
                    <label>قیمت ارسال پیشنهاد (تومان) </label>
                    <input type="number" 
                    value="'.$service->service_offered_price.'"
                    name="service_offered_price" id="service_offered_price" class="form-control" placeholder="">
                    <div class="valid-feedback">
                        صحیح است!
                    </div>
                </div><!-- form-group -->
            </div>
            <div class="form-group wd-xs-300">
                <label>توضیحات </label>
                <textarea type="text" name="service_desc" class="form-control" placeholder="">
                '.$service->service_desc.'
                </textarea>
                <div class="valid-feedback">
                    صحیح است!
                </div>
            </div><!-- form-group -->
            <div class="form-group wd-xs-300">
                <label>تذکرات </label>
                <input type="text"
                value="'.$service->service_alerts.'"
                name="service_alerts" class="form-control" placeholder="" >
                <div class="valid-feedback">
                    صحیح است!
                </div>
            </div><!-- form-group -->

            <p>شهر  </p>
            <div class="form-group wd-xs-300">';
           $cities = $service->cities->pluck('id')->toArray();
            foreach(\App\Models\City\City::all() as $key=>$item){
                $list .= ' <div class="">
                <input  type="checkbox" class=" checkbox__"
                value="'.$item->id.'"
                '.(in_array($item->id,$cities) ? 'checked' : '').'
                name="service_city[]" id="item_'.($key+1).'" >
                <label class="" for="item_'.($key+1).'">'.$item->city_name.'</label>
            </div> ';
               
            }
        
        
           $list .= '<div class="form-group wd-xs-300">
                <label for="recipient-name" class="col-form-label">نوع ارجاع:</label>
                <select required name="type_send"   class="form-control" id="exampleFormControlSelect2">
                    <option '.($service->service_type_send == 'ارجاع اتوماتیک' ? 'selected=""' : '').' value="ارجاع اتوماتیک">ارجاع اتوماتیک</option>
                    <option '.($service->service_type_send == 'ارجاع دستی' ? 'selected=""' : '').' value="ارجاع دستی">ارجاع دستی</option>  
                    <option '.($service->service_type_send == 'ارجاع منتخب' ? 'selected=""' : '').' value="ارجاع منتخب">ارجاع منتخب</option>  
                    <option '.($service->service_type_send == 'ارجاع به کمترین فاصله' ? 'selected=""' : '').' value="ارجاع به کمترین فاصله">ارجاع به کمترین فاصله</option>  
                </select>
            </div>
            <div class="row">
            <div class="form-group col-md-6">
            <label for="recipient-name" class="col-form-label">نام کارگزاری: </label>
            <select required name="service_role"   class="form-control" id="exampleFormControlSelect2">
                '. $brokers .'
                  
            </select>
            </div>
                <div class="form-group col-md-6">
                    <label>تغییر ایکون </label>
                    <input type="file" id="service_icon" name="service_icon" class="form-control" placeholder="" >
                    <div class="valid-feedback">
                        صحیح است!
                    </div>
                </div><!-- form-group -->
            </div>
    </section>
    <h3> قیمت</h3>
    <section>
        <div class="form-group wd-xs-300">
            <label for="recipient-name" class="col-form-label">نوع :</label>
            <select  name="price_type"   class="form-control" id="price_type">
                <option '.($service->price_type == 'توافقی' ? 'selected=""' : '').'  value="توافقی"> توافقی</option>
                <option '.($service->price_type == 'رقمی' ? 'selected=""' : '').' value="رقمی"> رقمی</option>
                <option '.($service->price_type == 'طبق لیست' ? 'selected=""' : '').' value="طبق لیست"> طبق لیست</option>
             </select>
        </div><!-- form-group -->
        <div class="form-group wd-xs-300" id="price-wrapper" 
        '.($service->price_type == 'رقمی' ? 'style="display:block;"' : 'style="display:none;"').'
        >
            <label class="form-control-label"> قیمت (به تومان):</label>
            <input id="service_price" 
            value="'.$service->service_price.'"
            class="form-control text-right" name="service_price" placeholder="0" type="number"  dir="rtl">
            <div class="valid-feedback">
                صحیح است!
            </div>
        </div><!-- form-group -->
        <div class="row">
        <div class="form-group col-md-6">
            <div class="service-img">
                <div class="chose-img">
                  <input type="file" class="btn-chose-img" id="" name="pic_1" title="نوع فایل میتواند png , jpg  باشد">
                </div>
                <img
                  style="background: #fff;
max-width: 100%;
height: 100%;
width: 100%;"
                  src="'.route('BaseUrl').$image1Url.'" alt="">
                <p class="text-chose-img" style="position: absolute;top: 44%;left: 33%;font-size: 13px;">انتخاب
                  تصویر</p>
              </div>
        
        </div><!-- form-group -->
        <div class="form-group col-md-6">
            <div class="service-img">
                <div class="chose-img">
                  <input type="file" class="btn-chose-img" id="" name="pic_2" title="نوع فایل میتواند png , jpg  باشد">
                </div>
                <img
                  style="background: #fff;
max-width: 100%;
height: 100%;
width: 100%;"
                  src="'.route('BaseUrl').$image2Url.'" alt="">
                <p class="text-chose-img" style="position: absolute;top: 44%;left: 33%;font-size: 13px;">انتخاب
                  تصویر</p>
              </div>
        </div><!-- form-group -->    
    </div> 
    </section>
    <h3>انتخاب سوالات از بانک</h3>
    <section>
    </section>
    <h3>پیشنهاد ویژه</h3>
    <section>
        <div class="form-group wd-xs-300">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" name="service_offered_status" id="customCheck" checked>
                <label class="custom-control-label" for="customCheck">به عنوان پیشنهاد ویژه در نظر گرفته شود</label>
            </div>     
            
        </div>   
        <div class="form-group wd-xs-300">
            <label for="recipient-name" class="col-form-label">این سرویس در چه خدماتی به عنوان ویژه در نظر گرفته شود: </label>
            <select  name="service_special_category"   class="form-control" id="exampleFormControlSelect2">';
            if (Cache::has('services')) {

                $services = Cache::get('services');
              } else {
                $services = Cache::remember('services', 60 * 5, function () {
                  return \App\Models\Services\Service::latest()->get();
                });
              }
           
            foreach ($services as $service){
            $list.='<option value="'.$service->service_id.'">'.$service->service_title.'</option>';
            } 
           $list.=' </select>
        </div> 
    </section>
    </form>
</div>
</div>';

return $list;

}

    public function SubmitServiceEdit(Request $request)
    {

 
      
       $service = Service::where('id',$request->service_id)->first();
        if ($request->has('service_icon')) {
            
            File::deleteDirectory(public_path('uploads/service_icons/'.$service->service_title));
            $icon = $request->title . '.' . $request->service_icon->getClientOriginalExtension();
            $request->service_icon->move(public_path('uploads/service_icons/'.$request->title), $icon);
        }else{
            $icon = $service->service_icon;
        }


        if ($request->has('pic_1')) {
            
            File::deleteDirectory(public_path('uploads/service_pics/'.$request->title.'/pic1'));
            $pic1 = $request->title . '.' . $request->pic_1->getClientOriginalExtension();
            $request->pic_1->move(public_path('uploads/service_pics/'.$request->title.'/pic1'), $pic1);
        }else{
            $pic1 = $service->service_pic_first;
        }


        if ($request->has('pic_2')) {
            
            File::deleteDirectory(public_path('uploads/service_pics/'.$request->title.'/pic2'));
            $pic2 = $request->title . '.' . $request->pic_1->getClientOriginalExtension();
            $request->pic_1->move(public_path('uploads/service_pics/'.$request->title.'/pic2'), $pic2);
        }else{
            $pic2 = $service->service_pic_second;
        }
            $array =[
                'service_title' => $request->title,
                'service_category_id' => $request->service_category,
                'service_role' => $request->service_role,
                'service_percentage' => $request->service_percentage,
                'service_offered_price' => $request->service_offered_price,
                'service_desc' => $request->service_desc,
                'service_price' => $request->service_price,
                'service_alerts' => $request->service_alerts,
                'service_type_send' => $request->type_send,
                'price_type' => $request->price_type,
                'service_offered_status' => $request->service_offered_status,
                'service_special_category' => $request->service_special_category,
                'service_icon' => $icon,
                'service_pic_first' => $pic1,
                'service_pic_second' => $pic2
                    ];
        
        Service::where('id',$request->service_id)->update($array);
        // if ($request->has('service_role')) {
        //     $service->user()->attach($request->service_role);
        // }
        $service->cities()->detach();
        $service->cities()->attach($request->service_city);
        alert()->success(' خدمت با موفقیت ویرایش شد', 'عملیات موفق')->autoclose(2000);
        return back();
    }

    public function ServiceOrderBy(Request $request)
    {
        
        
        if ($request->data == 'title') {
            $services = Service::OrderBy('service_title','ASC')->get();
          }
          if ($request->data == 'persent') {
            $services = Service::OrderBy('service_percentage','DESC')->get();
          }
          if ($request->data == 'broker_name') {
            $services = Service::OrderBy('service_broker_name','ASC')->get();
          }
          
          $tbody ='';
          foreach ($services as $key => $service) {
            $tbody .= '
            <tr>
            <td>
              <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
              <input data-id="'.$service->id.'" type="checkbox" id="'.$key.'" name="customCheckboxInline1" class="custom-control-input" value="1">
                <label class="custom-control-label" for="'.$key.'"></label>
              </div>
            </td>
            <td> '.($key+1).' </td>
            <td>'.$service->service_title.'</td>
            <td>'.$service->service_broker_name.'</td>
            <td>'.($service->service_desc !== null ? $service->service_desc : '--').'</td>
            <td>'.$service->relationCategory->category_title.'</td>
            <td>'.$service->service_rol.'</td>
            <td>--</td>
            <td>'.$service->service_percentage . '%</td>
            <td>--</td>
            <td>'.$service->price_type.'</td>
            <td>'.$service->service_type_send.'</td>
            <td>'. ($service->service_icon !== '' ? 
            '<img width="75px" class="img-fluid " src="'.asset("uploads/service_icons/$service->service_title/$service->service_icon").'" />'
        :
      ' ندارد'
        ).'</td>
            
          </tr>
    
            ';
          }
         
          return $tbody;
    }

    public function FilterServices(Request $request)
    {
      
        
      if($request->type_send == 'عنوان'){
        $services =  Service::where('service_title', 'like', '%' . $request->word. '%')
        ->get();
      }  
      if($request->type_send == 'نوع'){
        $services =  Service::where('user_firstname', 'like', '%' . $request->word. '%')
        ->get();
      }  
      if($request->type_send == 'نقش'){
        $services =  Service::where('user_firstname', 'like', '%' . $request->word. '%')
        ->get();
      }  
    //   if($request->type_send == 'دسته بندی خدمات'){
    //     $services =  Service::where('service_category_id',$request->word)
    //     ->get();
    //   }  
      if($request->type_send == 'نوع قیمت'){
        $services =  Service::where('price_type',$request->word)
        ->get();
      }  
      if($request->type_send == 'نوع ارجاع'){
        $services =  Service::where('service_type_send',$request->word)
        ->get();
      } 

      $category_parent_list = ServiceCategory::where('category_parent',0)->get();
      $count = ServiceCategory::where('category_parent',0)->count();
       $list ='';
      foreach ($category_parent_list as $key => $item) {
          
          $list .= '<option data-id="'.$item->id.'" value="'.$item->id.'" class="level-1">'.$item->category_title.' 
           '.(count(ServiceCategory::where('category_parent',$item->id)->get()) ? '&#xf104;  ' : '' ).'
          </option>';
         
          foreach (ServiceCategory::where('category_parent',$item->id)->get() as $key => $subitem) {
              $list .= '<option data-parent="'.$item->id.'" value="'.$subitem->id.'" class="level-2">'.$subitem->category_title.'</option>';
          }
      }

    
       return view('User.Services.ServiceList',compact(['list','count','services']));

    }
}
