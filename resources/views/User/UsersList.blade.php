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

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ثبت کاربر</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="user--form" method="post" action=" {{route('User.Submit')}} " enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12" style="display: flex;align-items: center;justify-content: center;">
              <div class="profile-img">
                <div class="chose-img">
                  <input type="file" class="btn-chose-img" id="user_profile" name="user_profile"
                    title="نوع فایل میتواند png , jpg  باشد">
                </div>
                <img
                  style="border-radius: 50%;object-fit: contain; background: #fff; max-width: 100%; height: 100%; width: 100%;"
                  src="{{route('BaseUrl')}}/Pannel/img/temp_logo.jpg" alt="">
                <p class="text-chose-img" style="position: absolute;top: 44%;left: 14%;font-size: 13px;">انتخاب
                  پروفایل</p>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="user_name" class="col-form-label"><span class="text-danger">*</span> نام: </label>
              <input type="text" class="form-control" name="user_name" id="user_name">
            </div>
            <div class="form-group col-md-6">
              <label for="user_family" class="col-form-label"><span class="text-danger">*</span> نام خانوادگی:</label>
              <input type="text" class="form-control" name="user_family" id="user_family">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="user_pass" class="col-form-label"><span class="text-danger">*</span> پسورد:</label>
              <input type="text" class="form-control" name="user_pass" id="user_pass">
            </div>
            <div class="form-group col-md-6">
              <label for="confirm_user_pass" class="col-form-label"><span class="text-danger">*</span> تکرار
                پسورد:</label>
              <input type="text" class="form-control" name="confirm_user_pass" id="confirm_user_pass">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="user_email" class="col-form-label">ایمیل:</label>
              <input type="text" class="form-control" name="user_email" id="user_email">
            </div>
             <div class="form-group col-md-6">
              <label for="user_mobile" class="col-form-label"><span class="text-danger">*</span> موبایل:</label>
              <input type="text" class="form-control" name="user_mobile" onblur="validateMobile(event,this.value)"
                id="user_mobile">
            </div>
           
          </div>
         

         
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
          <button type="submit" class="btn btn-primary btn--submit" >ذخیره</button>
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
    <div class="modal-content edit-modal-user">

    </div>
  </div>
</div>

<div class="container-fluid">
  <div class="card">
    <div class="container_icon card-body d-flex justify-content-end">
      <div class="delete-edit" style="display:none;">

        <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="order-delete   m-2">
          <span class="__icon bg-danger">
            <i class="fa fa-trash"></i>
          </span>
        </a>


        <a href="#" title="ویرایش" data-toggle="modal" data-target=".bd-example-modal-lg-edit" class="mx-2">
          <span class="edit-personal __icon bg-info">
            <i class="fa fa-edit"></i>
          </span>
        </a>

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

        <a href=" {{route('Pannel.User.List')}} " title="تازه سازی" class="mx-2">
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
      <form action=" {{route('Users.FilterData')}} " method="post">
        @csrf
        <div class="row ">
          <div class="form-group col-md-6">
            <label for="recipient-name" class="col-form-label">فیلتر اطلاعات براساس: </label>
            <select required name="filter_type" class="form-control" id="exampleFormControlSelect2">
              <option value="نام">نام</option>
              <option value="نام خانوادگی">نام خانوادگی</option>
           
              <option value="شماره موبایل">شماره موبایل</option>

            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="recipient-name" class="col-form-label">عبارت مورد نظر: </label>
            <input type="text" class="form-control" name="word" id="recipient-name">
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
        <h5 class="text-center">مدیریت کاربران</h5>
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
                <a href="#" data-id="lastname" class="name_field text-white">
                  نام خانوادگی
                  <i class="fa fa-angle-down"></i>
                </a>
              </th>

              <th>شماره موبایل</th>
              <th>پروفایل عکس</th>

            </tr>
          </thead>
          <tbody class="tbody">

            @foreach ($users as $key=>$user)
            <tr>
              <td>
                <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
                  <input data-id="{{$user->id}}" type="checkbox" id="user_{{ $key}}" name="customCheckboxInline1"
                    class="custom-control-input" value="1">
                  <label class="custom-control-label" for="user_{{$key}}"></label>
                </div>
              </td>
              <td> {{$key+1}} </td>
              <td>{{$user->user_firstname}}</td>
              <td>{{$user->user_lastname}}</td>

              <td>{{$user->user_mobile}}</td>
              <td>
                @if ($user->user_prfile_pic !== '' && $user->user_prfile_pic !== null )
                <img width="75px" class="img-fluid " src=" {{asset("uploads/brokers/$user->user_prfile_pic")}} " />
                @else
                <img width="75px" class="img-fluid " src=" {{asset("Pannel/img/avatar.jpg")}} " />
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
</div>
@endsection
@section('css')
<style>


</style>
@endsection

@section('js')


<script>
  $(document).ready(function(){
    $.ajaxSetup({

headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 }
});

var namefield = $('.name_field')
namefield.click(function(e){
  e.preventDefault();
 var data = $(this).attr('data-id');

 $.ajax({

type:'post',
url:'{{route("User.OrderBy.Table")}}',
data:{data:data},
success:function(data){ 
   $('.tbody').html(data)
   }
 })
})

$("#user_profile").on("change", function () {
    
    var fileInput = $("#user_profile")[0],
    file = fileInput.files && fileInput.files[0];
    if( file ) {
    var img = new Image();
    img.src = window.URL.createObjectURL( file );
    img.onload = function() {
    var width = img.naturalWidth,
        height = img.naturalHeight;
    window.URL.revokeObjectURL( img.src );
    if(width <= 400 && height <= 400 ) {}else{
      swal("اخطار!", "فایل تصویر حداکثر باید در ابعاد 400X400 باشد", "warning", {
			button: "باشه"
    });
    $("#user_profile").val('')
    }
  }
  }
});










    // form validation 
    var form = $("#user--form");
    form.validate({
        rules: {
          user_name: {
            required: true,
            // digits: true,
            // minlength: 5,
            maxlength: 20
          },
          user_family:{
            required:true,
            maxlength: 20
          },
          user_pass:{
            required:true,
            regex: "^[^;,&]*$"
          },
        confirm_user_pass:{
                    required:true,
                    equalTo : "#user_pass"
                  },
        username:{
                    required:true
                  },
        user_mobile:{
                    required:true,
                    digits: true,
                    minlength: 11,
                    maxlength:11
          },
          user_email:{
            email: true

          }
        },
        messages: {
          user_name: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            maxlength:'نام حداکثر 20 کاراکتر میتواند داشته باشد',
            required: "لطفا نام را وارد نمایید"
          },
          user_family: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا نام خانوادگی را وارد نمایید",
            maxlength:'نام خانوادگی حداکثر 20 کاراکتر میتواند داشته باشد',

          },
          user_pass: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا پسورد را وارد نمایید",
            regex: 'dssdsds'
          },
          username: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا نام کاربری را وارد نمایید"
          },
          confirm_user_pass: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "رمز عبور را تکرار کنید",
            equalTo : "تکرار رمز عبور صحیح نمیباشد"
          },
          user_email:{
            email: 'فرمت ایمیل را به صورت صحیح وارد نمایید'

          },
          user_mobile: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا شماره موبایل را وارد نمایید",
            digits: 'شماره موبایل بایستی به صورت عددی وارد شود',
            minlength: 'شماره موبایل بایستی 11 رقم باشد',
            maxlength: 'شماره موبایل بایستی 11 رقم باشد',
     

          },
        }
      });


      // edit 


      $('.bd-example-modal-lg-edit').on('shown.bs.modal', function (event) {
   user_id =  $('.tbody input[type="checkbox"]:checked').attr('data-id')
    $.ajax({
    type:'post',
    url:'{{route("User.Edit.getData")}}',
    cache: false,
    async: true,
    data:{user_id:user_id},
    success:function(data){
       $('.edit-modal-user').html(data)
    
        editform= $('#edit-user-form')
        var form = $("#example-advanced-form1").show();
        form.validate({
        rules: {
          user_name: {
            required: true,
            // digits: true,
            // minlength: 5,
            maxlength: 20
          },
          user_family:{
            required:true,
            maxlength: 20
          },
          user_pass:{
            required:true,
            regex: "^[^;,&]*$"
          },
        confirm_user_pass:{
                    required:true,
                    equalTo : "#user_pass"
                  },
        username:{
                    required:true
                  },
        user_mobile:{
                    required:true,
                    digits: true,
                    minlength: 11,
                    maxlength:11
          },
          user_email:{
            email: true

          }
        },
        messages: {
          user_name: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            maxlength:'نام حداکثر 20 کاراکتر میتواند داشته باشد',
            required: "لطفا نام را وارد نمایید"
          },
          user_family: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا نام خانوادگی را وارد نمایید",
            maxlength:'نام خانوادگی حداکثر 20 کاراکتر میتواند داشته باشد',

          },
          user_pass: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا پسورد را وارد نمایید",
            regex: 'dssdsds'
          },
          username: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا نام کاربری را وارد نمایید"
          },
          confirm_user_pass: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "رمز عبور را تکرار کنید",
            equalTo : "تکرار رمز عبور صحیح نمیباشد"
          },
          user_email:{
            email: 'فرمت ایمیل را به صورت صحیح وارد نمایید'

          },
          user_mobile: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا شماره موبایل را وارد نمایید",
            digits: 'شماره موبایل بایستی به صورت عددی وارد شود',
            minlength: 'شماره موبایل بایستی 11 رقم باشد',
            maxlength: 'شماره موبایل بایستی 11 رقم باشد',
     

          },
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

    $('.delete').click(function(e){
                e.preventDefault()
                // ajax request
                $.ajax({

                type:'post',
                url:'{{route("Users.Delete")}}',
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

    $(document).on('blur','#username',function(){
     
      if($(this).val().length > 15){
        swal("", "نام کاربری حداکثر میتواند 15 کاراکتر باشد", "error", {
			button: "باشه"
    });
    $(this).val('') 
        }
      var english = /^[A-Za-z0-9]*$/;
      if (!english.test($(this).val())){
        swal("", "نام کاربری نمیتواند شامل حروف فارسی باشد", "error", {
			button: "باشه"
    });
    $(this).val('')
      }
    })
  

  
})
</script>
@endsection