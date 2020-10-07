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
        <a type="button" class="delete1 btn btn-danger text-white">حذف! </a>
      </div>
    </div>
  </div>
</div>

{{--مدال برای تایید پرداخت--}}
<div class="modal fade" id="exampleModalp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalpLabel">پرداخت فرم تسویه حساب</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          آیا از پرداخت فرم تسویه حساب اطمینان دارید؟
      </div>
      <div class="modal-footer">
        <a type="button" class="delete btn btn-danger text-white">پرداخت! </a>
      </div>
    </div>
  </div>
</div>


<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">تسویه حساب با خدمت رسان ها</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="user--form" method="post" action=" {{route('Pannel.Acounting.CheckoutPersonals.Submit')}} " >
        @csrf
        <div class="modal-body">

          <div class="row">
            <div class="form-group col-md-12">
                <select name="personals" id="personals_type"  class="js-example-basic-single" dir="rtl">
                    <option value="all" >همه</option>
        
        
                    @foreach ($personals as $personal)
                                                            <option value="{{$personal->id}}"
                                                  
        
                                                  > {{$personal->personal_lastname}}</option>
                                            @endforeach
                </select>
            </div>
           
          </div>
          
          


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
          <button type="submit" class="btn btn-primary">ایجاد</button>
        </div>
      </form>
    </div>
  </div>
</div>
</div>


{{-- modal for edit --}}

<div class="modal fade bd-example-modal-lg-edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content edit__modal">
      
    </div>
  </div>
</div>


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
            <div class="delete-edit">

            </div>
            <div>
                <a href="#" class="mx-2 btn--filter" title="فیلتر اطلاعات">
                    <span class="__icon bg-info">
                        <i class="fa fa-search"></i>
                    </span>
                </a>

                <a href="#" data-toggle="modal" data-target=".bd-example-modal-lg" title="افزودن تسویه حساب">
                    <span class="__icon bg-success">
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
                <a href="#" title="تازه سازی" class="mx-2" onclick="location.reload()">
                    <span class="__icon bg-primary">
                        <i class="fa fa-refresh"></i>
                    </span>
                </a>
                <a href="{{route('Pannel.Acounting.CheckoutPersonals.Export')}}" title="خروجی اکسل" class="mx-2">
                    <span class="__icon bg-primary">
                        <i class="fa fa-table"></i>
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
                <h5 class="text-center">تسویه حساب خدمت رسان ها</h5>
                <hr>
            </div>
            <div style="overflow-x: auto;">
                <table  id="example1" class="table table-striped  table-bordered" >
                    <thead>
                        <tr>
                            <th></th>
                            <th>شناسه تسویه</th>
                            <th>شماره همراه</th>
                            <th>نام خانوادگی</th>
                            <th>نام</th>


                            <th>وضعیت پرداخت</th>

                            <th>مبلغ بستانکاری</th>

                            <th>  شماره شبا </th>
                            <th> 
                                تاریخ ثبت درخواست
                            </th>
                      
                            <th>  کد تراکنش برداشت </th>

                            <th>تاریخ پرداخت</th>

                            <th>توضیحات</th>
                     
                         
                        </tr>
                    </thead>
                    <tbody class="tbody">

                         @foreach ($personals as $personal)
                        @foreach ($personal->useracounts as $useracount)
                        @foreach ($useracount->checkoutpersonals as $checkoutpersonal)

                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox custom-control-inline"
                                    style="margin-left: -1rem;">
                                    <input data-id=" {{$checkoutpersonal->id}} " type="checkbox" id="{{ $checkoutpersonal->id}}"
                                        name="customCheckboxInline1" class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="{{$checkoutpersonal->id}}"></label>
                
                                </div>
                            </td>
                             <td>{{$checkoutpersonal->id}}</td>
                             <td>{{$personal->personal_mobile}}</td>
                             <td>{{$personal->personal_lastname}}</td>
                             <td>{{$personal->personal_firstname}}</td>

                             @if ($checkoutpersonal->payed == 1)
                             <td class="text-success">
                                 <i class="fa fa-check"></i>
                             </td>
                             @else
                             <td class="text-danger">
                                 <i class="fa fa-close"></i>
                             </td>
                             @endif

                             {{-- <td>{{$checkoutpersonal->amount}}</td> --}}
                             <td>
                             <?php
   
                             echo number_format($checkoutpersonal->amount);

                             ?>
</td>

                            <td>{{$checkoutpersonal->shaba}}</td>



                            <td> {{\Morilog\Jalali\Jalalian::forge($checkoutpersonal->created_at)->format('%Y-%m-%d H:i:s')}}

                            <td>{{$checkoutpersonal->transations_id}}</td>

                            {{-- <td>{{$checkoutpersonal->payed_at}}</td> --}}
                            @if($checkoutpersonal->payed_at)
                            <td> {{\Morilog\Jalali\Jalalian::forge($checkoutpersonal->payed_at)->format('%Y-%m-%d H:i:s')}}

                                @else
                                <td></td>
                                @endif

                            <td>{{$checkoutpersonal->description}}</td>

                            




                        </tr>
                        @endforeach
                        @endforeach
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
    .date-picker-shamsi {
        z-index: 1151 !important;
    }
</style>
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
       
        $('.btn--filter').click(function(){
          $('.filtering').toggle(200)
        })

           $('table input[type="checkbox"]').change(function(){
            array=[]
            $('table input[type="checkbox"]').each(function(){

                if($(this).is(':checked')){
                  array.push($(this).attr('data-id'))
               }

            if(array.length !== 0){

                if (array.length !== 1) {
        //             $('.container_icon').removeClass('justify-content-end')
        //             $('.container_icon').addClass('justify-content-between')
        //             $('.delete-edit').html(`
        //             <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="sweet-multiple mx-2">
        //     <span class="__icon bg-danger">
        //         <i class="fa fa-trash"></i>
        //     </span>
        //    </a>
        //             `)
                }else{

                    $('.container_icon').removeClass('justify-content-end')
                    $('.container_icon').addClass('justify-content-between')
                    $('.delete-edit').html(`
                    <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="sweet-multiple mx-2">
            <span class="__icon bg-danger">
                <i class="fa fa-trash"></i>
            </span>
           </a>

           <a href="#" title="پرداخت" data-toggle="modal" data-target="#exampleModalp" class="mx-2" >
            <span class="__icon bg-info">
                <i class="fa fa-money"></i>
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
   personal_id =  $('table input[type="checkbox"]:checked').attr('data-id')
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


// Delete
   
$('.delete1').click(function(e){
                e.preventDefault()
                console.log(array)

                // ajax request
 $.ajax({

                type:'post',
                url:'{{route("Pannel.Acounting.CheckoutPersonals.Delete")}}',
                 data:{array:array},
                // ,success:function(data){
                //   swal("پرداخت با موفقیت انجام شد", {
                //     icon: "success",
				// 	          button: "تایید"
                //        });

                      
                 
                 success:function(data){
                //   swal("پرداخت تکراری نکن ملعون", {
                //     icon: "success",
				// 	          button: "باشه"
                //        });

                       setTimeout(()=>{
                        location.reload()
                       },1000)
               
                }
        })
    })

    $('.delete').click(function(e){
                e.preventDefault()
                console.log(array)

                // ajax request
 $.ajax({

                type:'post',
                url:'{{route("Pannel.Acounting.CheckoutPersonals.Pay")}}',
                 data:{array:array},
                // ,success:function(data){
                //   swal("پرداخت با موفقیت انجام شد", {
                //     icon: "success",
				// 	          button: "تایید"
                //        });

                      
                 
                 success:function(data){
                //   swal("پرداخت تکراری نکن ملعون", {
                //     icon: "success",
				// 	          button: "باشه"
                //        });

                       setTimeout(()=>{
                        location.reload()
                       },1000)
               
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

    })
</script>
@endsection