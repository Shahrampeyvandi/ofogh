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
<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">ثبت اسلاید شو</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="create--slideshow" action=" {{route('SlideShow.store')}} " method="post" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="form-group col-md-12">
                <label for="title" class="col-form-label">عنوان:  </label>
                <input type="text" class="form-control" name="title" id="title">
            </div>
            <div class="form-group col-md-12">
                <label for="image" class="col-form-label">تصویر:  </label>
                <input type="file" class="form-control" name="image" id="image">
            </div>
            <div class="form-group col-md-12">
                منتشرشده: <input type="checkbox" name="status" id="status">
            </div>
            <div class="form-group col-md-12">
                پیش فرض:  <input type="checkbox" name="default" id="default">
            </div>
            <div class="form-group col-md-12">
                <label for="place" class="col-form-label">مکان:  </label>
                <select class="form-control" name="place">
                  <option value="خانه">خانه</option>
                  <option value="خدمات">خدمات</option>
                </select>
            </div>
            <div class="form-group col-md-12">
                <label for="release" class="col-form-label">تاریخ انتشار:  </label>
                <input readonly type="text" class="form-control release date-picker-shamsi" name="release" id="release">
            </div>
            <div class="form-group col-md-12">
                <label for="expiry" class="col-form-label">تاریخ انقضا:  </label>
                <input readonly type="text" class="form-control expiry  date-picker-shamsi" name="expiry" id="expiry">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
            <button type="submit" class="btn btn-primary">ذخیره </button>
          </div>
        </form>
      </div>
    </div>
  </div>


{{-- modal for edit --}}


<div class="modal fade bd-example-modal-lg-edit" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content edit-modal-content">

      </div>
    </div>
  </div>

<div class="container-fluid">
    <div class="card">
        <div class="container_icon card-body d-flex justify-content-end">
          <div class="delete-edit" >   
        </div>
        <div>
            <a href="#" data-toggle="modal" data-target="#exampleModal1" title="افزودن اسلایدشو">
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
    
    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-center">مدیریت اسلایدشو</h5>
                <hr>
            </div>
            <div style="overflow-x: auto;">
              <table  id="example1" class="table table-striped  table-bordered" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>ردیف</th>
                        <th>عنوان</th>
                        <th>تصویر</th>
                        <th>منتشر شود</th>
                        <th>پیش فرض</th>
                        <th>مکان</th>
                        <th>تاریخ انتشار</th>
                        <th>تاریخ انقضا</th>
                        <th>تاریخ ثبت</th>
                        <th>تاریخ ویرایش</th>     
                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($slideshows as $key=>$slideshow)

                      @php

                      if($slideshow->status==1){
                      $status = '<i style="color: green;" class="fa fa-check"></i>';
                      }else{
                      $status = '<i style="color: red;" class="fa fa-times"></i>';
                      }

                      if($slideshow->default==1){
                      $default = '<i style="color: green;" class="fa fa-check"></i>';
                      }else{
                      $default = '<i style="color: red;" class="fa fa-times"></i>';
                      }

                      @endphp
                      <tr>
                        <td>
                          <div class="custom-control custom-checkbox custom-control-inline"
                              style="margin-left: -1rem;">
                              <input data-id=" {{$slideshow->id}} " type="checkbox" id="{{ $key}}"
                                  name="customCheckboxInline1" class="custom-control-input" value="1">
                              <label class="custom-control-label" for="{{$key}}"></label>
                          </div>
                        </td>
                        <td> {{$key+1}} </td>
                        <td>{{$slideshow->title}}</td>
                        <td><img width="50" height="50" src="{{asset('uploads/'.$slideshow->image)}}"></td>
                        <td>{!! $status !!}</td>
                        <td>{!! $default !!}</td>
                        <td>{{$slideshow->place}}</td>
                        <td>{{\Morilog\Jalali\Jalalian::forge($slideshow->release)->format('%Y-%m-%d')}}</td>
                        <td>{{\Morilog\Jalali\Jalalian::forge($slideshow->expiry)->format('%Y-%m-%d')}}</td>
                        <td>{{\Morilog\Jalali\Jalalian::forge($slideshow->created_at)->format('%Y-%m-%d H:i:s')}}</td>
                        <td>{{\Morilog\Jalali\Jalalian::forge($slideshow->update_at)->format('%Y-%m-%d H:i:s')}}</td>          
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
<script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js"></script>

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


    $('.clone-neighborhood-bottom').click(function(e){
        e.preventDefault();
       let cloned =  $('.add-neighborhood').first().clone()
       cloned.find('input[type="text"]').val('')
       $('.clone-neighborhood').append(cloned)


    })


                  // edit 

$('.bd-example-modal-lg-edit').on('shown.bs.modal', function (event) {
       id = $('table input[type="checkbox"]:checked').attr('data-id')
       $.ajax({
       type:'post',
       url:'{{route("SlideShow.Edit.getData")}}',
       cache: false,
       async: true,
       data:{id:id},
       success:function(data){
          $('.edit-modal-content').html(data)
          editform= $('#edit--slideshow')
          editform.validate({
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
           }
         })
        })  

    $('.delete').click(function(e){
                e.preventDefault()
                console.log(array)

            // ajax request
                $.ajax({
                type:'post',
                url:'{{route("SlideShow.Delete")}}',
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
})
</script>
@endsection
