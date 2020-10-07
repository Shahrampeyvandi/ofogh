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
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">مدیریت سفارشات</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="order--form" method="post" action=" {{route('Order.Submit')}} " enctype="multipart/form-data">
        @csrf
        <div class="modal-body">

          <div class="row">
            <div class="form-group col-md-6">
              <label for="user_mobile" class="col-form-label"><span class="text-danger">*</span> شماره همراه:</label>
              <input type="number" class="form-control" name="user_mobile" onblur="validateMobile(event,this.value)" id="user_mobile">
            </div>
            <div class="form-group col-md-6">
              <label for="user_name" class="col-form-label"><span class="text-danger">*</span> نام: </label>
              <input type="text" class="form-control" name="user_name" id="user_name">
            </div>

          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="user_family" class="col-form-label"><span class="text-danger">*</span> نام خانوادگی:</label>
              <input type="text" class="form-control" name="user_family" id="user_family">
            </div>
            <div class="form-group col-md-6">
              <label for="user_desc" class="col-form-label"><span class="text-danger">*</span>
                توضیحات:</label>
              <textarea type="text" class="form-control" name="user_desc" id="user_desc">

              </textarea>
            </div>
          </div>

          <div class="row">
            <div class="form-group col-md-6">
              <label for="recipient-name" class="col-form-label">شهر: </label>
              <select required name="user_city" class="form-control" id="exampleFormControlSelect2">
                @foreach (\App\Models\City\City::all() as $item)
                <option value="{{$item->id}}">{{$item->city_name}}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group col-md-6">
              <label for="user_address" class="col-form-label"><span class="text-danger">*</span> ادرس دقیق:
                <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
                  <input data-id="" data-order="" type="checkbox" id="new-address" name="customCheckboxInline1"
                    class="custom-control-input" value="1">
                  <label class="mx-3 custom-control-label" for="new-address">ادرس جدید</label>
                </div>
              </label>
              <select name="user_address" class="form-control" id="address-select">
                <option value="">یاز کردن فهرست انتخاب</option>
              </select>
              <textarea type="text" class="form-control mt-2" name="new_address" id="add-address" style="display:none;">
              </textarea>
            </div>
          </div>
          <div class="wrapper-content">
            <div class="row">
              <div class="form-group col-md-6">
                <label for="time_one" class="col-form-label">انتخاب ساعت اول درخواستی:</label>
                <select required name="time_one[]" class="form-control" id="exampleFormControlSelect2">
                  <option value="8 تا 12">8 تا 12</option>
                  <option value="12 تا 16">12 تا 16</option>
                  <option value="16 تا 20">16 تا 20</option>
                  <option value="20 تا 24">20 تا 24</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="date_one" class="col-form-label">انتخاب تاریخ: </label>
                <input type="text" id="date_one" name="date_one[]" autocomplete="off"
                  class="form-control text-right date-picker-shamsi" dir="ltr">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-md-6">
                <label for="time_two" class="col-form-label">انتخاب ساعت دوم درخواستی:</label>
                <select name="time_two[]" class="form-control" id="exampleFormControlSelect2">
                  <option value="">انتخاب کنید</option>
                  <option value="8 تا 12">8 تا 12</option>
                  <option value="12 تا 16">12 تا 16</option>
                  <option value="16 تا 20">16 تا 20</option>
                  <option value="20 تا 24">20 تا 24</option>
                </select>
              </div>
              <div class="form-group col-md-6">
                <label for="date_two" class="col-form-label">انتخاب تاریخ: </label>
                <input type="text" id="date_two" name="date_two[]" autocomplete="off"
                  class="form-control text-right date-picker-shamsi" dir="ltr">
              </div>
            </div>

            <div class="row ii">
              <div class="form-group col-md-12">
                <label for="recipient-name" class="col-form-label">دسته:</label>
                <select @if ($count> 1)
                  size=" {{$count}} " @else size="3"
                  @endif class="form-control category-select" name="category[]" id="category">
                  {!! $list !!}
                </select>

                <div class="valid-feedback">
                  صحیح است!
                </div>
              </div><!-- form-group -->
            </div>
            <div class="row">
              <div class="form-group col-md-12">
                <label for="service_name" class="col-form-label">نام خدمت:</label>
                <select class="form-control service_name" name="service_name[]" id="service_name" dir="rtl">
                  <option value="">ابتدا دسته بندی را انتخاب نمایید</option>
                </select>
              </div>
            </div>
          </div>

          <hr>
          <div class="cloned ">

          </div>
          <div class="clone-bottom">

            <a href="#">
              سفارش جدید
              <i class="fa fa-plus-circle"></i>
            </a>
          </div>
        </div>



        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
          <button type="submit" class="btn btn-primary">ذخیره</button>
        </div>
      </form>
    </div>
  </div>
</div>


{{-- modal for edit --}}

<div class="modal fade bd-example-modal-lg-edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content edit-modal-content">
      <form action="{{route('Order.ChoicePersonal')}}" method="post">
        @csrf
        <div class="card">
          <div class="card-body">
            <div class="card-title">
              <h5 class="text-center">خدمت رسان ها</h5>
              <hr>
            </div>
            <div style="overflow-x: auto;">
              <table id="example1" class=" table-striped  table-bordered" style="width:100%;">
                <thead>
                  <tr>
                    <th></th>
                    <th>ردیف</th>
                    <th>
                      نام
                    </th>
                    <th>
                      نام خانوادگی
                    </th>
                    <th> شماره همراه</th>

                    <th> فعال</th>

                  </tr>
                </thead>
                <tbody class="tbody--edit">
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer text-left">
            <button type="submit" class="btn btn-primary">ارجاع</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg-detail" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content edit-modal-content">

      <div class="card">
        <div class="card-title">
          <h5 class="text-center">جزییات سفارش</h5>

        </div>
        <div class="card-body order--detail">



        </div>

      </div>

    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-lg-chosen" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content edit-modal-content">
      <form action="{{route('Order.Choise.ChosenPersonal')}}" method="post">

        <div class="card">
          <div class="card-body">
            <div class="card-title">
              <h5 class="text-center">خدمت رسان ها</h5>
              <hr>
            </div>
            <div style="overflow-x: auto;">
              <table id="" class=" table-striped  table-bordered" style="width:100%;">
                <thead>
                  <tr>
                    <th></th>
                    <th>ردیف</th>
                    <th>
                      نام
                    </th>
                    <th>
                      نام خانوادگی
                    </th>
                    <th> شماره همراه</th>

                    <th> فعال</th>

                  </tr>
                </thead>
                <tbody class="tbody--chosen">
                </tbody>
              </table>
            </div>
          </div>
          <div class="card-footer text-left">
            <button type="submit" class="btn btn-primary">ارجاع</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>


<div class="container-fluid">
  <div class="card">
    <div class="container_icon card-body ">
      <div class="row">
        <div class="delete-edit col-md-9" style="display:none;">
          @if (auth()->user()->can('orders_delete'))
          <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="order-delete   m-2">
            <span class="__icon bg-danger">
              <i class="fa fa-trash"></i>
            </span>
          </a>
          @endif
          @if (auth()->user()->can('orders_detail'))
          <a href="#" title="" data-toggle="modal" data-target=".bd-example-modal-lg-detail" class="order-detail  m-2">
            <span class=" bg-secondary" style="padding: 13px 10px 4px 10px;
      border-radius: 4px;
      box-shadow: 0 1px 6px 0 #6464a9;">
              جزییات سفارش
            </span>
          </a>
          @endif
          @if (auth()->user()->can('orders_refferto'))
          <a href="#" title="" data-toggle="modal" data-target=".bd-example-modal-lg-edit" class="order-refferto  m-2">
            <span class=" bg-info" style="padding: 13px 10px 4px 10px;
      border-radius: 4px;
      box-shadow: 0 1px 6px 0 #6464a9;">
              ارجاع دستی به خدمت رسان
            </span>
          </a>

          <a href="#" title="" data-toggle="modal" data-target=".bd-example-modal-lg-chosen"
            class="order-refferto  m-2">
            <span class=" bg-success" style="padding: 13px 10px 4px 10px;
      border-radius: 4px;
      box-shadow: 0 1px 6px 0 #6464a9;">
              ارجاع دستی به خدمت رسان های منتخب
            </span>
          </a>
          @endif
          @if (auth()->user()->can('orders_transactions'))
          <a href="#" title="" data-toggle="modal" data-target=".bd-example-modal-lg-transactions"
            class="order-transactions  m-2">
            <span class=" bg-primary" style="padding: 13px 10px 4px 10px;
      border-radius: 4px;
      box-shadow: 0 1px 6px 0 #6464a9;">
              تراکنش های سفارش
            </span>
          </a>
          @endif
          <a href="#" title="" data-toggle="modal" data-target=".bd-example-modal-lg-edit" class="  m-2">
            <span class=" bg-dark" style="padding: 13px 10px 4px 10px;
      border-radius: 4px;
      box-shadow: 0 1px 6px 0 #6464a9;">
              مشاهده لیست پیشنهادات خدمت رسان ها
            </span>
          </a>
        </div>
        <div class="col-md-3">
          <div class="insert-filter text-right">
            <a href="#" class="mx-2 btn--filter" title="فیلتر اطلاعات">
              <span class="__icon bg-info">
                <i class="fa fa-search"></i>
              </span>
            </a>
            @if (auth()->user()->can('orders_insert'))
            <a href="#" data-toggle="modal" data-target=".bd-example-modal-lg" title="افزودن کاربر">
              <span class="__icon bg-success">
                <i class="fa fa-plus"></i>
              </span>
            </a>
            @endif
            <a href="{{route('Pannel.Customers.Orders')}}" title="تازه سازی" class="mx-2">
              <span class="__icon bg-primary">
                <i class="fa fa-refresh"></i>
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- filtering --}}
  <div class="card filtering" style="display:none;">
    <form action=" {{route('Service.FilterData')}} " method="post">
      @csrf
      <div class="card-body">
        <div class="row ">
          <div class="form-group col-md-6">
            <label for="recipient-name" class="col-form-label">فیلتر اطلاعات براساس: </label>
            <select name="type_send" class="form-control" id="filtering">
              <option value="عنوان">عنوان</option>
              <option value="نوع">نوع</option>
              <option value="نقش">نقش</option>
              <option value="نوع قیمت">نوع قیمت</option>
              <option value="نوع ارجاع">نوع ارجاع</option>

            </select>
          </div>
          <div class="form-group col-md-6 search-box">
            <label for="recipient-name" class="col-form-label">عبارت مورد نظر: </label>
            <input type="text" class="form-control" name="word" id="word">
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-6">

            <button type="submit" class="btn btn-outline-primary">جست و جو</button>
          </div>
        </div>
      </div>
    </form>
  </div>


  <div class="card">
    <div class="card-body">
      <div class="card-title">
        <h5 class="text-center">مدیریت سفارشات</h5>
        <hr>
      </div>
      <div style="overflow-x: auto;">
        <table class="table table-striped  table-bordered example1">
          <thead>
            <tr>
              <th></th>
              <th>ردیف</th>
              <th>
                <a href="#" data-id="title" class=" text-white">
                  کد سفارس
                  <i class="fa fa-angle-down"></i>
                </a>
              </th>
              <th>
                <a href="#" data-id="broker_name" class=" text-white">
                  نوع
                  <i class="fa fa-angle-down"></i>
                </a>
              </th>
              <th> توضیحات</th>
              <th>نام مشتری</th>
              <th> نام خانوادگی مشتری</th>
              <th>نام کاربری مشتری</th>
              <th>
                <a href="#" data-id="persent" class=" text-white">
                  نام خدمت
                  <i class="fa fa-angle-down"></i>
                </a>
              </th>
              <th>نام کارگزاری</th>

              <th>زمان اول درخواستی</th>


              <th>زمان دوم درخواستی</th>
              <th> تاریخ ثبت</th>
              <th> ارجاع شده به</th>

            </tr>
          </thead>
          <tbody class="tbody">
            @foreach ($orders as $key=> $order)
            <tr>
              <td>
                <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
                  <input data-id="{{$order->relatedService->id}}" data-order="{{$order->id}}" type="checkbox"
                    id="{{ $key}}" name="customCheckboxInline1" class="custom-control-input" value="1">
                  <label class="custom-control-label" for="{{$key}}"></label>
                </div>
              </td>
              <td> {{$key+1}} </td>
              <td>{{$order->order_unique_code}}</td>
              <td>{{$order->order_type}}</td>
              <td>
                @if ($order->order_desc !== null)
                {{$order->order_desc}}
                @else
                --
                @endif

              </td>
              <td>

                {{$order->order_firstname_customer}}

              </td>
              <td>
                {{$order->order_lastname_customer}}
              </td>

              <td>
                {{$order->order_username_customer}}
              </td>
              <td>
                {{$order->relatedService->service_title}}
              </td>
              <td>

                @if ($service_broker =
                \App\Models\Services\Service::where('id',$order->relatedService->id)->first()->user->first() !== null)
                {{\App\Models\Services\Service::where('id',$order->relatedService->id)->first()->user->first()->user_username}}
                @else
                ندارد
                @endif

              </td>
              <td>
                {{\Morilog\Jalali\Jalalian::forge($order->order_date_first)->format('%B %d، %Y') .'ساعت'. $order->order_time_first}}
              </td>
              <td>
                {{\Morilog\Jalali\Jalalian::forge($order->order_date_second)->format('%B %d، %Y') .'ساعت'. $order->order_time_second}}
              </td>
              <td>
                {{\Morilog\Jalali\Jalalian::forge($order->created_at)->format('%B %d، %Y')}}
              </td>
              <td>

                @foreach ($order->personals as $personal)
                {{$personal->personal_firstname .' - '. $personal->personal_lastname}}
                <br>
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

<style>
  select {
    font-family: 'FontAwesome', 'sans-serif';
    font-size: 15px;
    font-weight: 600;
  }

  option {
    font-size: 18px;
  }

  .table {
    width: 115%;
  }

  .table th {
    width: 7%;
  }

  .delete-edit a {
    display: inline-block;
  }

  .delete-edit a span {
    margin: 10px 0;
  }

  .order-detail span {
    display: inline-block;
    margin: 5px 0;
  }
</style>
@endsection

@section('js')

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

    var form = $("#order--form").show();
    form.validate({
        rules: {
         
      
        user_mobile: {
          required:true
        },
        user_name: {
          required:true
        },
        user_family: {
          required:true
        },
        category: {
          required:true
        },
        service_name: {
          required:true
        }
        
        },
        messages: {
         date_one: {
            required:'تاریخ سفارش را انتخاب کنید',
        } ,user_mobile: {
          required:'شماره همراه سفارش دهنده را وارد کنید',
        },
        user_name: {
          required:'نام سفارش دهنده را وارد کنید',
        },
        user_family: {
          required:'نام خانوادگی سفارش دهنده را وارد کنید',
        },
        category: {
          required:'دسته بندی را انتخاب کنید',
        },
        service_name: {
          required:'خدمت را انتخاب کنید',
        }
        }
      });

     $('#price_type').click(function(){
         if($(this).val() == 'رقمی'){
             
             $('#price-wrapper').show(250)
         }else{
            $('#price-wrapper').hide(250)
  
         }
     })


        $('.btn--filter').click(function(){
          $('.filtering').toggle(200)
        })
        
            $(document).on('change','.table input[type="checkbox"]',function(){
                if( $(this).is(':checked')){
                $(this).parents('tr').css('background-color','#41f5e07d');
                }else{
                    $(this).parents('tr').css('background-color','');

                }

             array=[]
            
            $('.table input[type="checkbox"]').each(function(){
                if($(this).is(':checked')){
                    array.push($(this).attr('data-order'))

               }
            if(array.length !== 0){

                if (array.length !== 1) {
                    $('.container_icon').removeClass('justify-content-end')
                    $('.container_icon').addClass('justify-content-between')
                    $('.delete-edit').show()
                    $('.order-detail').hide()
                    $('.order-transactions').hide()
                    $('.order-refferto').hide()
                    $('.insert-filter').addClass('text-left')
                    $('.insert-filter').removeClass('text-right')
                    
                }else{
                    $('.container_icon').removeClass('justify-content-end')
                    $('.container_icon').addClass('justify-content-between')
                    $('.delete-edit').show()
                    $('.order-detail').show()
                    $('.order-transactions').show()
                    $('.order-refferto').show()
                    $('.insert-filter').addClass('text-left')
                    $('.insert-filter').removeClass('text-right')
                }
            }
            else{
                $('.container_icon').removeClass('justify-content-between')
                $('.container_icon').addClass('justify-content-end')
                $('.delete-edit').hide()
                $('.insert-filter').removeClass('text-left')
                $('.insert-filter').addClass('text-right')
            }
        })
            
    })

$('.bd-example-modal-lg-edit').on('shown.bs.modal', function (event) {
   service_id =  $('table input[type="checkbox"]:checked').attr('data-id')
   order_id = $('table input[type="checkbox"]:checked').attr('data-order')
    $.ajax({
    type:'post',
    url:'{{route("Order.Edit.getPersonals")}}',
    cache: false,
    async: true,
    data:{service_id:service_id,order_id:order_id},
    success:function(data){
       $('.tbody--edit').html(data)
     
         }
    
      });
    
    }); 

    $('.bd-example-modal-lg-detail').on('shown.bs.modal', function (event) {
   service_id =  $('table input[type="checkbox"]:checked').attr('data-id')
   order_id = $('table input[type="checkbox"]:checked').attr('data-order')
    $.ajax({
    type:'post',
    url:'{{route("Order.getDetailOrder")}}',
    cache: false,
    async: true,
    data:{service_id:service_id,order_id:order_id},
    success:function(data){
       $('.order--detail').html(data)
     
         }
    
      });
    
    }); 

    $('.bd-example-modal-lg-chosen').on('shown.bs.modal', function (event) {
   service_id =  $('.tbody input[type="checkbox"]:checked').attr('data-id')
   order_id = $('.tbody input[type="checkbox"]:checked').attr('data-order')
    $.ajax({
    type:'post',
    url:'{{route("Order.getChosenPersonal")}}',
    cache: false,
    async: true,
    data:{service_id:service_id,order_id:order_id},
    success:function(data){
     
       $('.tbody--chosen').html(data)
     
         }
    
      });
    
    }); 


    // filtering
    $('#filtering').change(function(){
        if ($(this).val() == 'نوع قیمت') {
            $('.search-box').html(
                ` <div class="form-group wd-xs-300">
                                <label for="recipient-name" class="col-form-label">نوع خدمت:</label>
                                <select required name="word"   class="form-control" id="exampleFormControlSelect2">
                                    <option value="توافقی">توافقی</option>
                                    <option value="طبق لیست">طبق لیست</option>  
                                    <option value="رقمی">رقمی</option>  
                                </select>
                </div>`
            )
        }

        if ($(this).val() == 'نوع ارجاع') {

              $('.search-box').html(
                `  <div class="form-group wd-xs-300">
                                <label for="recipient-name" class="col-form-label">نوع ارجاع:</label>
                                <select required name="word"   class="form-control" id="exampleFormControlSelect2">
                                    <option value="ارجاع اتوماتیک">ارجاع اتوماتیک</option>
                                    <option value="ارجاع دستی">ارجاع دستی</option>  
                                    <option value="ارجاع منتخب">ارجاع منتخب</option>  
                                    <option value="ارجاع به کمترین فاصله">ارجاع به کمترین فاصله</option>  
                                </select>
                   </div>`
            )
        }
    });

    // Delete
    $('.delete').click(function(e){
                e.preventDefault()
                  $.ajax({

                type:'post',
                url:'{{route("Order.Delete")}}',
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


// OrderBy Services

var namefield = $('.name_field')
namefield.click(function(e){
  e.preventDefault();
 var data = $(this).attr('data-id');
 $.ajax({
type:'post',
url:'{{route("Service.OrderBy.Table")}}',
data:{data:data},
success:function(data){ 
   $('.tbody').html(data)
   }
 })
})

// Validate Icons

$("#service_icon").on("change", function () {
    
    var fileInput = $("#service_icon")[0],
    file = fileInput.files && fileInput.files[0];
  if( file ) {
    var img = new Image();
    img.src = window.URL.createObjectURL( file );
    img.onload = function() {
    var width = img.naturalWidth,
        height = img.naturalHeight;
    window.URL.revokeObjectURL( img.src );
    if(width <= 400 && height <= 400 ) {}else{
      swal("اخطار!", "فایل ایکون حداکثر باید در ابعاد 400X400 باشد", "warning", {
			button: "باشه"
    });
    $("#service_icon").val('')
    }
  }
  }
});

$(document).on('change','.category-select',function(){
 var thiss = $(this)
var data = $(this).val();
$.ajax({
type:'post',
url:'{{route("Order.Category.getService")}}',
data:{data:data},
success:function(data){ 
thiss.parents('.ii').next().find('.service_name').html(data)
$('.date-picker-shamsi').datepicker({
		dateFormat: "yy/mm/dd",
		showOtherMonths: true,
		selectOtherMonths: false
	});
   }
 })
})

$('#user_mobile').blur(function(){
  var data = $(this).val();
$.ajax({
type:'post',
url:'{{route("Order.CheckCustomer")}}',
data:{data:data},
success:function(data){ 
$('#user_name').val(data.customer.customer_firstname)
$('#user_family').val(data.customer.customer_lastname)
$('#address-select').html(data.option_address)
   }
 })
})

$('#new-address').click(function(){
  if($(this).is(':checked')){
    $('#address-select').hide()
  $('#add-address').show()
  }else{
    $('#address-select').show()
    $('#add-address').hide()
  }
})
$(document).on('click','.clone-bottom',function(e){
  e.preventDefault()

 
  let cloned = $(this).siblings('.wrapper-content').clone()

  $('.cloned').append(cloned)
  $('.cloned').append('<hr>')
  cloned.find('.date-picker-shamsi').each(function(){
    $(this).removeClass('hasDatepicker')
  .removeData('datepicker')
  .attr('id', 'dd_date' + Math.random()) //newly added line
  .unbind()
  .datepicker({ dateFormat: "yy/mm/dd",
		showOtherMonths: true,
		selectOtherMonths: false });
  })
  
  

})

})
</script>
@endsection