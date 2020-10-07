@extends('Layouts.Pannel.Template')

@section('content')
<div class="modal fade" id="showProfile" tabindex="-1" role="dialog" aria-labelledby="showProfileLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <img src="" alt="" class="w-100 img-fluid">
            </div>
            
        </div>
    </div>
</div>

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
                    <form id="example-advanced-form" method="post" action="{{route('Service.technician.Submit')}}"
                        enctype="multipart/form-data">
                        @csrf
                        <h3>مشخصات فردی</h3>
                        <section>
                            <div class="row">
                                <div class="col-md-12"
                                    style="display: flex;align-items: center;justify-content: center;">
                                    <div class="profile-img">
                                        <div class="chose-img">
                                            <input type="file" class="btn-chose-img" name="personal_profile"
                                                title="نوع فایل میتواند png , jpg  باشد">
                                        </div>
                                        <img style="border-radius: 50%;object-fit: contain; background: #fff; max-width: 100%; height: 100%; width: 100%;"
                                            src="{{route('BaseUrl')}}/Pannel/img/temp_logo.jpg" alt="">
                                        <p class="text-chose-img"
                                            style="position: absolute;top: 44%;left: 14%;font-size: 13px;">انتخاب
                                            پروفایل</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>نام </label>
                                    <input type="text" id="firstname" name="firstname" class="form-control"
                                        placeholder="نام">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                              
                                <div class="form-group col-md-6">
                                    <label>نام خانوادگی</label>
                                    <input type="text" id="lastname" name="lastname" class="form-control"
                                        placeholder="نام خانوادگی">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>


                            <div class="row">

                                <div class="form-group col-md-6">
                                    <label>تاریخ تولد </label>
                                        <input type="text" class="form-control ltr"
                                        name="birth_year"  id="birth_year" 
                                        data-inputmask="'mask': '9999/99/99'" data-mask>    
                                <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->


                                <div class="form-group col-md-6">
                                    <label>کد ملی </label>
                                    <input type="number" onblur="checknationalcode(this.value)" name="national_num"
                                        id="user_national_num" class="form-control" placeholder="">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">وضعیت تاهل: </label>
                                    <select name="marriage_status" class="form-control" id="exampleFormControlSelect2">
                                        <option value="مجرد">مجرد</option>
                                        <option value="متاهل">متاهل</option>

                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">اخرین مدرک تحصیلی </label>
                                    <select name="education_status" class="form-control" id="exampleFormControlSelect2">
                                        <option value="سیکل">سیکل</option>
                                        <option value="دیپلم">دیپلم</option>
                                        <option value="فوق دیپلم">فوق دیپلم</option>
                                        <option value="لیسانس">لیسانس</option>
                                    </select>
                                </div>
                            </div>
                        </section>
                        <h3>اطلاعات تماس</h3>
                        <section>
                            <div class="row">
                                <div class="form-group col-md-6" style="padding-top: 11px;">
                                    <label class="form-control-label"> <span class="text-danger">*</span> تلفن همراه
                                    </label>
                                    <input class="form-control text-right" id="p_mobile" name="mobile" placeholder=""
                                    onblur="validateMobile(event,this.value)"
                                    type="text" dir="ltr">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">نام شهر: </label>
                                    <select name="city" class="form-control" id="exampleFormControlSelect2">
                                        <option value="مشهد">مشهد</option>
                                        <option value="نیشابور">نیشابور</option>
                                        <option value="فریمان">فریمان</option>
                                        <option value="سبزوار">سبزوار</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> کد پستی: </label>
                                    <input id="postal_code" class="form-control text-right" type="num"
                                        name="postal_code" placeholder="0" type="text" dir="ltr">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="form-control-label"> نشانی دقیق منزل: </label>
                                    <textarea id="address" class="form-control text-right" type="text" name="address"
                                        dir="rtl">
                            </textarea>
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> تلفن منزل: </label>
                                    <input id="tel_home" class="form-control text-right" type="num" name="tel_home"
                                        placeholder="0" type="text" dir="ltr">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> تلفن محل کار: </label>
                                    <input id="tel_work" class="form-control text-right" type="num" name="tel_work"
                                        placeholder="0" type="text" dir="ltr">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>
                        </section>
                        <h3>مدارک اولیه: </h3>
                        <section>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> تصویر دو صفحه اول شناسنامه: </label>
                                    <input id="first_page_certificate" class="form-control text-right" type="file"
                                        name="first_page_certificate" placeholder="0" type="text" dir="rtl">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> تصویر دو صفحه دوم شناسنامه: </label>
                                    <input id="first_page_certificate" class="form-control text-right" type="file"
                                        name="second_page_certificate" placeholder="0" type="text" dir="rtl">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> تصویر کارت پایان خدمت: </label>
                                    <input id="Card_Service" class="form-control text-right" type="file"
                                        name="card_Service" placeholder="0" type="text" dir="rtl">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> تصویر برگه عدم سوء پیشینه: </label>
                                    <input id="antecedent_report_card" class="form-control text-right" type="file"
                                        name="antecedent_report_card" placeholder="0" type="text" dir="rtl">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> تصویر روی کارت ملی: </label>
                                    <input id="national_card_front_pic" class="form-control text-right" type="file"
                                        name="national_card_front_pic" placeholder="0" type="text" dir="rtl">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> تصویر پشت کارت ملی: </label>
                                    <input id="national_card_back_pic" class="form-control text-right" type="file"
                                        name="national_card_back_pic" placeholder="0" type="text" dir="rtl">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>
                        </section>
                        <h3>مشخصات حرفه ای: </h3>
                        <section>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label class="form-control-label"> درباره تخصص: </label>
                                    <textarea  style="text-align:right;" id="about_specialization" class="form-control text-right" type="text"
                                        name="about_specialization" dir="rtl">
                        </textarea>
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">تعداد ماه سابقه کار: </label>
                                    <input id="work_experience_month_num" class="form-control text-right" type="number"
                                        name="work_experience_month_num" type="number" dir="rtl">

                                </div>

                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">تعداد سال سابقه کار: </label>
                                    <input id="work_experience_year_num" class="form-control text-right" type="number"
                                        name="work_experience_year_num" type="number" dir="rtl">

                                </div>
                            </div>
                        </section>
                        <h3>تخصص: </h3>
                        <section>
                            @if (auth()->user()->hasRole('admin_panel'))
                            @foreach (\App\Models\Services\Service::all() as $key=>$service)
                            <div class="row">

                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-checkbox custom-control-inline"
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="service_{{$key}}_{{$key}}"
                                            name="service[service_{{$key+1}}][1]" class="custom-control-input"
                                            value="{{$service->id}}">
                                        <label class="custom-control-label"
                                            for="service_{{$key}}_{{$key}}">{{$service->service_title}}</label>
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-checkbox custom-control-inline"
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="service_{{$key}}_{{$key+1}}"
                                            name="service[service_{{$key+1}}][2]" class="custom-control-input"
                                            value="1">
                                        <label class="custom-control-label" for="service_{{$key}}_{{$key+1}}">خدمت رسان
                                            ارشد</label>
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-checkbox custom-control-inline"
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="service_{{$key}}_{{$key+2}}"
                                            name="service[service_{{$key+1}}][3]" class="custom-control-input"
                                            value="1">
                                        <label class="custom-control-label" for="service_{{$key}}_{{$key+2}}">مورد تایید
                                            است</label>
                                    </div>
                                </div><!-- form-group -->
                            </div>
                            @endforeach
                            @else
                            @if (auth()->user()->roles->first()->broker !== null)
                            @php
                            $services = auth()->user()->services;
                            @endphp
                            @foreach ($services as $key=>$service)
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-checkbox custom-control-inline"
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="service_{{$key}}_{{$key}}"
                                            name="service[service_{{$key+1}}][1]" class="custom-control-input"
                                            value="{{$service->id}}">
                                        <label class="custom-control-label"
                                            for="service_{{$key}}_{{$key}}">{{$service->service_title}}</label>
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-checkbox custom-control-inline"
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="service_{{$key}}_{{$key+1}}"
                                            name="service[service_{{$key+1}}][2]" class="custom-control-input"
                                            value="1">
                                        <label class="custom-control-label" for="service_{{$key}}_{{$key+1}}">خدمت رسان
                                            ارشد</label>
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-checkbox custom-control-inline"
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="service_{{$key}}_{{$key+2}}"
                                            name="service[service_{{$key+1}}][3]" class="custom-control-input"
                                            value="1">
                                        <label class="custom-control-label" for="service_{{$key}}_{{$key+2}}">مورد تایید
                                            است</label>
                                    </div>
                                </div><!-- form-group -->
                            </div>
                            @endforeach
                            @endif
                            @if (auth()->user()->roles->first()->sub_broker !== null)
                            @php
                            $role_id = auth()->user()->roles->first()->sub_broker;

                            $user = User::whereHas('roles', function ($q) use ($role_id) {
                            $q->where('id',$role_id);
                            })->get();
                            $services = $user->services;
                            @endphp
                            @foreach ($services as $key=>$service)
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-checkbox custom-control-inline"
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="service_{{$key}}_{{$key}}"
                                            name="service[service_{{$key+1}}][1]" class="custom-control-input"
                                            value="{{$service->id}}">
                                        <label class="custom-control-label"
                                            for="service_{{$key}}_{{$key}}">{{$service->service_title}}</label>
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-checkbox custom-control-inline"
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="service_{{$key}}_{{$key+1}}"
                                            name="service[service_{{$key+1}}][2]" class="custom-control-input"
                                            value="1">
                                        <label class="custom-control-label" for="service_{{$key}}_{{$key+1}}">خدمت رسان
                                            ارشد</label>
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-4">
                                    <div class="custom-control custom-checkbox custom-control-inline"
                                        style="margin-left: -1rem;">
                                        <input type="checkbox" id="service_{{$key}}_{{$key+2}}"
                                            name="service[service_{{$key+1}}][3]" class="custom-control-input"
                                            value="1">
                                        <label class="custom-control-label" for="service_{{$key}}_{{$key+2}}">مورد تایید
                                            است</label>
                                    </div>
                                </div><!-- form-group -->
                            </div>
                            @endforeach
                            @endif
                            @endif
                        </section>
                        <h3>مدارک فنی: </h3>
                        <section>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> بارگذاری: </label>
                                    <input id="technical_credential" class="form-control text-right" type="file"
                                        name="technical_credential" type="text" dir="rtl">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>
                        </section>
                        <h3>مدارک تحصیلی: </h3>
                        <section>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label class="form-control-label"> بارگذاری: </label>
                                    <input id="expert_credential" class="form-control text-right" type="file"
                                        name="expert_credential" type="text" dir="rtl">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
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

<div class="modal fade bd-example-modal-lg-edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content edit-modal-content">


        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card">
        <div class="container_icon card-body d-flex justify-content-end">
           
            <div class="delete-edit" style="display:none;"> 
                @if (auth()->user()->hasRole('admin_panel'))
                <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="order-delete   m-2">
                  <span class="__icon bg-danger">
                      <i class="fa fa-trash"></i>
                  </span>
                 </a>
                @endif
                @if (auth()->user()->can('personal_edit'))
                    
           <a href="#" title="تازه سازی" data-toggle="modal" data-target=".bd-example-modal-lg-edit" class="mx-2" >
            <span class="edit-personal __icon bg-info">
                <i class="fa fa-edit"></i>
            </span>
           </a>
                @endif
            </div>
            <div>
                <a href="#" class="mx-2 btn--filter" title="فیلتر اطلاعات">
                    <span class="__icon bg-info">
                        <i class="fa fa-search"></i>
                    </span>
                </a>
                @if (auth()->user()->can('personal_insert'))
                <a href="#" data-toggle="modal" data-target=".bd-example-modal-lg" title="افزودن کاربر">
                    <span class="__icon bg-success">
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
                @endif
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
            <form action=" {{route('Personals.FilterData')}} " method="post">
                @csrf
                <div class="row ">

                    <div class="form-group col-md-6">
                        <label for="recipient-name" class="col-form-label">فیلتر اطلاعات براساس: </label>
                        <select required name="type_send" class="form-control" id="personal-filter">

                            <option value="نام">نام</option>
                            <option value="نام خانوادگی">نام خانوادگی</option>
                            <option value="وضعیت">وضعیت</option>
                            <option value="نام کاربری">نام کاربری</option>
                            <option value="کد ملی">کد ملی</option>
                            <option value="شماره موبایل">شماره موبایل</option>

                        </select>
                    </div>
                    <div class="word_field form-group col-md-6" style="display:block;">
                        <label for="recipient-name" class="col-form-label">عبارت مورد نظر: </label>
                        <input type="text" name="word" class="form-control" id="word">
                    </div>
                    <div class="status_options form-group col-md-6" style="display:none;">
                        <label for="recipient-name" class="col-form-label">وضعیت: </label>
                        <select required name="word" class="form-control" id="word">
                            <option value="فعال">فعال</option>
                            <option value="غیر فعال">غیر فعال</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">

                        <button type="submit" class="btn btn-outline-primary">جست و جو</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-center">مدیریت خدمت رسان</h5>
                <hr>
            </div>
            <div style="overflow-x: auto;">
                <table id="example1" class="table table-striped  table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ردیف</th>
                            <th>
                                <a href="#" data-id="name" class="name_field text-white">
                                    نام
                                    <i class="fa fa-angle-down"></i>
                                </a>
                            </th>
                            <th>
                                <a href="#" data-id="family" class="name_field text-white">
                                    نام خانوادگی
                                    <i class="fa fa-angle-down"></i>
                                </a>
                            </th>
                            <th>شماره همراه</th>
                            <th>فعال</th>
                            <th>
                                <a href="#" data-id="gender" class="name_field text-white">
                                    جنسیت
                                    <i class="fa fa-angle-down"></i>
                                </a>

                            </th>
                            <th>وضعیت تاهل</th>
                            <th>اخرین مدرک تحصیلی</th>
                            <th>تلفن منزل</th>
                            <th>تلفن محل کار</th>
                            <th>تاریخ عضویت</th>
                            <th>تصویر پروفایل</th>

                        </tr>
                    </thead>
                    <tbody class="tbody">
                        {!! $personals !!}
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
<!-- begin::form wizard -->
<script src="{{route('BaseUrl')}}/Pannel/assets/vendors/form-wizard/jquery.steps.min.js"></script>
<script src="{{route('BaseUrl')}}/Pannel/assets/js/examples/form-wizard.js"></script>
<!-- end::form wizard -->
<script src="{{route('BaseUrl')}}/Pannel/assets/input-mask/jquery.inputmask.js"></script>
<script src="{{route('BaseUrl')}}/Pannel/assets/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="{{route('BaseUrl')}}/Pannel/assets/input-mask/jquery.inputmask.extensions.js"></script>
<script>
 

    $(function () {
        $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


 //Datemask dd/mm/yyyy
 $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()
       
        $('.btn--filter').click(function(){
          $('.filtering').toggle(200)
        })

           $('.checkpersonal input[type="checkbox"]').change(function(){
            array=[]
            $('.checkpersonal input[type="checkbox"]').each(function(){

                if($(this).is(':checked')){
                  array.push($(this).attr('data-id'))
               }

            if(array.length !== 0){
                $('.delete-edit').show()
                if (array.length !== 1) {
                    $('.container_icon').removeClass('justify-content-end')
                    $('.container_icon').addClass('justify-content-between')
                    $('.edit-personal').hide()
                }else{

                    $('.container_icon').removeClass('justify-content-end')
                    $('.container_icon').addClass('justify-content-between')
                    $('.edit-personal').show()
                    
                   
                }
            }
            else{
                $('.container_icon').removeClass('justify-content-between')
                $('.container_icon').addClass('justify-content-end')
                $('.delete-edit').hide()
            }
        })
            
    })
$(document).on('shown.bs.modal','.bd-example-modal-lg',function(){
    $('.date-picker-shamsi-list').datepicker({
		dateFormat: "yy/mm/dd",
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		showButtonPanel: true
	});
})
    
// Edit
$('.bd-example-modal-lg-edit').on('shown.bs.modal', function (event) {
   personal_id =  $('table input[name="checkbox"]:checked').attr('data-id')
    $.ajax({
    type:'post',
    url:'{{route("Personal.Edit.getData")}}',
    cache: false,
    async: true,
    data:{personal_id:personal_id},
    success:function(data){
       $('.edit-modal-content').html(data)
       $('.js-example-basic-single').select2({
         placeholder: 'انتخاب کنید'
        });
        editform= $('#edit--form')
        var form = $("#example-advanced-form1").show();
    form.validate({
        rules: {
          service_percentage: {
            required: true,
            range:[0,100]
          },
          firstname: {
            required:true
        }, 
        lastname: {
            required:true
        },
        national_num:{
            required:true,
            
            maxlength:10
        },
        work_experience_month_num: {
            required:true,
            range:[0,12]
        }
        },
        messages: {
          title: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا عنوان را وارد نمایید"
          },
          service_category: {
            required:'سرگروه خدمت را انتخاب نمایید'
        },
        service_percentage: {
            required:'درصد پورسانت را وارد نمایید',
            range:'پورسانت حداکثر 100% میباشد'
        },
        firstname: {
            required:'لطفا نام خود را وارد نمایید'
        }, 
        lastname: {
            required:'لطفا نام خانوادگی خود را وارد نمایید'
        },
        national_num:{
            required: ' کد ملی خود را وارد نمایید',
            maxlength:'کد ملی بایستی حداکثر 10 رقم باشد'
        },
        work_experience_month_num: {
            required:'فیلد اجباری است',
            range:'ماه باید در بازه 0 تا12 باشد',
           
        }
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
 //Datemask dd/mm/yyyy
 $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

         }
    
      });
    
    }); 



    $('#showProfile').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.find('img').attr('src') // Extract info from data-* attributes
  var modal = $(this)
  modal.find('img').attr('src',recipient)
})
   



// change status
$(document).on('click','input[id^="status_"]',function(){
  let  id =  $(this).data('id')
  if($(this).is(':checked')){
      var value = 1
  }else{
      var value = 0
  }
  $.ajax({
type:'post',
url:'{{route("Personal.ChangeStatus")}}',
data:{id:id,value:value},
success:function(data){
    swal("", "تغییر وضعیت خدمت رسان با موفقیت انجام شد", "success", {
			button: "باشه"
		});
}
})
})


// Delete
   

    $('.delete').click(function(e){
                e.preventDefault()
                console.log(array)

                // ajax request
 $.ajax({

                type:'post',
                url:'{{route("Personal.Delete")}}',
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
//check national num 
$(document).on('blur','#national_num',function(){
    var personal_national_code = $(this).val();
    var thiss = $(this);
    var personal_id = $(this).attr('data-id');
$.ajax({
type:'post',
url:'{{route("Personal.CheckNationalNum")}}',
data:{personal_national_code:personal_national_code,
    personal_id:personal_id
},
success:function(data){
    if (data.error) {
        swal("خطا!", data.error, "error", {
			button: "باشه"
        });
        thiss.val('')
    }
}
})

})

$('#personal-filter').click(function(){
    if ($(this).val() == 'وضعیت') {
        $('.word_field').hide()
        $('.status_options').show()
    }else{
        $('.status_options').hide()
        $('.word_field').show()
    }
})


// OrderBy Personals

var namefield = $('.name_field')
namefield.click(function(e){
  e.preventDefault();
 var data = $(this).attr('data-id');
 $.ajax({
type:'post',
url:'{{route("Personal.OrderBy.Table")}}',
data:{data:data},
success:function(data){ 
   $('.tbody').html(data)
   }
 })
})
$('#p_mobile').blur(function(){
var data = $(this).val();
var thiss = $(this);
 $.ajax({
type:'post',
url:'{{route("Personal.CheckMobile")}}',
data:{data:data},
success:function(data){ 
    swal("خطا!", data.error, "error", {
			button: "باشه"
        });
        thiss.val('')
    }
  })
 })
})
</script>
@endsection