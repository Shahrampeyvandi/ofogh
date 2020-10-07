@extends('Layouts.Pannel.Template')

@section('content')
{{-- modal for delete --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">اخطار</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                موارد علامت زده شده حذف شوند؟
            </div>
            <div class="modal-footer">
                <a type="button" class="delete btn btn-danger text-white">حذف! </a>
            </div>
        </div>
    </div>
</div>


{{-- modal for create --}}
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div id="wizard2">
                    <form id="example-advanced-form" method="post" action="{{route('Role.Submit')}}"
                        enctype="multipart/form-data">
                        @csrf
                        <h3>نقش</h3>
                        <section>
                            <div class="form-group wd-xs-300">
                                <label>نام </label>
                                <input type="text" id="role_name" name="role_name" class="form-control"
                                    placeholder="نام">

                            </div><!-- form-group -->

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="broker_status" name="broker_status" class="custom-control-input"
                                  value="1" checked>
                                <label class="custom-control-label" for="broker_status" >به عنوان کارگزاری در نظر
                                    گرفته شود</label>
                              </div>
                              <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="sub_broker_status" name="broker_status" class="custom-control-input"
                                  value="1">
                                <label class="custom-control-label" for="sub_broker_status">به عنوان زیر مجموعه کارگزاری در نظر گرفته شود
                                    </label>
                              </div>
                           
                            
                            <div class="form-group wd-xs-300 broker-select" style="display:none;">
                                <label for="recipient-name" class="col-form-label">نام کارگزاری</label>
                                <select  name="broker_id"   class="form-control" id="exampleFormControlSelect2">
                                    <option value="" selected="">باز کردن فهرست انتخاب</option>
                                  @foreach ($brokers as $broker)
                                  <option value="{{$broker->id}}">{{$broker->name}}</option>  
                                  @endforeach
                                </select>
                            </div>
                        </section>
                        <h3> مجوز ها</h3>
                        <section>
                            <p>کاربران</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="user_menu" name="user_menu"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="user_menu">منو کاربر</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row user--permisions" style="display:none;">
                                <div class="col-md-6">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="user_list" name="user_list"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="user_list">لیست کاربر</label>
                                        </div>

                                    </div>
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="insert_user" name="insert_user"
                                                class="custom-control-input" value="1" >
                                            <label class="custom-control-label" for="insert_user">ثبت کاربر</label>
                                        </div>

                                    </div>
                                
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="user_pass" name="user_pass"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="user_pass">تغییر پسورد
                                                کاربر</label>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="user_delete" name="user_delete"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="user_delete">حذف کاربر</label>
                                        </div>

                                    </div>
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="user_edit" name="user_edit"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="user_edit">ویرایش کاربر</label>
                                        </div>
                                    </div>
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="user_transaction" name="user_transaction"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="user_transaction">تراکنش های
                                                کاربر</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <hr>
                            <p>دسته بندی خدمات</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="category_menu" name="category_menu"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="category_menu">منوی دسته بندی</label>
                                        </div>

                                    </div>
                                </div>
                            </div>  
                            <div class="row category--permisions" style="display:none;">
                                    <div class="col-md-4">
                                        <div class="form-group wd-xs-300">
                                            <div class="custom-control custom-checkbox custom-control-inline"
                                                style="margin-left: -1rem;">
                                                <input type="checkbox" id="category_insert" name="category_insert"
                                                    class="custom-control-input" value="1">
                                                <label class="custom-control-label" for="category_insert">افزودن دسته بندی</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group wd-xs-300">
                                            <div class="custom-control custom-checkbox custom-control-inline"
                                                style="margin-left: -1rem;">
                                                <input type="checkbox" id="category_delete" name="category_delete"
                                                    class="custom-control-input" value="1">
                                                <label class="custom-control-label" for="category_delete">حذف دسته بندی</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group wd-xs-300">
                                            <div class="custom-control custom-checkbox custom-control-inline"
                                                style="margin-left: -1rem;">
                                                <input type="checkbox" id="category_edit" name="category_edit"
                                                    class="custom-control-input" value="1">
                                                <label class="custom-control-label" for="category_edit">ویرایش دسته بندی</label>
                                            </div>
                                        </div>
    
                                    </div>
                                </div>
                              
                                <hr>
                                <p>خدمات</p>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group wd-xs-300">
                                            <div class="custom-control custom-checkbox custom-control-inline"
                                                style="margin-left: -1rem;">
                                                <input type="checkbox" id="service_menu" name="service_menu"
                                                    class="custom-control-input" value="1">
                                                <label class="custom-control-label" for="service_menu">منوی خدمات</label>
                                            </div>
    
                                        </div>
                                    </div>
                                </div>  
                                <div class="row service--permisions" style="display:none;">
                                        <div class="col-md-4">
                                            <div class="form-group wd-xs-300">
                                                <div class="custom-control custom-checkbox custom-control-inline"
                                                    style="margin-left: -1rem;">
                                                    <input type="checkbox" id="service_insert" name="service_insert"
                                                        class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="service_insert">افزودن خدمت</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group wd-xs-300">
                                                <div class="custom-control custom-checkbox custom-control-inline"
                                                    style="margin-left: -1rem;">
                                                    <input type="checkbox" id="service_delete" name="service_delete"
                                                        class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="service_delete">حذف خدمت</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group wd-xs-300">
                                                <div class="custom-control custom-checkbox custom-control-inline"
                                                    style="margin-left: -1rem;">
                                                    <input type="checkbox" id="service_edit" name="service_edit"
                                                        class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="service_edit">ویرایش خدمت</label>
                                                </div>
                                            </div>
        
                                        </div>
                                    </div>
                                  
                                    <hr>
                                    <p>خدمت رسان ها</p>
                                    <div class="row ">
                                        <div class="col-md-12 ">
                                            <div class="form-group wd-xs-300">
                                                <div class="custom-control custom-checkbox custom-control-inline"
                                                    style="margin-left: -1rem;">
                                                    <input type="checkbox" id="personal_menu" name="personal_menu"
                                                        class="custom-control-input" value="1">
                                                    <label class="custom-control-label" for="personal_menu">منوی خدمت رسان ها</label>
                                                </div>
        
                                            </div>
                                        </div>
                                    </div>  
                                    <div class="row personal--permisions" style="display:none;">
                                            <div class="col-md-4">
                                                <div class="form-group wd-xs-300">
                                                    <div class="custom-control custom-checkbox custom-control-inline"
                                                        style="margin-left: -1rem;">
                                                        <input type="checkbox" id="personal_insert" name="personal_insert"
                                                            class="custom-control-input" value="1">
                                                        <label class="custom-control-label" for="personal_insert">افزودن خدمت رسان</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group wd-xs-300">
                                                    <div class="custom-control custom-checkbox custom-control-inline"
                                                        style="margin-left: -1rem;">
                                                        <input type="checkbox" id="personal_delete" name="personal_delete"
                                                            class="custom-control-input" value="1">
                                                        <label class="custom-control-label" for="personal_delete">حذف خدمت رسان</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group wd-xs-300">
                                                    <div class="custom-control custom-checkbox custom-control-inline"
                                                        style="margin-left: -1rem;">
                                                        <input type="checkbox" id="personal_edit" name="personal_edit"
                                                            class="custom-control-input" value="1">
                                                        <label class="custom-control-label" for="personal_edit">ویرایش خدمت رسان</label>
                                                    </div>
                                                </div>
            
                                            </div>
                                        </div>
                                        <hr>
                                        <p>گردش کار</p>
                                        <div class="row ">
                                            <div class="col-md-12 ">
                                                <div class="form-group wd-xs-300">
                                                    <div class="custom-control custom-checkbox custom-control-inline"
                                                        style="margin-left: -1rem;">
                                                        <input type="checkbox" id="orders_menu" name="orders_menu"
                                                            class="custom-control-input" value="1">
                                                        <label class="custom-control-label" for="orders_menu">منوی گردش کار</label>
                                                    </div>
            
                                                </div>
                                            </div>
                                        </div>  
                                        <div class="row orders--permisions" style="display:none;">
                                                <div class="col-md-6">
                                                    <div class="form-group wd-xs-300">
                                                        <div class="custom-control custom-checkbox custom-control-inline"
                                                            style="margin-left: -1rem;">
                                                            <input type="checkbox" id="orders_insert" name="orders_insert"
                                                                class="custom-control-input" value="1">
                                                            <label class="custom-control-label" for="orders_insert">افزودن سفارش</label>
                                                        </div>
                                                    </div>
                                                
                                                    <div class="form-group wd-xs-300">
                                                        <div class="custom-control custom-checkbox custom-control-inline"
                                                            style="margin-left: -1rem;">
                                                            <input type="checkbox" id="orders_delete" name="orders_delete"
                                                                class="custom-control-input" value="1">
                                                            <label class="custom-control-label" for="orders_delete">حذف سفارش</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group wd-xs-300">
                                                        <div class="custom-control custom-checkbox custom-control-inline"
                                                            style="margin-left: -1rem;">
                                                            <input type="checkbox" id="orders_detail" name="orders_detail"
                                                                class="custom-control-input" value="1">
                                                            <label class="custom-control-label" for="orders_detail">جزئیات سفارش</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    
                                                    <div class="form-group wd-xs-300">
                                                        <div class="custom-control custom-checkbox custom-control-inline"
                                                            style="margin-left: -1rem;">
                                                            <input type="checkbox" id="orders_transactions" name="orders_transactions"
                                                                class="custom-control-input" value="1">
                                                            <label class="custom-control-label" for="orders_transactions">تراکنش های سفارش</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group wd-xs-300">
                                                        <div class="custom-control custom-checkbox custom-control-inline"
                                                            style="margin-left: -1rem;">
                                                            <input type="checkbox" id="orders_refferto" name="orders_refferto"
                                                                class="custom-control-input" value="1">
                                                            <label class="custom-control-label" for="orders_refferto">ارجاع سفارش</label>
                                                        </div>
                                                    </div>
                
                                                </div>
                                            </div>
                                          
                                    <hr>  
                                
                            <p>گزارش خدمت رسان های انلاین</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="personal_online_menu" name="personal_online_menu"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="personal_online_menu">منو خدمت رسان
                                                های انلاین</label>
                                        </div>

                                    </div>
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="personal_online_list" name="personal_online_list"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="personal_online_list">لیست خدمت
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
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="city_insert" name="city_insert"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="city_insert">ثبت شهر</label>
                                        </div>

                                    </div>
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="city_edit" name="city_edit"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="city_edit">ویرایش شهر</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="city_delete" name="city_delete"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="city_delete">حذف شهر</label>
                                        </div>
                                    </div>
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="city_menu" name="city_menu"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="city_menu">منوی شهر</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="city_list" name="city_list"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="city_list">لیست شهر</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <p>مشتری</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="customer_menu" name="customer_menu"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="customer_menu">منوی مشتری</label>
                                        </div>

                                    </div>
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="customer_list" name="customer_list"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="customer_list">لیست مشتری</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="customer_delete" name="customer_delete"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="customer_delete">حذف مشتری</label>
                                        </div>
                                    </div>
                                     <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="customer_edit" name="customer_edit"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="customer_edit">ویرایش مشتری</label>
                                        </div>
                                    </div> 
                                </div>

                            </div>
                            <hr>
                            <p>فروشگاه</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="stores_menu" name="stores_menu"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="stores_menu">منوی فروشگاه</label>
                                        </div>

                                    </div>
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="stores_create" name="stores_create"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="stores_create">افزودن فروشگاه</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="stores_delete" name="stores_delete"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="stores_delete">حذف فروشگاه</label>
                                        </div>
                                    </div>
                                     <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="stores_edit" name="stores_edit"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="stores_edit">ویرایش فروشگاه</label>
                                        </div>
                                    </div> 
                                </div>

                            </div>
                            <hr>
                            <p>حسابداری</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="accounting" name="accounting"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="accounting">منوی حسابداری</label>
                                        </div>

                                    </div>
                                  
                                </div>
                            </div>
                            <div class="row accounting--section" style=" display:none;">
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="user_accounts_personals" name="user_accounts_personals"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="user_accounts_personals">حساب خدمت رسان ها</label>
                                        </div>
                                    </div>

                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="user_accounts_customers" name="user_accounts_customers"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="user_accounts_customers">حساب مشتری ها</label>
                                        </div>
                                    </div>
                                </div>   
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="user_transactions_personals" name="user_transactions_personals"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="user_transactions_personals">تراکنش های خدمت رسان ها</label>
                                        </div>
                                    </div>

                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="user_transactions_customers" name="user_transactions_customers"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="user_transactions_customers">تراکنش های مشتری ها</label>
                                        </div>
                                    </div>
                                </div>   

                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="checkout_personals" name="checkout_personals"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="checkout_personals">تسویه حساب خدمت رسان ها</label>
                                        </div>
                                    </div>
                                </div>


                            </div>
                            <hr>
                            <p>نوتیفیکیشن ها</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="notifications" name="notifications"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="notifications">منوی نوتیفیکشن ها</label>
                                        </div>

                                    </div>
                                </div>
                            </div>  
                            <div class="row notifications--section" style="display:none;">
                                    <div class="col-md-4">
                                        <div class="form-group wd-xs-300">
                                            <div class="custom-control custom-checkbox custom-control-inline"
                                                style="margin-left: -1rem;">
                                                <input type="checkbox" id="notifications_add" name="notifications_add"
                                                    class="custom-control-input" value="1">
                                                <label class="custom-control-label" for="notifications_add">افزودن نوتیفیکشن</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group wd-xs-300">
                                            <div class="custom-control custom-checkbox custom-control-inline"
                                                style="margin-left: -1rem;">
                                                <input type="checkbox" id="notifications_send" name="notifications_send"
                                                    class="custom-control-input" value="1">
                                                <label class="custom-control-label" for="notifications_send">ارسال نوتیفیکشن</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                              
                            <hr>
                            <p>مدیریت اپلیکیشن</p>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="appmanage" name="appmanage"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="appmanage">منوی مدیریت اپلیکیشن</label>
                                        </div>

                                    </div>
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="appmenu" name="appmenu"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="appmenu">منوی مدیریت منوی اپلیکیشن</label>
                                        </div>

                                    </div>
                                </div>

                                    <div class="col-md-4">
                                        <div class="form-group wd-xs-300">
                                            <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="appworkerannounc" name="appworkerannounc"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="appworkerannounc">منوی اطلاعیه های اپلیکیشن خدمت رسان</label>
                                        

                                        </div>
                                    </div>
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="appslideshow" name="appslideshow"
                                            class="custom-control-input" value="1">
                                        <label class="custom-control-label" for="appslideshow">منوی اسلایدشو های اپلیکیشن خدمت رسان</label>
                                    

                                    </div>
                                </div>
                                </div>
                            </div>
                            <hr>
                            <p>تنظیمات</p>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group wd-xs-300">
                                        <div class="custom-control custom-checkbox custom-control-inline"
                                            style="margin-left: -1rem;">
                                            <input type="checkbox" id="setting" name="setting"
                                                class="custom-control-input" value="1">
                                            <label class="custom-control-label" for="setting">منوی تنظیمات</label>
                                        </div>

                                    </div>
                                  
                                </div>
                            </div>

                        </section>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
</div>


{{-- modal for edit --}}


<div class="modal fade bd-example-modal-lg-edit" id="exampleModal2" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content edit-modal-content">

        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="container_icon card-body d-flex justify-content-end">
            <div class="delete-edit">
            </div>
            <div>
                <a href="#" class="mx-2 btn--filter" title="فیلتر اطلاعات">
                    <span class="__icon bg-info">
                        <i class="fa fa-search"></i>
                    </span>
                </a>
                <a href="#" data-toggle="modal" data-target=".bd-example-modal-lg" title="افزودن کاربر">
                    <span class="__icon bg-success">
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
                <a href="#" title="تازه سازی" class="mx-2" onclick="location.reload()">
                    <span class="__icon bg-primary">
                        <i class="fa fa-refresh"></i>
                    </span>
                </a>
            </div>
        </div>
    </div>
    {{-- filtering --}}
    <div class="card filtering" style="display:none;">
        <div class="card-body">
            <div class="row ">
                <div class="form-group col-md-6">
                    <label for="recipient-name" class="col-form-label">فیلتر اطلاعات براساس: </label>
                    <select required name="type_send" class="form-control" id="exampleFormControlSelect2">
                        <option value="نام">نام</option>
                        <option value="نام خانوادگی">نام خانوادگی</option>
                        <option value="نام کاربری">نام کاربری</option>
                        <option value="کد ملی">کد ملی</option>
                        <option value="شماره موبایل">شماره موبایل</option>

                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="recipient-name" class="col-form-label">عبارت مورد نظر: </label>
                    <input type="text" class="form-control" id="recipient-name">
                </div>
            </div>
            <div class="row">
                <div class="form-group col-md-6">

                    <button type="submit" class="btn btn-outline-primary">جست و جو</button>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-center">مدیریت نقش ها</h5>
                <hr>
            </div>
            <div style="overflow-x: auto;">
                <table id="example1" class="table table-striped  table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ردیف</th>
                            <th>نام نقش</th>
                            <th>کارگزاری مربوطه</th>
                            <th> مجوز ها</th>
                           
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $key=>$role)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox custom-control-inline"
                                    style="margin-left: -1rem;">
                                    <input data-id=" {{$role->id}} " type="checkbox" id="{{ $key}}"
                                        name="customCheckboxInline1" class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="{{$key}}"></label>
                                </div>
                            </td>
                            <td> {{$key+1}} </td>
                            <td>{{$role->name}}</td>
                            <td>
                                @if ($role->sub_broker !== null)
                                {{Spatie\Permission\Models\Role::where('id',$role->sub_broker)->first()->name}}
                                @endif
                               
                            </td>
                            <td>
                                @foreach (\Spatie\Permission\Models\Role::findByName($role->name)->permissions as
                                $permission)

                                @switch($permission->name)
                                @case('user_transaction')
                                <span> تراکنش</span>
                                @break
                                @case('user_pass')
                                <span>تغییر پسورد کاربر</span>
                                @break
                                @case('insert_user')
                                <span> ثبت کاربر</span>
                                @break
                                @case('user_menu')
                                <span> منو کاربر</span>
                                @break
                                @case('user_list')
                                <span> لیست کاربر</span>
                                @break
                                @case('user_delete')
                                <span> حذف کاربر</span>
                                @break
                                @case('user_edit')
                                <span> ویرایش کاربر</span>
                                @break
                                @case('personal_online_menu')
                                <span> منو خدمت رسان های انلاین</span>
                                @break
                                @case('personal_online_list')
                                <span> لیست خدمت رسان های انلاین</span>
                                @break
                                @case('city_insert')
                                <span> ثبت شهر</span>
                                @break
                                @case('city_delete')
                                <span> حذف شهر</span>
                                @break
                                @case('city_list')
                                <span> لیست شهر</span>
                                @break
                                @case('city_menu')
                                <span> منوی شهر</span>
                                @break
                                @case('city_edit')
                                <span> ویرایش شهر</span>
                                @break
                                @case('customer_menu')
                                <span> منوی مشتری</span>
                                @break
                                @case('customer_list')
                                <span> لیست مشتری</span>
                                @break
                                @case('customer_delete')
                                <span> حذف مشتری</span>
                                @break
                                @case('customer_edit')
                                <span> ویرایش مشتری</span>
                                @break
                                @case('category_menu')
                                <span> منوی دسته بندی</span>
                                @break
                                @case('category_insert')
                                <span> افزودن دسته بندی</span>
                                @break
                                @case('category_delete')
                                <span> حذف دسته بندی</span>
                                @break
                                @case('category_edit')
                                <span> ویرایش دسته بندی</span>
                                @break
                                @case('service_menu')
                                <span> منوی خدمت</span>
                                @break
                                @case('service_insert')
                                <span> افزودن خدمت</span>
                                @break
                                @case('service_delete')
                                <span> حذف خدمت</span>
                                @break
                                @case('service_edit')
                                <span> ویرایش خدمت</span>
                                @break
                                @case('personal_menu')
                                <span> منوی خدمت رسان</span>
                                @break
                                @case('personal_insert')
                                <span> منوی خدمت رسان</span>
                                @break
                                @case('personal_delete')
                                <span> منوی خدمت رسان</span>
                                @break
                                @case('personal_edit')
                                <span> منوی خدمت رسان</span>
                                @break
                                @case('orders_menu')
                                <span> منوی گردش کار</span>
                                @break
                                @case('orders_insert')
                                <span> ثبت سفارش</span>
                                @break
                                
                                @case('orders_delete')
                                <span> حذف سفارش</span>
                                @break
                                @case('orders_refferto')
                                <span> ارجاع سفارش</span>
                                @break
                                @case('orders_transactions')
                                <span> تراکنش های سفارش</span>
                                @break
                                @case('orders_detail')
                                <span> جزئیات سفارش</span>
                                @break
                                @case('user_accounts_personals')
                                <span> حساب های خدمت رسان ها</span>
                                @break
                                @case('user_transactions_personals')
                                <span> تراکنش های خدمت رسان ها</span>
                                @break
                                @case('user_transactions_customers')
                                <span> تراکنش های مشتری ها</span>
                                @break
                                @case('checkout_personals')
                                <span> تسویه حساب خدمت رسان ها</span>
                                @break
                                @case('setting')
                                <span> تنظیمات </span>
                                @break
                                @case('accounting')
                                <span> حسابداری</span>
                                @break
                                @case('stores_menu')
                                <span> منوی فروشگاه</span>
                                @break
                                @case('stores_delete')
                                <span> حذف فروشگاه</span>
                                @break
                                @case('stores_create')
                                <span> افزودن فروشگاه</span>
                                @break
                                @case('stores_edit')
                                <span> ویرایش فروشگاه</span>
                                @break
                                @case('appmanage')
                                <span> مدیریت اپلیکیشن</span>
                                @break
                                @case('appmenu')
                                <span> مدیریت منوی اپلیکیشن</span>
                                @break
                                @case('user_accounts_customers')
                                <span> حساب های مشتری ها</span>
                                @break
                                @case('notifications')
                                <span>نوتیفیکیشن ها</span>
                                @break
                                @case('notifications_add')
                                <span> افزودن نوتیفیکیشن </span>
                                @break
                                @case('notifications_send')
                                <span>ارسال نوتیفیکیشن </span>
                                @break
                                @case('appworkerannounc')
                                <span>اعلانیه های اپ خدمت رسان </span>
                                @break
                                @case('appslideshow')
                                <span>اسلایدشو های اپ خدمت رسان </span>
                                @break
                                @default
                                @endswitch
,,
                                @endforeach

                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('css')

<!-- begin::form wizard -->
<link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/vendors/form-wizard/jquery.steps.css" type="text/css">
<!-- end::form wizard -->
@endsection

@section('js')
\
<!-- begin::form wizard -->
<script src="{{route('BaseUrl')}}/Pannel/assets/vendors/form-wizard/jquery.steps.min.js"></script>
<script src="{{route('BaseUrl')}}/Pannel/assets/js/examples/form-wizard.js"></script>
<!-- end::form wizard -->
<script>
    $(document).ready(function(){
      $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

      $('#sub_broker_status').click(function(){
          if ($(this).is(':checked')) {
              $('.broker-select').slideDown()
          }else{
            $('.broker-select').slideUp()

          }
      })  

      $('#broker_status').click(function(){
         
            $('.broker-select').slideUp()

          
      })  

      $('#create--city').validate({
     
        rules: {
          city_name: {
            required: true,
            // digits: true,
            // minlength: 5,
            maxlength: 20
          }
        },
        messages: {
          city_name: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            maxlength:'نام حداکثر 20 کاراکتر میتواند داشته باشد',
            required: "لطفا نام شهر را وارد نمایید"
          },
        }
      })
        $('.btn--filter').click(function(){
          $('.filtering').toggle(200)
        })

           $('table input[type="checkbox"]').change(function(){
            if( $(this).is(':checked')){
            $(this).parents('tr').css('background-color','#41f5e07d');
            }else{
                $(this).parents('tr').css('background-color','');

            }
            array=[]
            $('table input[type="checkbox"]').each(function(){
                if($(this).is(':checked')){
                  array.push($(this).attr('data-id'))
               }
            if(array.length !== 0){

                if (array.length !== 1) {
                    $('.container_icon').removeClass('justify-content-end')
                    $('.container_icon').addClass('justify-content-between')
                    $('.delete-edit').html(`
                    <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="sweet-multiple mx-2">
            <span class="__icon bg-danger">
                <i class="fa fa-trash"></i>
            </span>
           </a>
                    `)
                }else{

                    $('.container_icon').removeClass('justify-content-end')
                    $('.container_icon').addClass('justify-content-between')
                    $('.delete-edit').html(`
                    <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="sweet-multiple mx-2">
            <span class="__icon bg-danger">
                <i class="fa fa-trash"></i>
            </span>
           </a>

           <a href="#" title="تازه سازی" data-toggle="modal" data-target="#exampleModal2" class="mx-2" >
            <span class="__icon bg-info">
                <i class="fa fa-edit"></i>
            </span>
           </a>
                    `)
                }
            }
            else{
                $('.container_icon').removeClass('justify-content-between')
                $('.container_icon').addClass('justify-content-end')
                $('.delete-edit').html('')
            }
        })
            
    })


                  // edit 

$('.bd-example-modal-lg-edit').on('shown.bs.modal', function (event) {
       id = $('table input[type="checkbox"]:checked').attr('data-id')
       $.ajax({
       type:'post',
       url:'{{route("Roles.Edit.getData")}}',
       cache: false,
       async: true,
       data:{id:id},
       success:function(data){
          $('.edit-modal-content').html(data)
          editform= $('#edit--city')
          var form = $("#example-advanced-form1").show();
    form.validate({
        rules: {
          role_name: {
            required: true,
            // digits: true,
            // minlength: 5,
            // maxlength: 5
          },
          
        
        },
        messages: {
          role_name: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا عنوان را وارد نمایید"
          },
          
        }
      });
    form.steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    labels: {
        cancel: 'انصراف',
        current: 'قدم کنونی:',
        pagination: 'صفحه بندی',
        finish: 'ثبت اطلاعات',
        next: 'بعدی',
        previous: 'قبلی',
        loading: 'در حال بارگذاری ...'
    },
    onStepChanging: function (event, currentIndex, newIndex)
    {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex)
        {
            return true;
        }
        // Forbid next action on "Warning" step if the user is to young
        if (newIndex === 3 && Number($("#age-2").val()) < 18)
        {
            return false;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex)
        {
            // To remove error styles
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        
        return form.valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex)
    {
        // Used to skip the "Warning" step if the user is old enough.
        if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
        {
            form.steps("next");
        }
        // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
        if (currentIndex === 2 && priorIndex === 3)
        {
            form.steps("previous");
        }
    },
    onFinishing: function (event, currentIndex)
    {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex)
    {
        // var file =$('#service_icon')
        // var formData = new FormData($(this)[0]);
        // formData.append('file',$('#service_icon'))
        // console.log(formData)
        form.submit()
        
    }
}).validate({
    errorPlacement: function errorPlacement(error, element) { element.before(error); },
    rules: {
        confirm: {
            equalTo: "#password-2"
        }
    }
});

           }
         })
        })  

    $('.delete').click(function(e){
                e.preventDefault()
                console.log(array)

            // ajax request
                $.ajax({
                type:'post',
                url:'{{route("Role.Delete")}}',
                data:{array:array},
                success:function(data){
                  swal("حذف با موفقیت انجام شد", {
                    icon: "success",
					          button: "تایید"
                       });

                       setTimeout(()=>{
                        location.reload()
                       },2000)
           }
        })
     })

     $('#user_menu').click(function(){
         if ($(this).is(':checked')) {
             $('.user--permisions').slideDown()
         }else{
            $('.user--permisions').slideUp()      
         }
     })
     $('#category_menu').click(function(){
         if ($(this).is(':checked')) {
             $('.category--permisions').slideDown()
         }else{
            $('.category--permisions').slideUp()      
         }
     })
     $('#service_menu').click(function(){
         if ($(this).is(':checked')) {
             $('.service--permisions').slideDown()
         }else{
            $('.service--permisions').slideUp()      
         }
     })
     $('#personal_menu').click(function(){
         if ($(this).is(':checked')) {
             $('.personal--permisions').slideDown()
         }else{
            $('.personal--permisions').slideUp()      
         }
     })

     $('#orders_menu').click(function(){
         if ($(this).is(':checked')) {
             $('.orders--permisions').slideDown()
         }else{
            $('.orders--permisions').slideUp()      
         }
     })
     $('#accounting').click(function(){
         if ($(this).is(':checked')) {
             $('.accounting--section').slideDown()
         }else{
            $('.accounting--section').slideUp()      
         }
     })
     
     $('#notifications').click(function(){
         if ($(this).is(':checked')) {
             $('.notifications--section').slideDown()
         }else{
            $('.notifications--section').slideUp()      
         }
     })
     

     
     
})
</script>
@endsection