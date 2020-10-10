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
        <h5 class="modal-title" id="exampleModalLabel">ثبت دسته بندی </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="user--form" method="post" action="{{route('Pannel.Category')}}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-12">
              <label for="category_title" class="col-form-label"><span class="text-danger">*</span> عنوان: </label>
              <input type="text" class="form-control" name="category_title" id="category_title">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-12">
              <label for="recipient-name" class="col-form-label">دسته:</label>
              <select  @if ($count> 1)
                size=" {{($count+2)}} " @elseif($count > 10) size="10" @else size="2"
                @endif class="form-control" name="parent_id" id="parent_id">
                {!! $list !!}

              </select>
              <div class="valid-feedback">
                صحیح است!
              </div>
            </div><!-- form-group -->
            {{-- <div id="jstree_demo1"></div> --}}
          </div>

          <div class="form-group col-md-6">
            <label for="category_icon" class="col-form-label"> تصویر:</label>
            <input type="file" class="form-control" name="category_icon" id="category_icon">
          </div>


          <div class="row">
            <div class="form-group col-md-12">
              <label for="recipient-name" class="col-form-label">توضیحات :</label>
              <textarea type="text" class="form-control" name="category_description" id="category_description">
                    </textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            <button type="submit" class="btn btn-primary">ارسال</button>
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
            <option value="عنوان">عنوان</option>
            <option value="دسته بالا دستی">دسته بالا دستی</option>
            <option value="نوع">نوع</option>
            <option value="قیمت پیشنهادی">قیمت پیشنهادی</option>
            <option value="نمایش در صفحه اصلی">نمایش در صفحه اصلی</option>

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
  {{-- end filtering --}}

  <div class="card">
    <div class="card-body">
      <div class="card-title">
        <h5 class="text-center">مدیریت دسته بندی خدمات</h5>
        <hr>
      </div>
      <div style="overflow-x: auto;">
        <table id="example1" class="table table-striped  table-bordered">
          <thead>
            <tr>
              <th></th>
              <th>ردیف</th>
              <th>عنوان</th>
              <th> دسته بالا دستی</th>
              <th> توضیحات عمومی</th>
              <th>عکس</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($all_categories as $key=>$category)
            <tr>
              <td>
                <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
                  <input data-id=" {{$category->id}} " type="checkbox" id="{{ $key}}" name="customCheckboxInline1"
                    class="custom-control-input" value="1">
                  <label class="custom-control-label" for="{{$key}}"></label>
                </div>
              </td>
              <td> {{$key+1}} </td>
              <td>{{$category->name}}</td>
              <td>
                @if ($category->parent_id == 0)
                {{'ندارد'}}
                @else
                {{\App\Models\Category::where('id',$category->parent_id)->first()->name}}
                @endif
              </td>

              <td>{{str_limit($category->description,100,'...')}}</td>
              <td>
                @if ($category->picture)
                <img width="75px" style="max-height: 60px !important" class="img-fluid "
                  src="{{asset("/uploads/category_images/$category->picture")}}" alt="">
                @else
                ندارد
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


<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.1/css/font-awesome.min.css" rel="stylesheet" />

<style>
  select {
    font-family: 'FontAwesome', 'sans-serif';
  }
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

   // form validation 
   var form = $("#user--form");
    
    form.validate({
        rules: {
          category_title: {
            required: true,
            // digits: true,
            // minlength: 5,
            // maxlength: 5
          },
          category_type:{
            required:true
          },
        },
        messages: {
          category_title: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "عنوان دسته بندی را وارد نمایید"
          },
          category_type: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "نوع دسته بندی را انتخاب نمایید"
          },
          
        }
      });


// $('#service_category').each(e => {
//   console.log(e)
// })
      $('.level-1').on('click',function(e){

       var id = $(this).attr('data-id')
       
       $('.level-2').each(function(e){
         console.log(e)
      })
    });
    // icon size validations
// $("input[type='file']").on("change", function () {
//     var fileInput = $("input[type='file']")[0],
//     file = fileInput.files && fileInput.files[0];
//   if( file ) {
//     var img = new Image();
//     img.src = window.URL.createObjectURL( file );
//     img.onload = function() {
//     var width = img.naturalWidth,
//         height = img.naturalHeight;
//     window.URL.revokeObjectURL( img.src );
//     if(width <= 400 && height <= 400 ) {}else{
//       swal("اخطار!", "فایل ایکون حداکثر باید در ابعاد 400X400 باشد", "warning", {
// 			button: "باشه"
//     });
//     $("input[type='file']").val('')
//     }
//   }
//   }
// });

      // validate form
      var form = $("#user--form");
    
    form.validate({
        rules: {
          service_title: {
            required: true,
            // digits: true,
            // minlength: 5,
            // maxlength: 5
          }
        },
        messages: {
          service_title: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا عنوان خدمت را وارد نمایید"
          }
        }
      });
      $('.btn--filter').click(function(){
          $('.filtering').toggle(200)
        })
        
        
            $(document).on('change','input[type="checkbox"]',function(){
              if( $(this).is(':checked')){
            $(this).parents('tr').css('background-color','#41f5e07d');
            }else{
                $(this).parents('tr').css('background-color','');

            }
            array=[]
            $('input[type="checkbox"]').each(function(){
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




       // edit 

   $('.bd-example-modal-lg-edit').on('shown.bs.modal', function (event) {
   category_id =  $('table input[type="checkbox"]:checked').attr('data-id')
    $.ajax({
    type:'post',
    url:'{{route('category.Edit.getData')}}',
    cache: false,
    async: true,
    data:{category_id:category_id},
    success:function(data){
       $('.edit-modal-content').html(data)
       $('.js-example-basic-single').select2({
         placeholder: 'انتخاب کنید'
        });
        editform= $('#edit--form')
       editform.validate({
        rules: {
          category_title: {
            required: true,
            // digits: true,
            // minlength: 5,
            // maxlength: 5
          },
          category_type: {
            required: true,
            // digits: true,
            // minlength: 5,
            // maxlength: 5
          }
        },
        messages: {
          category_title: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا عنوان دسته بندی را وارد نمایید"
          },
          category_type: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا نوع دسته بندی را انتخاب نمایید"
          }
        }
      });
         }
    
      });
    
    }); 
    


    $('.delete').click(function(e){
                e.preventDefault()
                // ajax request
                $.ajax({

                type:'post',
                url:'{{route("Category.Delete")}}',
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

    // $(document).on('click','.level-1',function(){

    //   $(this).nextAll('.level-2').slideDown(300)
    // })
    // $(document).on('click','.level-2',function(){
    //   $(this).nextAll('.level-3').slideDown(300)
    // })
    // $(document).on('click','.level-3',function(){
    //   $(this).nextAll('.level-4').slideDown(300)
    // })

})
</script>
@endsection