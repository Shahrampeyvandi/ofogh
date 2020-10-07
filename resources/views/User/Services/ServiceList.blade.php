@extends('Layouts.Pannel.Template')

@section('content')

{{-- modal for delete --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
          <a type="button" class="delete btn btn-danger text-white">حذف!  </a>
        </div>
      </div>
    </div>
  </div>


{{-- modal for create --}}

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">    <div class="modal-dialog modal-lg">
        <div class="modal-content">  
            <div class="modal-body">
                <div id="wizard2">
                <form id="example-advanced-form" method="post" action="{{route('Service.Submit')}}" enctype="multipart/form-data">
                    @csrf
                    <h3>خدمت</h3>
                    <section>  
                            <div class="form-group wd-xs-300">
                                <label>عنوان </label>
                                <input type="text" id="title" name="title" class="form-control" placeholder="نام" >
                                
                            </div><!-- form-group -->
                            
                                <div class="form-group wd-xs-300">
                                    <label for="recipient-name" class="col-form-label">دسته:</label>
                                    <select  @if ($count > 1)
                                        size=" {{$count +1}} "   @elseif($count > 10)  size="10" @else size="2"
                                     @endif    class="form-control" name="service_category" id="service_category">
                                     {!! $list !!}
                                    </select>
                                                      
                                <div class="valid-feedback">
                                    صحیح است!
                                </div>
                            </div><!-- form-group -->
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>درصد پورسانت </label>
                                    <input type="number" name="service_percentage" id="service_percentage" class="form-control" placeholder="">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                                <div class="form-group col-md-6">
                                    <label>قیمت ارسال پیشنهاد (تومان) </label>
                                    <input type="number" name="service_offered_price" id="service_offered_price" class="form-control" placeholder="">
                                    <div class="valid-feedback">
                                        صحیح است!
                                    </div>
                                </div><!-- form-group -->
                            </div>
                            <div class="form-group wd-xs-300">
                                <label>توضیحات </label>
                                <textarea type="text" name="service_desc" class="form-control" placeholder="">
                                </textarea>
                                <div class="valid-feedback">
                                    صحیح است!
                                </div>
                            </div><!-- form-group -->
                            <div class="form-group wd-xs-300">
                                <label>تذکرات </label>
                                <input type="text" name="service_alerts" class="form-control" placeholder="" >
                                <div class="valid-feedback">
                                    صحیح است!
                                </div>
                            </div><!-- form-group -->

                            <p>شهر  </p>
                            <div class="form-group ">
                                @foreach (\App\Models\City\City::all(); as $key=>$item)
                                <div class="custom-control custom-checkbox custom-control-inline">
                                    <input  type="checkbox" class="custom-control-input checkbox__"
                                    value="{{$item->id}}"
                                    name="service_city[]" id="item_{{$key+1}}" >
                                    <label class="custom-control-label" for="item_{{$key+1}}">{{$item->city_name}}</label>
                                </div> 
                                @endforeach
         
                            </div> 
                            <div class="form-group wd-xs-300">
                                <label for="recipient-name" class="col-form-label">نوع ارجاع:</label>
                                <select required name="type_send"   class="form-control" id="exampleFormControlSelect2">
                                    <option value="ارجاع اتوماتیک">ارجاع اتوماتیک</option>
                                    <option value="ارجاع دستی">ارجاع دستی</option>  
                                    <option value="ارجاع منتخب">ارجاع منتخب</option>  
                                    <option value="ارجاع به کمترین فاصله">ارجاع به کمترین فاصله</option>  
                                </select>
                            </div>
                         
                                <div class="form-group wd-xs-300">
                                <label for="recipient-name" class="col-form-label">نام کارگزاری: </label>
                                <select required name="service_role"   class="form-control" id="exampleFormControlSelect2">
                                    {!! $brokers !!}
                                      
                                </select>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>ایکون </label>
                                        <input type="file" id="service_icon" name="service_icon" class="form-control" placeholder="" >
                                        <div class="valid-feedback">
                                            صحیح است!
                                        </div>
                                    </div><!-- form-group -->
                                    <div class="form-group  col-md-6 pt-4">
                                        <span>درخواست به صورت پیامکی به خدمت رسان</span>
                                        <div class="custom-control custom-switch">
                                            <input style="display:inline-block;" value="1" type="checkbox" class="custom-control-input" name="sms_status" id="sms_status" >
                                            <label class="custom-control-label" for="sms_status"></label>
                                        </div>
                                    </div>
                                </div>
                            
                    </section>
                    <h3> قیمت</h3>
                    <section>
                        <div class="form-group wd-xs-300">
                            <label for="recipient-name" class="col-form-label">نوع :</label>
                            <select  name="price_type"   class="form-control" id="price_type">
                                
                                <option selected="" value="توافقی"> توافقی</option>
                                <option value="رقمی"> رقمی</option>
                                <option value="طبق لیست"> طبق لیست</option>
                             </select>
                        </div><!-- form-group -->
                        <div class="form-group wd-xs-300" id="price-wrapper" style="display:none;">
                            <label class="form-control-label"> قیمت (به تومان):</label>
                            <input id="service_price" class="form-control text-right" name="service_price" placeholder="0" type="number"  dir="rtl">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->

                       
                            <div class="form-group wd-xs-300">
                            
                                <label class="form-control-label"> تصویر 1: </label>
                                <input id="" class="form-control text-right" name="pic_1"  type="file"  dir="rtl">
                                <div class="valid-feedback">
                                    صحیح است!
                                </div>
                            </div><!-- form-group -->
                            <div class="form-group wd-xs-300">
                                <label class="form-control-label"> تصویر 2: </label>
                                <input id="" class="form-control text-right" name="pic_2"  type="file"  dir="rtl">
                                <div class="valid-feedback">
                                    صحیح است!
                                </div>
                            </div><!-- form-group -->  
                        
                        
                        
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
                            <select  name="service_special_category"   class="form-control" id="exampleFormControlSelect2">
                                @foreach (\App\Models\Services\Service::latest()->get() as $service)
                                 <option value="{{$service->service_id}}">{{$service->service_title}}</option>
                                @endforeach  
                            </select>
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

<div class="modal fade bd-example-modal-lg-edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">    <div class="modal-dialog modal-lg">
    <div class="modal-content edit-modal-content">
      
    </div>
</div>
</div>



<div class="container-fluid">
    <div class="card">
        <div class="container_icon card-body d-flex justify-content-end">

            <div class="delete-edit" style="display:none;"> 
                @if (auth()->user()->can('service_delete'))
                <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="order-delete   m-2">
                  <span class="__icon bg-danger">
                      <i class="fa fa-trash"></i>
                  </span>
                 </a>
                @endif
                @if (auth()->user()->can('service_edit'))
                    
           <a href="#" title="ویرایش" data-toggle="modal" data-target=".bd-example-modal-lg-edit" class="mx-2" >
            <span class="edit-personal __icon bg-info">
                <i class="fa fa-edit"></i>
            </span>
           </a>
                @endif
            </div>

        <div>
            <a href="#" class="mx-2 btn--filter"  title="فیلتر اطلاعات">
                <span class="__icon bg-info">
                    <i class="fa fa-search"></i>
                </span>
            </a>
           @if (auth()->user()->can('service_insert'))
           <a href="#" data-toggle="modal" data-target=".bd-example-modal-lg" title="افزودن کاربر">
            <span class="__icon bg-success">
                <i class="fa fa-plus"></i>
            </span>
            </a>
           @endif
            <a href=" {{route('Pannel.Services.Questions')}} " title="تازه سازی" class="mx-2" >
                <span class="__icon bg-primary">
                    <i class="fa fa-refresh"></i>
                </span>
            </a>
           </div>
        </div>
    </div>

      {{-- filtering --}}
      <div class="card filtering" style="display:none;">
        <form action=" {{route('Service.FilterData')}} " method="post">
            @csrf
            <div class="card-body">
                <div class="row " >
                  <div class="form-group col-md-6">
                    <label for="recipient-name" class="col-form-label">فیلتر اطلاعات براساس: </label>
                    <select  name="type_send"   class="form-control" id="filtering">
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
        <div class="card-body" >
            <div class="card-title">
                <h5 class="text-center">مدیریت خدمات</h5>
                <hr>
            </div>
               <div style="overflow-x: auto;">
                <table  id="example1" class="table table-striped  table-bordered" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>ردیف</th>
                        <th>
                            <a href="#" data-id="title" class="name_field text-white">
                                عنوان
                                <i class="fa fa-angle-down"></i>  
                              </a>
                        </th>
                        <th>
                            <a href="#" data-id="broker_name" class="name_field text-white" >
                                نام کارگزاری
                                <i class="fa fa-angle-down"></i>  
                              </a>
                        </th>
                        <th> توضیحات</th>
                        <th>دسته بندی خدمات</th>
                        <th> نقش</th>
                        <th>فاصله</th>
                        <th>
                            <a  href="#" data-id="persent" class="name_field text-white">
                                درصد پورسانت
                                <i class="fa fa-angle-down"></i>  
                            </a>
                        </th>
                        <th>پیشنهاد ویژه در خدمات زیر</th>
                        <th>نوع خدمت</th>
                        <th>نوع ارجاع</th>
                        <th>ارسال پیامک به خدمت رسان</th>
                        <th>عکس</th> 
                    </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach ($services as $key=> $service)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
                                <input data-id=" {{$service->id}} " type="checkbox" id="{{ $key}}" name="customCheckboxInline1" class="custom-control-input" value="1">
                                  <label class="custom-control-label" for="{{$key}}"></label>
                                </div>
                            </td>
                            <td> {{$key+1}} </td>
                            <td>{{$service->service_title}}</td>
                            <td>{{$service->service_role}}</td>
                            <td>
                                @if ($service->service_desc !== null)
                                {{$service->service_desc}}
                                @else
                                --
                                @endif
                            
                            </td>
                            <td>
                                @if ($service->relationCategory !== null)
                                {{$service->relationCategory->category_title}} 
                                @else
                                    ندارد
                                @endif
                            </td>
                            <td>{{$service->service_rol}}</td>
                            <td>--</td>
                            <td>
                              {{$service->service_percentage . '%'}}
                            </td>
                            <td>
                                {{$service->service_special_category}}
                            </td>
                            <td>{{$service->price_type}}</td>
                            <td>{{$service->service_type_send}}</td>
                            @if ($service->sms_status == 1)
                                <td class="text-success">
                                    <i class="fa fa-check"></i>
                                </td>
                                @else
                                <td class="text-danger">
                                    <i class="fa fa-close"></i>
                                </td>
                            @endif
                            <td> 
                            @if ($service->service_icon !== '')
                                <img width="75px" class="img-fluid " src=" {{asset("uploads/service_icons/$service->service_title/$service->service_icon")}} " />
                            @else
                           --
                            @endif

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

$('.bd-example-modal-lg-edit').on('shown.bs.modal', function (event) {
   category_id =  $('table input[type="checkbox"]:checked').attr('data-id')
    $.ajax({
    type:'post',
    url:'{{route("Service.Edit.getData")}}',
    cache: false,
    async: true,
    data:{category_id:category_id},
    success:function(data){
       $('.edit-modal-content').html(data)
       $('.js-example-basic-single').select2({
         placeholder: 'انتخاب کنید'
        });
        editform= $('#edit--form')
        var form = $("#example-advanced-form1").show();
    form.validate({
        rules: {
          title: {
            required: true,
            // digits: true,
            // minlength: 5,
            // maxlength: 5
          },
          
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
                url:'{{route("Service.Delete")}}',
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

// $(document).on('click','.level-1',function(){
//       $(this).nextAll('.level-2').slideDown(300)
//     })
//     $(document).on('click','.level-2',function(){
//       $(this).nextAll('.level-3').slideDown(300)
//     })
//     $(document).on('click','.level-3',function(){
//       $(this).nextAll('.level-4').slideDown(300)
//     })


})
</script>
@endsection
