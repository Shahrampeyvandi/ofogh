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
          <h5 class="modal-title" id="exampleModalLabel">ثبت شهر</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <form id="create--city" action=" {{route('Pannel.City.Insert')}} " method="post">
          @csrf
        <div class="modal-body">
            <div class="form-group col-md-12">
                <label for="city_name" class="col-form-label">شهر:  </label>
                <input type="text" class="form-control"
                name="city_name"
                id="city_name">
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


  {{-- add regions --}}
  <div class="modal fade" id="addRegions" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">ثبت محدوده</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
        <form id="create--city" action=" {{route('Pannel.Neighborhood.Insert')}} " method="post">
            @csrf
          <div class="modal-body">
            <div class="form-group col-md-12">
              <label for="recipient-name" class="col-form-label">نام شهر </label>
              <select required name="city_name" class="form-control" id="exampleFormControlSelect2">
                <option value="">باز کردن فهرست انتخاب</option>
                @foreach (\App\Models\City\City::all(); as $city)
                 <option value="{{$city->id}}">{{$city->city_name}}</option>
                @endforeach
  
              </select>
            </div>
            <div class="col-md-12 form-group" >
              <label  class="col-form-label">نام ناحیه</label>
                <input type="number" class="form-control" name="region" id="">
            </div>
            <div class="col-md-12 form-group add-neighborhood" >
              <label  class="col-form-label">نام محله </label>
              <input type="text" class="form-control" name="neighborhood[]" id="">
            </div>
            <div class="clone-neighborhood">

            </div>
            <div>
              <hr>
              <a href="#" class="clone-neighborhood-bottom">جدید</a>
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
          <a href="#" class="mx-2 btn--filter"  title="فیلتر اطلاعات">
            <span class="__icon bg-info">
                <i class="fa fa-search"></i>
            </span>
        </a>
            <a href="#" data-toggle="modal" data-target="#exampleModal1" title="افزودن شهر">
                <span class="__icon bg-success">
                    <i class="fa fa-plus"></i>
                </span>
            </a>
            <a href="#" title="تازه سازی" class="mx-2" onclick="location.reload()">
                <span class="__icon bg-primary">
                    <i class="fa fa-refresh"></i>
                </span>
            </a>
            <a href="#" data-toggle="modal" data-target="#addRegions" title="افزودن محدوده">
              <span class="__icon bg-secondary" style="width:110px !important;font-size: 13px !important;">
                  افزودن محدوده
              </span>
          </a>
           </div>
        </div>
  </div>
    {{-- filtering --}}
    <div class="card filtering" style="display:none;">
      <div class="card-body">
        <div class="row " >
          <div class="form-group col-md-6">
            <label for="recipient-name" class="col-form-label">فیلتر اطلاعات براساس: </label>
            <select required name="type_send"   class="form-control" id="exampleFormControlSelect2">
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
                <h5 class="text-center">مدیریت شهر</h5>
                <hr>
            </div>
            <div style="overflow-x: auto;">
              <table  id="example1" class="table table-striped  table-bordered" >
                    <thead>
                    <tr>
                        <th></th>
                        <th>ردیف</th>
                        <th>عنوان</th>
                        <th>تاریخ ثبت</th>
                        <th>تاریخ ویرایش</th>     
                    </tr>
                    </thead>
                    <tbody>
                      @foreach ($cities as $key=>$city)
                      <tr>
                        <td>
                          <div class="custom-control custom-checkbox custom-control-inline"
                              style="margin-left: -1rem;">
                              <input data-id=" {{$city->id}} " type="checkbox" id="{{ $key}}"
                                  name="customCheckboxInline1" class="custom-control-input" value="1">
                              <label class="custom-control-label" for="{{$key}}"></label>
                          </div>
                        </td>
                        <td> {{$key+1}} </td>
                        <td>{{$city->city_name}}</td>
                        <td>{{\Morilog\Jalali\Jalalian::forge($city->created_at)->format('%d/ %m /%Y')}}</td>
                        <td>{{\Morilog\Jalali\Jalalian::forge($city->update_at)->format('%d/ %m /%Y')}}</td>          
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
       url:'{{route("City.Edit.getData")}}',
       cache: false,
       async: true,
       data:{id:id},
       success:function(data){
          $('.edit-modal-content').html(data)
          editform= $('#edit--city')
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
                url:'{{route("City.Delete")}}',
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
