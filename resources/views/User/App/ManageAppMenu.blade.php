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


{{-- modal for deleet --}}


<div class="modal fade bd-example-modal-lg-edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content edit-modal-content">
            
            
        </div>
    </div>
</div>

{{--model for add transaction--}}
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ایجاد منوی جدید</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="tranaction--form" method="post" action=" {{route('Pannel.AppManage.Menu.Submit')}} ">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-12">
                  <label for="recipient-name" class="col-form-label">عنوان منو:</label>
                  <input type="text" class="form-control" name="title">
                </div>
              </div>
          <div class="row">
            <div class="form-group col-md-5">
              <label for="user_mobile" class="col-form-label"><span class="text-danger">*</span> اولویت نمایش:</label>
              <input type="text" class="form-control" name="priority">

       
            </div>
            <div class="form-group col-md-5">
              <label for="user_name" class="col-form-label"><span class="text-danger">*</span> نوع اطلاعات: </label>
              <select required name="type"  class="form-control" id="type">
                <option value="">انتخاب کنید</option>
                <option value="دسته بندی">لیستی از زیر دسته های یک دسته بندی</option>
                <option value="خدمت های دسته">لیستی از خدمت های یک دسته بندی</option>  
                <option value="فروشگاه های دسته">لیستی از فروشگاه های یک دسته بندی</option>  
                  <option value="خدمت">لیستی از خدمت های انتخابی</option>  
                  <option value="فروشگاه">لیستی از فروشگاه های انتخابی</option>  

              </select>

                      
            </div>

            <div class="form-group col-md-2">
                <label for="user_name" class="col-form-label"><span class="text-danger">*</span> پیشنهاد ویژه: </label>
                <div class="custom-control custom-switch">
                    <input style="display:inline-block;" type="checkbox" class="custom-control-input" name="spechoffer" id="sms_status" >
                    <label class="custom-control-label" for="sms_status"></label>
                </div>
                        
              </div>

           
            
          </div>
          <div class="row">
            <div class="form-group col-md-12" id="categoryservice"  style="display:none;">
              <label for="user_family" class="col-form-label"><span class="text-danger">*</span>دسته بندی:</label>
              <select @if ($count> 1)
                size=" {{$count}} " @elseif($count > 10) size="10" @else size="2"
                @endif class="form-control" name="category" id="category">
                {!! $list !!}
              </select>
            
                    </div>
            <div class="form-group col-md-12" style="display:none;" id="service">
              <label for="user_desc" class="col-form-label"><span class="text-danger">*</span> 
                خدمت :</label>
                <select name="service[]" id="servicelist"  class="js-example-basic-single" dir="rtl" multiple>
                  @foreach ($services as $service)
                <option value="{{$service->id}}" >{{$service->service_title}}</option>
                  @endforeach
                  
                   
                   
                </select>
            </div>
          </div>
          <div class="form-group col-md-12" style="display:none;" id="store">
              <label for="user_desc" class="col-form-label"><span class="text-danger">*</span> 
                  فروشگاه:</label>
                <select name="store[]" id="storelist"  class="js-example-basic-single" dir="rtl" multiple>
                  
                    @foreach ($stores as $store)
                        
                <option value="{{$store->id}}" >{{$store->store_name}}</option>



                    @endforeach
        
                   
                </select>
          </div>
          <div class="row">
            <div class="form-group col-md-12">
              <label for="user_address" class="col-form-label"> توضیحات: </label>
              <textarea type="text" class="form-control" name="description">
              </textarea>
            </div>
          </div>

  
          
      <!-- form-group -->

        </div>
        
        

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
          <button type="submit" class="btn btn-primary">ایجاد منو</button>
        </div>
      </form>
    </div>
  </div>
</div>


{{-- modal for edit --}}

<div class="container-fluid">
    <div class="card">
        <div class="container_icon card-body d-flex justify-content-end">
            <div class="delete-edit">

            </div>
            <div>
               

                <a href="#" data-toggle="modal" data-target=".bd-example-modal-lg" title="افزودن منو">
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
                <h5 class="text-center">منو های اپلیکیشن مشتری</h5>
                <hr>
            </div>
            <div style="overflow-x: auto;">
                <table  id="example1" class="table table-striped  table-bordered" >
                    <thead>
                        <tr>
                            <th></th>
                            <th>اولویت</th>
                            <th>تیتر</th>
                            <th>نوع اطلاعات</th>

                            <th>ایتم ها</th>
                            <th>پیشنهاد ویژه</th>


                            <th>تاریخ ایجاد</th>
                        

                            <th>توضیحات</th>
                     
                         
                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach ($appmenus as $appmenu)
                            <tr>
                        <td>
                            <div class="custom-control custom-checkbox custom-control-inline"
                                style="margin-left: -1rem;">
                        <input data-id="{{$appmenu->id}}" type="checkbox" id="{{$appmenu->id}}"
                                    name="customCheckboxInline1" class="custom-control-input" value="{{$appmenu->id}}">
                                <label class="custom-control-label" for="{{$appmenu->id}}"></label>
            
                            </div>
                        </td>
 
                        <td>
                            {{$appmenu->priority}}
                        </td>

                        <td>
                            {{$appmenu->title}}
                        </td>
                        <td>
                            {{$appmenu->type}}
                        </td>
                        <td>
                            @foreach ($appmenu->item as $ite)
                                
                            {{$ite}}


                            @endforeach
                        </td>
                     
                    
                        @if ($appmenu->special_offer == 1)
                             <td class="text-success">
                                 <i class="fa fa-check"></i>
                             </td>
                             @else
                             <td class="text-danger">
                                 <i class="fa fa-close"></i>
                             </td>
                             @endif

                        <td>
                            {{\Morilog\Jalali\Jalalian::forge($appmenu->created_at)->format('%Y-%m-%d H:i:s')}}
                        </td>
                        <td>
                            {{$appmenu->description}}
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


@section('js')

<script>
    $(document).ready(function(){
 

        $('input[type="checkbox"]').change(function(){
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

           <a href="#" title="تازه سازی" data-toggle="modal" data-target=".bd-example-modal-lg-edit" class="mx-2" >
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

    

    $(document).on('change','#type',function(){
        var data = $(this).val();

     if(data === 'خدمت'){
            $('#categoryservice').hide()
            $('#store').hide()
            $('#service').show()
               }else if(data === 'فروشگاه'){
        $('#categoryservice').hide()
        $('#store').show()
            $('#service').hide()
                        }else {
                          $('#categoryservice').show()
            $('#store').hide()
            $('#service').hide()
                        }
    


 var thiss = $(this)

})


$('.delete').click(function(e){
                e.preventDefault()
                console.log(array)

                // ajax request
 $.ajax({

                type:'post',
                url:'{{route("Pannel.AppManage.Menu.Delete")}}',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
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
                //console.log(data)


                       setTimeout(()=>{
                        location.reload()
                       },1000)
               
                }
        })
    })


    })
</script>
@endsection