@extends('Layouts.Pannel.Template')

@section('content')

<div class="modal fade" id="showStore" tabindex="-1" role="dialog" aria-labelledby="showStoreLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
<div class="modal fade" id="locationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">مناطق تحت پوشش</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body locations">

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
                    <form id="example-advanced-form" method="post" action="{{route('Pannel.Product')}}"
                        enctype="multipart/form-data">
                        @csrf
                        <h3>محصول</h3>
                        <section>
                            <div class="row product-detail mb-2" style="position: relative;">
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">نام محصول</label>
                                    <input id="product_name" class="form-control text-right" name="product_name"
                                        required type="text" dir="rtl">

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">قیمت محصول</label>
                                    <input id="product_price" class="form-control text-right" name="product_price"
                                        required type="number" dir="rtl">
                                </div>
                                   <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">تخفیف (درصد)</label>
                                    <input id="discount" class="form-control text-right" name="discount"
                                        required type="number" dir="rtl">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">تعداد</label>
                                    <input id="count" class="form-control text-right" name="count" required
                                        type="number" dir="rtl">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">تصویر محصول</label>
                                    <input id="product_picture" class="form-control text-right" name="product_picture"
                                        type="file" dir="rtl">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="recipient-name" class="col-form-label">دسته بندی</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">باز کردن فهرست انتخاب</option>
                                       
                                        @foreach (\App\Models\Category::all() as $item)
                                        <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="form-group  col-md-6 pt-4">
                                    <span>وضعیت محصول</span>
                                    <div class="">
                                        <label class="" for="">فعال</label>
                                        <input style="display:inline-block;" value="1" type="checkbox" class=""
                                            name="product_status" id="">
                                    </div>
                                </div> --}}
                                <div class="form-group col-md-12">
                                    <label for="recipient-name" class="col-form-label">توضیح محصول: </label>
                                    <textarea id="product_description" class="form-control text-right"
                                        name="product_description" type="text" dir="rtl"></textarea>
                                </div>
                            </div>

                        </section>
                    </form>
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

                <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="order-delete   m-2">
                    <span class="__icon bg-danger">
                        <i class="fa fa-trash"></i>
                    </span>
                </a>

                <a href="#" title="ویرایش فروشگاه" data-toggle="modal" data-target=".bd-example-modal-lg-edit"
                    class="mx-2">
                    <span class="edit-personal __icon bg-info">
                        <i class="fa fa-edit"></i>
                    </span>
                </a>

            </div>
            <div>

                <a href="#" data-toggle="modal" data-target=".bd-example-modal-lg" title="افزودن فروشگاه">
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
                <h5 class="text-center">لیست محصولات</h5>
                <hr>
            </div>
            <div style="overflow-x: auto;">
                <table id="example1" class="table table-striped  table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ردیف</th>
                            <th>
                                نام محصول
                            </th>
                            <th>
                                تعداد موجودی

                            </th>
                            <th>دسته بندی</th>
                            <th>
                                قیمت
                            </th>
                            <th>تخفیف </th>

                            <th>تصویر</th>

                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach ($products as $key=>$product)
                        <tr>
                            <td>
                                <div class="checkstores custom-control custom-checkbox custom-control-inline"
                                    style="margin-left: -1rem;">
                                    <input data-id="{{$product->id}}" type="checkbox" id="{{ $key}}" name="checkbox"
                                        class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="{{$key}}"></label>
                                </div>
                            </td>
                            <td> {{$key+1}} </td>
                            <td>{{$product->name}}</td>
                            <td>
                                {{$personal->count}}
                            </td>
                            <td>{{$product->category->name}}
                            </td>
                            <td>
                                {{$product->price}}
                            </td>
                            <td>{{$product->discount}}</td>
                            <td>
                                @if ($product->product_picture !== '' && $product->product_picture !== null)
                                <a href="#" title="مشاهده تصویر" data-toggle="modal" data-target="#showStore">
                                    <img width="75px" class="img-fluid "
                                        src="{{asset("uploads/$product->product_picture")}} " />
                                </a>
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


//  //Datemask dd/mm/yyyy
//  $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
//     //Datemask2 mm/dd/yyyy
//     $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()
       
        $('.btn--filter').click(function(){
          $('.filtering').toggle(200)
        })

           $('.checkstores input[type="checkbox"]').change(function(){
            array=[]
            $('.checkstores input[type="checkbox"]').each(function(){
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





$(document).on('click','.remove-product',function(e){
    e.preventDefault()
    $(this).parents('.product-detail').remove()
  
})
$(document).on('click','.remove-product',function(e){
    e.preventDefault()
    $(this).parents('.sundry-product-detail').remove()
  
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
   store_id =  $('tbody input[name="checkbox"]:checked').attr('data-id')
    $.ajax({
    type:'post',
    url:'{{route("Store.Edit.getData")}}',
    cache: false,
    async: true,
    data:{store_id:store_id},
    success:function(data){
       $('.edit-modal-content').html(data)
    //    $('.js-example-basic-single').select2({
    //      placeholder: 'انتخاب کنید'
    //     });
        editform= $('#edit--form')
        var form = $("#example-advanced-form1").show();
    form.validate({
        rules: {
          
        store_pluck_num:{
            required:true
        },
        mobile:{
            required:true
        },store_type:{
            required:true
        }
        
         
            
        
        },
        messages: {
            firstname: {
            required:' نام فروشنده را وارد نمایید'
        },
        lastname: {
            required:' نام خانوادگی فروشنده را وارد نمایید'
        },
            store_name: {
            required:' نام فروشگاه را وارد نمایید'
        },
        store_city: {
            required:' نام شهر را وارد نمایید'
        },
        store_main_street:{
            required: 'نام خیابان اصلی را وارد نمایید'
        },
        store_pluck_num:{
            required: 'شماره پلاک را وارد نماید'
        },
        mobile:{
            required: 'شماره موبایل را وارد کنید'
        },
        tel_work: {
            required:'شماره محل کار را وارد نمایید'
        },
        store_type:{
            required:'دسته بندی فروشگاه را وارد نمایید'
        }
        }
      });
  
 //Datemask dd/mm/yyyy
 $('#birth_year').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
$('#birth_year').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
   

        $('[data-mask]').inputmask()
    

         }
    
      });
    
    }); 




   




// Delete
   

    $('.delete').click(function(e){
                e.preventDefault()
               

                // ajax request
      $.ajax({
                type:'post',
                url:'{{route("Stores.Delete")}}',
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
var mobile = $(this).val();
var thiss = $(this);
if(mobile !== ''){
    $.ajax({
type:'post',
url:'{{route("Store.getOwnerData")}}',
data:{mobile:mobile},
success:function(data){
    $('#firstname').val(data.personal_firstname)
    $('#lastname').val(data.personal_lastname)
    $('#user_national_num').val(data.personal_national_code)
    $('#birth_year').val(data.personal_birthday)

}
})
}
})


$('#showStore').on('shown.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
  var recipient = button.find('img').attr('src') // Extract info from data-* attributes
  var modal = $(this)
  modal.find('img').attr('src',recipient)
})
})
</script>
@endsection