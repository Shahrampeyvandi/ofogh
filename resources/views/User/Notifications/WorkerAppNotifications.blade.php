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

  


{{-- modal for Send --}}


<div class="modal fade" id="sendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
            موارد علامت زده شده ارسال شوند؟
          </div>
          <div class="modal-footer">
            <a type="button" class="send btn btn-danger text-white">ارسال!  </a>
          </div>

            
        </div>
    </div>
</div>

{{--model for add transaction--}}
<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ایجاد اطلاعیه جدید</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      


      <form id="tranaction--form" method="post" action=" {{route('Pannel.AppManage.WorkerApp.Notifications.Submit')}} ">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-12">
                  <label for="recipient-name" class="col-form-label">
                      تیتر :
                      <span class="text-danger">*</span> 
                  </label>
                  <input type="text" class="form-control" name="title">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="user_address" class="col-form-label"> متن اطلاعیه:
                    <span class="text-danger">*</span>  </label>
                  <textarea type="text" class="form-control" name="text">
                  </textarea>
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-12">
                  <label for="link" class="col-form-label">  لینک(حتما به صورت http://google.com باشد):
                    <span class="text-danger">*</span>  </label>
                  <input type="text" class="form-control" name="link">
                </div>
              </div>

            
              <div class="row">

              <div class="form-group col-md-3">
                <label for="group" class="col-form-label"><span class="text-danger">*</span> نمایش در اپ خدمت رسانان: </label>
                <select required name="group"  class="form-control" id="group">
                  <option value="">انتخاب کنید</option>
                  <option value="همه">همه</option>
                  <option value="کارگذاران">کارگذاران</option>

                </select>
  
            </div>

            <div class="form-group col-md-8" style="display:none;" id="rolesg">
                <label for="roles" class="col-form-label"><span class="text-danger">*</span> 
                  خدمت رسان های کارگذاری های :</label>
                  <select name="roles[]" id="roles"  class="js-example-basic-single" dir="rtl" multiple>
                    @foreach ($roles as $role)
                  <option value="{{$role->name}}" >{{$role->name}}</option>
                    @endforeach
                    
                     
                     
                  </select>
              </div>
          
              </div>

               
          
            <div class="row">
                <div class="form-group col-md-12">
                  <label for="user_address" class="col-form-label"> توضیحات: </label>
                  <textarea type="text" class="form-control" name="desc">
                  </textarea>
                </div>
              </div>
          

    </div>

          
      <!-- form-group -->

        
        
        

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
          <button type="submit" class="btn btn-primary">ایجاد اطلاعیه</button>
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
               
              @if(auth()->user()->can('notifications_add'))

                <a href="#" data-toggle="modal" data-target=".bd-example-modal-lg" title="افزودن منو">
                    <span class="__icon bg-success">
                        <i class="fa fa-plus"></i>
                    </span>
                </a>
                @endif
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
                <h5 class="text-center">نوتیفیکیشن ها</h5>
                <hr>
            </div>
            <div style="overflow-x: auto;">
                <table  id="example1" class="table table-striped  table-bordered" >
                    <thead>
                        <tr>
                            <th></th>
                            <th>ردیف</th>
                            <th>تیتر</th>
                            <th>متن</th>
                            <th>لینک</th>

                            <th>ارسال به گروه</th>
                            <th>لیست کارگذاران</th>


                        
                            <th>تاریخ ثبت</th>


                         
                        </tr>
                    </thead>
                    <tbody class="tbody">

                        @foreach($notifications as $key=>$notification)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox custom-control-inline"
                                    style="margin-left: -1rem;">
                            <input data-id="{{$notification->id}}" type="checkbox" id="{{$notification->id}}"
                                        name="customCheckboxInline1" class="custom-control-input" value="{{$notification->id}}">
                                    <label class="custom-control-label" for="{{$notification->id}}"></label>
                
                                </div>
                            </td>
                            <td>
                                {{$key+1}}
                            </td>
                            <td>
                                {{$notification->title}}
                            </td>
                            <td>
                                {{$notification->text}}
                            </td>
                            <td>
                                {{$notification->link}}
                            </td>
                            <td>
                                {{$notification->group}}
                            </td>
                            <td>
                                @if($notification->brokers)
                              @for ($i = 0; $i < count($notification->brokers); $i++)
                                  {{$notification->brokers[$i]}} ,
                              @endfor
                              @endif
                            </td>
                            <td>
                                {{\Morilog\Jalali\Jalalian::forge($notification->created_at)->format('%Y-%m-%d H:i:s')}}
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

               

                    $('.container_icon').removeClass('justify-content-end')
                    $('.container_icon').addClass('justify-content-between')
                    $('.delete-edit').html(`
                    <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="sweet-multiple mx-2">
            <span class="__icon bg-danger">
                <i class="fa fa-trash"></i>
            </span>
           </a>
           @if(auth()->user()->can('notifications_send'))
           <a href="#" title="ارسال" data-toggle="modal" data-target="#sendModal" class="mx-2" >
            <span class="__icon bg-info">
                <i class="fa fa-send"></i>
            </span>
           </a>
           @endif
                    `)
                
            }
            else{
                $('.container_icon').removeClass('justify-content-between')
                $('.container_icon').addClass('justify-content-end')
                $('.delete-edit').html('')
            }
        })
            
    })

    



$(document).on('change','#group',function(){
        var data = $(this).val();

     if(data === 'همه'){
            $('#rolesg').hide()
               }else if(data === 'کارگذاران') {
            $('#rolesg').show()
                        }else{
                            $('#roles').hide()

                        }
    


 var thiss = $(this)

})


$('.delete').click(function(e){
                e.preventDefault()
                console.log(array)

                // ajax request
 $.ajax({

                type:'post',
                url:'{{route("Pannel.AppManage.WorkerApp.Notifications.Delete")}}',
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

    $('.send').click(function(e){
                e.preventDefault()
                console.log(array)

                // ajax request
 $.ajax({

                type:'post',
                url:'{{route("Pannel.Notifications.Send")}}',
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