<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Services\Service;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct()
{
    $this->middleware(['role:admin_panel']);
}
    public function RolesList()
    {
       $roles = Role::orderBy('name')->get();
       $brokers = Role::where('broker',1)->get();

       return view('User.Roles.RolesList',compact(['roles','brokers']));
    }

    public function InsertRole(Request $request)
    {
     $array = $request->except(['role_name','_token','broker_status','broker_id']);
     if ($request->broker_status !== null) {
         if ($request->broker_id !== null) {
            $role = Role::create([
                'name' => $request->role_name,
                'sub_broker' => $request->broker_id
                ]);
         }else{
            $role = Role::create([
                'name' => $request->role_name,
                'broker' => 1,
                ]);
         }

     }else{
        $role = Role::create([
            'name' => $request->role_name,
            ]);
     }
     
       

            $role->givePermissionTo(array_keys($array));
            alert()->success('نقش با موفقیت ثبت گردید', 'عملیات موفق')->autoclose(2000);
            return back();

        // $permission = Permission::create([
        //         'name' => 'insert user',
        //         'name' => 'user transaction',
        //         'name' => 'user pass',
        //         'name' => 'user menu',
        //         'name' => 'user list',
        //         'name' => 'user delete',
        //         'name' => 'user edit',
        //         'name' => 'personal online menu',
        //         'name' => 'personal online list',
        //         'name' => 'city insert',
        //         'name' => 'city delete',
        //         'name' => 'city list',
        //         'name' => 'customer menu',
        //         'name' => 'customer list',
        //         'name' => 'customer delete',
        //         'name' => 'customer excel',
                
        //         ]);
    }

    public function DeleteRole(Request $request)
    {
        foreach ($request->array as $role_id) {
           $role_name = Role::where('id',$role_id)->first()->name;
           Service::where('service_role',$role_name)->delete();
           Role::where('id',$role_id)->delete();
        }
        return 'success';
    }

    public function getData(Request $request)
    {
     
        $role = Role::where('id',$request->id)->first();
        $csrf = csrf_token();

        $permissions = $role->permissions->pluck('name')->toArray();

        $list = ' <div class="modal-body">
        <div id="wizard2">
            <form id="example-advanced-form1" method="post" action="'.route('Roles.Edit.Submit').'"
                enctype="multipart/form-data">
                <input type="hidden" name="_token" value="'.$csrf.'">
                <input type="hidden" name="role_id" value="'.$role->id.'">
                <h3>نقش</h3>
                <section>
                    <div class="form-group wd-xs-300">
                        <label>نام </label>
                        <input type="text" id="role_name" name="role_name"
                        value="'.$role->name.'"
                        class="form-control"
                            placeholder="نام">
                    </div><!-- form-group -->
                    <div class="form-group wd-xs-300">
                        <div 
                            style="margin-left: -1rem;">
                            <input type="checkbox" id="broker_status" name="broker_status"
                                class="" value="1"
                                '.($role->broker == 1 ? 'checked=""' : '').'
                                >
                            <label class="" for="broker_status">به عنوان کارگزاری در نظر
                                گرفته شود</label>
                        </div>
                    </div>
                </section>
                <h3> مجوز ها</h3>
                <section>
                    <p>کاربران</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="insert_user" name="insert_user"
                                        class="" value="1"
                                        '.(in_array('insert_user',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="insert_user">ثبت کاربر</label>
                                </div>
                            </div>
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="user_transaction" name="user_transaction"
                                        class="" value="1"
                                        '.(in_array('user_transaction',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="user_transaction">تراکنش های
                                        کاربر</label>
                                </div>
                            </div>
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="user_pass" name="user_pass"
                                        class="" value="1"
                                        '.(in_array('user_pass',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="user_pass">تغییر پسورد
                                        کاربر</label>
                                </div>
                            </div>
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="user_menu" name="user_menu"
                                        class="" value="1"
                                        '.(in_array('user_menu',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="user_menu">منو کاربر</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="user_list" name="user_list"
                                        class="" value="1"
                                        '.(in_array('user_list',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="user_list">لیست کاربر</label>
                                </div>

                            </div>

                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="user_delete" name="user_delete"
                                        class="" value="1"
                                        '.(in_array('user_delete',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="user_delete">حذف کاربر</label>
                                </div>

                            </div>
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="user_edit" name="user_edit"
                                        class="" value="1"
                                        '.(in_array('user_edit',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="user_edit">ویرایش کاربر</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                   
                    <p>دسته بندی خدمات</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="category_menu" name="category_menu"
                                         value="1"
                                         '.(in_array('category_menu',$permissions) ? 'checked=""' : '').'
                                         >
                                    <label  for="category_menu">منوی دسته بندی</label>
                                </div>

                            </div>
                        </div>
                    </div>  
                    <div class="row c" >
                            <div class="col-md-4">
                                <div class="form-group wd-xs-300">
                                    <div class=""
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="category_insert" name="category_insert"
                                             value="1"
                                             '.(in_array('category_insert',$permissions) ? 'checked=""' : '').'
                                             >
                                        <label  for="category_insert">افزودن دسته بندی</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group wd-xs-300">
                                    <div class=""
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="category_delete" name="category_delete"
                                             value="1"
                                             '.(in_array('category_delete',$permissions) ? 'checked=""' : '').'
                                             >
                                        <label  for="category_delete">حذف دسته بندی</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group wd-xs-300">
                                    <div class=""
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="category_edit" name="category_edit"
                                             value="1"
                                             '.(in_array('category_edit',$permissions) ? 'checked=""' : '').'
                                             >
                                        <label  for="category_edit">ویرایش دسته بندی</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                      
                        <hr>
                        <p>خدمات</p>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group wd-xs-300">
                                    <div class=""
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="service_menu" name="service_menu"
                                             value="1"
                                             '.(in_array('service_menu',$permissions) ? 'checked=""' : '').'
                                             >
                                        <label  for="service_menu">منوی خدمات</label>
                                    </div>

                                </div>
                            </div>
                        </div>  
                        <div class="row " >
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class=""
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="service_insert" name="service_insert"
                                                 value="1"
                                                 '.(in_array('service_insert',$permissions) ? 'checked=""' : '').'
                                                 >
                                            <label  for="service_insert">افزودن خدمت</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class=""
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="service_delete" name="service_delete"
                                                 value="1"
                                                 '.(in_array('service_delete',$permissions) ? 'checked=""' : '').'
                                                 >
                                            <label  for="service_delete">حذف خدمت</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class=""
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="service_edit" name="service_edit"
                                                 value="1"
                                                 '.(in_array('service_edit',$permissions) ? 'checked=""' : '').'
                                                 >
                                            <label  for="service_edit">ویرایش خدمت</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <hr>
                                    <p>خدمت رسان ها</p>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group wd-xs-300">
                                                <div 
                                                    style="margin-left: -1rem;">
                                                    <input type="checkbox" id="personal_menu" name="personal_menu"
                                                         value="1"
                                                          '.(in_array('personal_menu',$permissions) ? 'checked=""' : '').'
                                                         >
                                                    <label  for="personal_menu">منوی خدمت رسان ها</label>
                                                </div>
        
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row ">
                                            <div class="col-md-4">
                                                <div class="form-group wd-xs-300">
                                                    <div 
                                                        style="margin-left: -1rem;">
                                                        <input type="checkbox" id="personal_insert" name="personal_insert"
                                                             value="1"
                                                              '.(in_array('personal_insert',$permissions) ? 'checked=""' : '').'
                                                             >
                                                        <label  for="personal_insert">افزودن خدمت رسان</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group wd-xs-300">
                                                    <div 
                                                        style="margin-left: -1rem;">
                                                        <input type="checkbox" id="personal_delete" name="personal_delete"
                                                             value="1"
                                                              '.(in_array('personal_delete',$permissions) ? 'checked=""' : '').'
                                                             >
                                                        <label  for="personal_delete">حذف خدمت رسان</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group wd-xs-300">
                                                    <div >
                                                        
                                                        <input type="checkbox" id="personal_edit" name="personal_edit"
                                                             value="1"
                                                              '.(in_array('personal_edit',$permissions) ? 'checked=""' : '').'
                                                             >
                                                        <label  for="personal_edit">ویرایش خدمت رسان</label>
                                                    </div>
                                                </div>
            
                                            </div>
                                        </div>
                                        <hr>
                                        <p>گردش کار</p>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group wd-xs-300">
                                                    <div 
                                                        style="margin-left: -1rem;">
                                                        <input type="checkbox" id="orders_menu" name="orders_menu"
                                                             value="1"
                                                              '.(in_array('orders_menu',$permissions) ? 'checked=""' : '').'
                                                             >
                                                        <label  for="orders_menu">منوی گردش کار</label>
                                                    </div>
            
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="row ">
                                                <div class="col-md-6">
                                                    <div class="form-group wd-xs-300">
                                                        <div 
                                                            style="margin-left: -1rem;">
                                                            <input type="checkbox" id="orders_insert" name="orders_insert"
                                                                 value="1"
                                                                  '.(in_array('orders_insert',$permissions) ? 'checked=""' : '').'
                                                                 >
                                                            <label  for="orders_insert">افزودن سفارش</label>
                                                        </div>
                                                    </div>
                                              
                                                    <div class="form-group wd-xs-300">
                                                        <div 
                                                            style="margin-left: -1rem;">
                                                            <input type="checkbox" id="orders_delete" name="orders_delete"
                                                                 value="1"
                                                                  '.(in_array('orders_delete',$permissions) ? 'checked=""' : '').'
                                                                 >
                                                            <label  for="orders_delete">حذف سفارش</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group wd-xs-300">
                                                        <div 
                                                            style="margin-left: -1rem;">
                                                            <input type="checkbox" id="orders_transactions" name="orders_transactions"
                                                                 value="1"
                                                                  '.(in_array('orders_transactions',$permissions) ? 'checked=""' : '').'
                                                                 >
                                                            <label  for="orders_transactions">تراکنش های سفارش</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group wd-xs-300">
                                                        <div >
                                                            
                                                            <input type="checkbox" id="orders_refferto" name="orders_refferto"
                                                                 value="1"
                                                                  '.(in_array('orders_refferto',$permissions) ? 'checked=""' : '').'
                                                                 >
                                                            <label  for="orders_refferto">ارجاع سفارش</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group wd-xs-300">
                                                        <div >
                                                            
                                                            <input type="checkbox" id="orders_detail" name="orders_detail"
                                                                 value="1"
                                                                  '.(in_array('orders_detail',$permissions) ? 'checked=""' : '').'
                                                                 >
                                                            <label  for="orders_detail">جزئیات سفارش</label>
                                                        </div>
                                                    </div>
                
                                                </div>
                                            </div>
                                            <hr>
                    <p>گزارش خدمت رسان های انلاین</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group wd-xs-300">
                                <div class="">
                                    
                                    <input type="checkbox" id="personal_online_menu" name="personal_online_menu"
                                        class="" value="1"
                                        '.(in_array('personal_online_menu',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="personal_online_menu">منو خدمت رسان
                                        های انلاین</label>
                                </div>

                            </div>
                            <div class="form-group wd-xs-300">
                                <div class="">
                                    
                                    <input type="checkbox" id="personal_online_list" name="personal_online_list"
                                        class="" value="1"
                                        '.(in_array('personal_online_list',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="personal_online_list">لیست خدمت
                                        رسان های انلاین</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p>شهر</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group wd-xs-300">
                                <div class="" >
                                    
                                    <input type="checkbox" id="city_insert" name="city_insert"
                                        class="" value="1"
                                        '.(in_array('city_insert',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="city_insert">ثبت شهر</label>
                                </div>

                            </div>
                            <div class="form-group wd-xs-300">
                                <div class="" >
                                    
                                    <input type="checkbox" id="city_edit" name="city_edit"
                                        class="" value="1"
                                        '.(in_array('city_edit',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="city_edit">ویرایش شهر</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group wd-xs-300">
                                <div class="" 
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="city_delete" name="city_delete"
                                        class="" value="1"
                                         '.(in_array('city_delete',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="city_delete">حذف شهر</label>
                                </div>
                            </div>
                            <div class="form-group wd-xs-300">
                                <div class="" 
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="city_menu" name="city_menu"
                                        class="" value="1"
                                         '.(in_array('city_menu',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="city_menu">منوی شهر</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="city_list" name="city_list"
                                        class="" value="1"
                                         '.(in_array('city_list',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="city_list">لیست شهر</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p>مشتری</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="customer_menu" name="customer_menu"
                                        class="" value="1"
                                         '.(in_array('customer_menu',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="customer_menu">منوی مشتری</label>
                                </div>

                            </div>
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="customer_list" name="customer_list"
                                        class="" value="1"
                                         '.(in_array('customer_list',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="customer_list">لیست مشتری</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="customer_delete" name="customer_delete"
                                        class="" value="1"
                                         '.(in_array('customer_delete',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="customer_delete">حذف مشتری</label>
                                </div>
                            </div>
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="customer_edit" name="customer_edit"
                                        class="" value="1"
                                         '.(in_array('customer_edit',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="customer_edit">ویرایش مشتری</label>
                                </div>
                            </div>
                        </div>


                    </div>
                    <hr>
                    <p>فروشگاه</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="stores_menu" name="stores_menu"
                                        class="" value="1"
                                         '.(in_array('stores_menu',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="stores_menu">منوی فروشگاه</label>
                                </div>

                            </div>
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="stores_create" name="stores_create"
                                        class="" value="1"
                                         '.(in_array('stores_create',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="stores_create">افزودن فروشگاه</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="stores_delete" name="stores_delete"
                                        class="" value="1"
                                         '.(in_array('stores_delete',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="stores_delete">حذف فروشگاه</label>
                                </div>
                            </div>
                            <div class="form-group wd-xs-300">
                                <div class=""
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="stores_edit" name="stores_edit"
                                        class="" value="1"
                                         '.(in_array('stores_edit',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label class="" for="stores_edit">ویرایش فروشگاه</label>
                                </div>
                            </div>
                        </div>


                    </div>
                    <hr>
                    <p>حسابداری</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group wd-xs-300">
                                <div 
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="accounting" name="accounting"
                                         value="1"
                                        '.(in_array('accounting',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label  for="accounting">منوی حسابداری</label>
                                </div>

                            </div>
                          
                        </div>
                    </div>
                    <div class="row accounting--section" >
                        <div class="col-md-4">
                            <div class="form-group wd-xs-300">
                                <div 
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="user_accounts_personals" name="user_accounts_personals"
                                         value="1"
                                        '.(in_array('user_accounts_personals',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label  for="user_accounts_personals">حساب خدمت رسان ها</label>
                                </div>
                            </div>
                            <div class="form-group wd-xs-300">
                                <div 
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="user_accounts_customers" name="user_accounts_customers"
                                         value="1"
                                        '.(in_array('user_accounts_customers',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label  for="user_accounts_customers">حساب مشتری ها</label>
                                </div>
                            </div>

                        </div>   

                        <div class="col-md-4">
                        <div class="form-group wd-xs-300">
                        <div 
                            style="margin-left: -1rem;">
                            <input type="checkbox" id="user_transactions_personals" name="user_transactions_personals"
                                 value="1"
                                '.(in_array('user_transactions_personals',$permissions) ? 'checked=""' : '').'
                                >
                            <label  for="user_transactions_personals">تراکنش های خدمت رسان ها</label>
                        </div>
                    </div>
                        <div class="form-group wd-xs-300">
                            <div 
                                style="margin-left: -1rem;">
                                <input type="checkbox" id="user_transactions_customers" name="user_transactions_customers"
                                     value="1"
                                    '.(in_array('user_transactions_customers',$permissions) ? 'checked=""' : '').'
                                    >
                                <label  for="user_transactions_customers">تراکنش های مشتری ها</label>
                            </div>
                        </div>

                    </div>   


                        <div class="col-md-4">
                        
                            <div class="form-group wd-xs-300">
                                <div 
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="checkout_personals" name="checkout_personals"
                                         value="1"
                                        '.(in_array('checkout_personals',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label  for="checkout_personals">تسویه حساب خدمت رسان ها</label>
                                </div>
                            </div>
                            </div>


                    </div>
                    <hr>
                    <p>نوتیفیکیشن ها</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group wd-xs-300">
                                <div 
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="notifications" name="notifications"
                                         value="1"
                                        '.(in_array('notifications',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label  for="notifications">منوی نوتیفیکشن ها</label>
                                </div>

                            </div>
                            <div 
                            style="margin-left: -1rem;">
                            <input type="checkbox" id="notifications_add" name="notifications_add"
                                 value="1"
                                '.(in_array('notifications_add',$permissions) ? 'checked=""' : '').'
                                >
                            <label  for="notifications_add">افزودن نوتیفیکشن </label>
                        </div>

                    </div>
                    <div 
                    style="margin-left: -1rem;">
                    <input type="checkbox" id="notifications_send" name="notifications_send"
                         value="1"
                        '.(in_array('notifications_send',$permissions) ? 'checked=""' : '').'
                        >
                    <label  for="notifications_send">ارسال نوتیفیکشن </label>
                </div>

            
                          
                    </div>

                    <hr>
                    <p>مدیریت اپلیکیشن</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group wd-xs-300">
                                <div 
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="appmanage" name="appmanage"
                                         value="1"
                                        '.(in_array('appmanage',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label  for="appmanage">منوی مدیریت اپلیکیشن</label>
                                </div>

                            </div>
                            <div class="form-group wd-xs-300">
                                <div 
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="appmenu" name="appmenu"
                                         value="1"
                                        '.(in_array('appmenu',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label  for="appmenu">منوی مدیریت منوی اپلیکیشن</label>
                                </div>

                            </div>
                          
                        </div>

                        <div class="col-md-4">
                            <div class="form-group wd-xs-300">

                            <div 
                            style="margin-left: -1rem;">
                            <input type="checkbox" id="appworkerannounc" name="appworkerannounc"
                                 value="1"
                                '.(in_array('appworkerannounc',$permissions) ? 'checked=""' : '').'
                                >
                            <label  for="appworkerannounc">منوی مدیریت اطلاعیه های خدمت رسان</label>
                        </div>
                            
                            </div>
                            <div class="form-group wd-xs-300">

                            <div 
                            style="margin-left: -1rem;">
                            <input type="checkbox" id="appslideshow" name="appslideshow"
                                 value="1"
                                '.(in_array('appslideshow',$permissions) ? 'checked=""' : '').'
                                >
                            <label  for="appslideshow">منوی اسلایدشو اطلاعیه های خدمت رسان</label>
                        </div>
                            
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p>تنظیمات</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group wd-xs-300">
                                <div 
                                    style="margin-left: -1rem;">
                                    <input type="checkbox" id="setting" name="setting"
                                         value="1"
                                        '.(in_array('setting',$permissions) ? 'checked=""' : '').'
                                        >
                                    <label  for="setting">منوی تنظیمات</label>
                                </div>

                            </div>
                          
                        </div>
                    </div>


                </section>

            </form>
        </div>
    </div>';

    return $list;
    }

    public function SubmitEditRole(Request $request)
    {
       
        $all_permissions = [
            'user_transaction',
            'user_pass',
            'insert_user',
            'user_menu',
            'user_list',
            'user_delete',
            'user_edit',
            'personal_online_menu',
            'personal_online_list',
            'city_insert',
            'city_delete',
            'city_list',
            'city_menu',
            'city_edit',
            'customer_menu',
            'customer_list',
            'customer_delete',
            'customer_edit',
            'category_menu',
            'category_insert',
            'category_delete',
            'category_edit',
            'service_menu',
            'service_insert',
            'service_delete',
            'service_edit',
            'personal_menu',
            'personal_insert',
            'personal_delete',
            'personal_edit',
            'orders_menu',
            'orders_insert',
            'orders_delete',
            'orders_refferto',
            'orders_detail',
            'orders_transactions',
            'stores_menu',
            'stores_create',
            'store_edit',
            'store_delete',
            'user_accounts_personals',
            'user_transactions_personals',
            'checkout_personals',
            'setting',
            'accounting',
            'appmanage',
            'appmenu',
            'user_accounts_customers',
            'user_transactions_customers',
            'notifications'

        ];

    $role = Role::where('id',$request->role_id)->update([
            'name' => $request->role_name,
            'broker' => $request->broker_status,
            'sub_broker' => $request->broker_id
            ]);

    $role_model = Role::find($request->role_id);
    $array = $request->except(['role_name','_token','broker_status','role_id']);
    $role_model->revokePermissionTo($all_permissions);
    $role_model->givePermissionTo(array_keys($array));
    alert()->success('نقش با موفقیت ویرایش گردید', 'عملیات موفق')->autoclose(2000);
    return back();

    }
}
