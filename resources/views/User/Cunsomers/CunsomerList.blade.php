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
        @if (auth()->user()->can('customer_delete'))
        <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="   m-2">
          <span class="__icon bg-danger">
              <i class="fa fa-trash"></i>
          </span>
         </a>
        @endif
        @if (auth()->user()->can('customer_edit'))
          <a href="#" title="ویرایش" data-toggle="modal" data-target=".bd-example-modal-lg-edit" class="mx-2" >
            <span class="edit-customer __icon bg-info">
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
        <a href=" {{route('Pannel.Cunsomers.List')}} " title="تازه سازی" class="mx-2">
          <span class="__icon bg-primary">
            <i class="fa fa-refresh"></i>
          </span>
        </a>
      </div>
    </div>
  </div>
  {{-- filtering --}}
  <div class="card filtering" style="display:none;">
    <form action=" {{route('Customers.Filter')}} " method="post">
      @csrf
      <div class="card-body">
        <div class="row">
          <div class="form-group col-md-6">
            <label for="recipient-name" class="col-form-label">فیلتر اطلاعات براساس: </label>
            <select id="filtering" required name="type_send" class="form-control">
              <option value="نام">نام</option>
              <option value="نام خانوادگی">نام خانوادگی</option>
              <option value="کد ملی">کد ملی</option>
              <option value="شماره موبایل">شماره موبایل</option>
              <option value="تاریخ ثبت">تاریخ ثبت</option>
            </select>
          </div>
          <div class="form-group col-md-6 search-box">
            <label for="recipient-name" class="col-form-label">عبارت مورد نظر: </label>
            <input type="text" class="form-control" id="word" name="word" >
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
        <h5 class="text-center">مدیریت مشتریان</h5>
        <hr>
      </div>
      <div style="overflow-x: auto;">
        <table  id="example1" class="table table-striped  table-bordered" >
          <thead>
            <tr>
              <th></th>
              <th>ردیف</th>
              <th>
                
              <a  href="#" data-id="name" class="name_field text-white">
                نام
                <i class="fa fa-angle-down"></i>  
            </a>
          </th>
          <th> 
                <a  href="#" data-id="family" class="name_field text-white">
                نام خانوادگی
                <i class="fa fa-angle-down"></i>  
            </a>
          </th>
              <th>شماره همراه</th>
              <th>فعال</th>
              <th>کد ملی</th>
              <th>
               <a  href="#" data-id="created_date" class="name_field text-white">
                تاریخ ثبت
                <i class="fa fa-angle-down"></i>  
               </a>
              </th>
              <th>تاریخ ویرایش</th>

            </tr>
          </thead>
          <tbody class="tbody">
            @foreach ($customers as $key=>$customer)
            <tr>
              <td>
                <div class="customer-list custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
                  <input data-id=" {{$customer->id}} " type="checkbox" id="{{ $key}}" name="customCheckboxInline1"
                    class="custom-control-input" value="1">
                  <label class="custom-control-label" for="{{$key}}"></label>
                </div>
              </td>
              <td> {{$key+1}} </td>
              <td>{{$customer->customer_firstname}}</td>
              <td>{{$customer->customer_lastname}}</td>
              <td>
                @if ($customer->customer_mobile)
                {{$customer->customer_mobile}}
                @else
                وارد نشده
                @endif
              </td>
              @if ($customer->customer_status == 1)
              <td class="text-success">
                <div class=" form-group" style="display:inline-block;" >
                  <div class="custom-control custom-switch custom-checkbox-success">
                      <input data-id="{{$customer->id}}" type="checkbox" value="1" class="custom-control-input" id="status_{{$key}}" checked>
                      <label class="custom-control-label" for="status_{{$key}}"></label>
                  </div>
               </div>
              </td>
              @else
              <td class="text-danger">
                <div class=" form-group" style="display:inline-block;" >
                  <div class="custom-control custom-switch custom-checkbox-success">
                      <input data-id="{{$customer->id}}" type="checkbox" value="1"
                       class="custom-control-input" id="status_{{$key}}" >
                      <label class="custom-control-label" 
                      for="status_{{$key}}"></label>
                  </div>
               </div>
              </td>
              @endif
              <td>
                @if ($customer->customer_national_code)
                {{$customer->customer_national_code}}
                @else
                وارد نشده
                @endif
              </td>
              <td>{{\Morilog\Jalali\Jalalian::forge($customer->created_at)->format('%d/ %m /%Y')}}</td>
              <td>{{\Morilog\Jalali\Jalalian::forge($customer->update_at)->format('%d/ %m /%Y')}}</td>

            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
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
           $('.customer-list input[type="checkbox"]').change(function(){
           if( $(this).is(':checked')){
            $(this).parents('tr').css('background-color','#41f5e07d');
            }else{
                $(this).parents('tr').css('background-color','');
            }
            array=[]
            $('.customer-list input[type="checkbox"]').each(function(){
                if($(this).is(':checked')){
                  array.push($(this).attr('data-id'))
               }
            if(array.length !== 0){
              $('.delete-edit').show();
                if (array.length !== 1) {
                    $('.container_icon').removeClass('justify-content-end')
                    $('.container_icon').addClass('justify-content-between')
                   $('.edit-customer').hide();
                }else{
                    $('.container_icon').removeClass('justify-content-end')
                    $('.container_icon').addClass('justify-content-between')
                    $('.edit-customer').show();
                }
            }
            else{
                $('.container_icon').removeClass('justify-content-between')
                $('.container_icon').addClass('justify-content-end')
                $('.delete-edit').hide();
            }
        })
            
    })



 
              // edit 

$('.bd-example-modal-lg-edit').on('shown.bs.modal', function (event) {
       
       id = $('table input[type="checkbox"]:checked').attr('data-id')
       
       $.ajax({
       type:'post',
       url:'{{route("Customer.Edit.getData")}}',
       cache: false,
                   async: true,
       data:{id:id},
       success:function(data){
          $('.edit-modal-content').html(data)
          editform= $('#edit--form')
          editform.validate({
               rules: {
                 firstname: {
                   required: true,
                   // digits: true,
                   // minlength: 5,
                   maxlength: 20
                 },
                 lastname:{
                   required:true,
                   maxlength: 20
                 },
              
               },
               messages: {
                firstname: {
                   //minlength: jQuery.format("Zip must be {0} digits in length"),
                   maxlength:'نام حداکثر 20 کاراکتر میتواند داشته باشد',
                   required: "لطفا نام را وارد نمایید"
                 },
                 lastname: {
                   //minlength: jQuery.format("Zip must be {0} digits in length"),
                   //maxlength: jQuery.format("Please use a {0} digit zip code"),
                   required: "لطفا نام خانوادگی را وارد نمایید",
                   maxlength:'نام خانوادگی حداکثر 20 کاراکتر میتواند داشته باشد',
       
                 },
               }
             })
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
                url:'{{route("Customers.Delete")}}',
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

     // filtering
     $('#filtering').change(function(){
        if ($(this).val() == 'تاریخ ثبت') {
            $('.search-box').html(
                ` 
                <label for="word" class="col-form-label">انتخاب تاریخ: </label>
                <input type="text" id="word" name="word"
                autocomplete="off"
                class="form-control text-right date-picker-shamsi"
                 dir="ltr">
                 `
            )
            $('.date-picker-shamsi').datepicker({
              dateFormat: "yy/mm/dd",
              showOtherMonths: true,
              selectOtherMonths: false
            });
        }
     }); 

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
url:'{{route("Customer.ChangeStatus")}}',
data:{id:id,value:value},
success:function(data){
    swal("", "تغییر وضعیت مشتری با موفقیت انجام شد", "success", {
			button: "باشه"
		});
   
}
})
})

// order By
var namefield = $('.name_field')
namefield.click(function(e){
  e.preventDefault();
 var data = $(this).attr('data-id');
 $.ajax({
type:'post',
url:'{{route("Customers.OrderBy.Table")}}',
data:{data:data},
success:function(data){ 
   $('.tbody').html(data)
   }
 })
})

})
</script>
@endsection