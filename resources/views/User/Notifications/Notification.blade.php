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
        <h5 class="modal-title" id="exampleModalLabel">ایجاد نوتیفیکشن جدید</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      
      <form id="tranaction--form" method="post" action=" {{route('Pannel.Notifications.Submit')}} ">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="form-group col-md-12">
                  <label for="recipient-name" class="col-form-label">
                      عنوان :
                      <span class="text-danger">*</span> 
                  </label>
                  <input type="text" class="form-control" name="title">
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="user_address" class="col-form-label"> متن پیام:
                    <span class="text-danger">*</span>  </label>
                  <textarea type="text" class="form-control" name="text">
                  </textarea>
                </div>
              </div>

              <div class="row">

                <div class="form-group col-md-2">
                  <label for="datesend" class="col-form-label">زمان بندی ارسال : </label>
                  <input type="text" id="datesend" name="datesend"
                  autocomplete="off"
                  class="form-control text-right date-picker-shamsi"
                   dir="ltr">
    
              </div>

              <div class="form-group col-md-2">
                <label for="timesend" class="col-form-label">ساعت : </label>
                <select name="timesend"  class="form-control" id="timesend">
                  @for($x = 8; $x <= 22; $x++)
                <option value="{{$x}}">{{$x}}</option>
                  @endfor
                


                </select>
  
            </div>

              <div class="form-group col-md-3">
                <label for="user_name" class="col-form-label"><span class="text-danger">*</span> به : </label>
                <select required name="to"  class="form-control" id="to">
                  <option value="">انتخاب کنید</option>
                  <option value="مشتری ها">مشتری ها</option>
                  <option value="خدمت رسان ها">خدمت رسان ها</option>
                  <option value="کاربران پنل">کاربران پنل</option>

                </select>
  
            </div>
            <div class="form-group col-md-3">
                <label for="user_name" class="col-form-label"><span class="text-danger">*</span> از طریق : </label>
                <select required name="how"  class="form-control" id="how">
                  <option value="">انتخاب کنید</option>
                  <option value="پیامک"> پیامک</option>
                  <option value="نوتیفیکیشن">نوتیفیکشن</option>
                  <option value="هردو">هردو</option>
                  <option value="نوتیفیکیشن پنل">نوتیفیکیشن پنل</option>


                </select>
  
            </div>
            <div class="form-group col-md-2" id="smstemplate" style="display:none;">
                <label for="user_name" class="col-form-label"><span class="text-danger">*</span> کد قالب پیامک  : </label>
                <input type="text" class="form-control" name="smstemplate">

  
            </div>
              </div>

               
          <div class="row">
            <div class="form-group col-md-12" style="display:none;" id="cunsomers">
              <label for="user_desc" class="col-form-label"><span class="text-danger">*</span> 
                مشتری ها :</label>
                <select name="cunsomers[]" id="consomers"  class="js-example-basic-single" dir="rtl" multiple>
                    <option value="0" >همه</option>
                  @foreach ($cunsomers as $cunsomer)
                <option value="{{$cunsomer->id}}" >{{$cunsomer->customer_firstname}} {{$cunsomer->customer_lastname}} {{$cunsomer->customer_mobile}}</option>
                  @endforeach
                  
                   
                   
                </select>
            </div>
          <div class="form-group col-md-12" style="display:none;" id="personals">
              <label for="user_desc" class="col-form-label"><span class="text-danger">*</span> 
                  خدمت رسان ها:</label>
                <select name="personals[]" id="personals"  class="js-example-basic-single" dir="rtl" multiple>
                    <option value="0" >همه</option>

                    @foreach ($personals as $personal)
                        
                <option value="{{$personal->id}}" >{{$personal->personal_firstname}} {{$personal->personal_lastname}} {{$personal->personal_mobile}}</option>



                    @endforeach
        
                   
                </select>
          </div>
          <div class="form-group col-md-12" style="display:none;" id="users">
            <label for="users" class="col-form-label"><span class="text-danger">*</span> 
              کاربران ها :</label>
              <select name="users[]" id="users"  class="js-example-basic-single" dir="rtl" multiple>
                  <option value="0" >همه</option>
                @foreach ($users as $user)
              <option value="{{$user->id}}" >{{$user->user_firstname}} {{$user->user_lastname}} {{$user->user_mobile}}</option>
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

  
          
      <!-- form-group -->

        </div>
        
        

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
          <button type="submit" class="btn btn-primary">ایجاد نوتیفیکیشن</button>
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
                            <th>نام</th>
                            <th>متن</th>

                            <th>ارسال به گروه</th>
                            <th>به صورت</th>


                            <th>لیست</th>
                        

                            <th>قالب پیامک</th>
                            <th>تاریخ ارسال</th>
                            <th>وضعیت ارسال</th>
                            <th>توضیحات</th>
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
                                {{$notification->to}}
                            </td>
                            <td>
                                {{$notification->how}}
                            </td>
                            <td>
                              @foreach ($notification->list as $item)
                              {{$item}},
                              @endforeach
                            </td>
                            <td>
                                {{$notification->smstemplate}}
                            </td>

                            <td>

                            @if($notification->send)
                            {{\Morilog\Jalali\Jalalian::forge($notification->send)->format('%Y-%m-%d H:i:s')}}
                            @else

                            ارسال نشده

                            @endif
                            </td>
                            <td>

                              @if($notification->sent==1)

                              ارسال شده

                              @else
  
                              ارسال نشده
  
                              @endif
                              </td>

                            <td>
                                {{$notification->desc}}
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

    

    $(document).on('change','#to',function(){
        var data = $(this).val();

     if(data === 'مشتری ها'){
            $('#personals').hide()
            $('#users').hide()
            $('#cunsomers').show()
               }else if(data === 'خدمت رسان ها'){
        $('#cunsomers').hide()
        $('#users').hide()
        $('#personals').show()
                        }else if(data === 'کاربران پنل'){
           $('#cunsomers').hide()
        $('#users').show()
        $('#personals').hide()
                        }else {
            $('#cunsomers').hide()
            $('#personals').hide()
            $('#users').hide()

                        }
    


 var thiss = $(this)

})


$(document).on('change','#how',function(){
        var data = $(this).val();

     if(data === 'پیامک' || data ==='هردو'){
            $('#smstemplate').show()
               }else {
            $('#smstemplate').hide()
                        }
    


 var thiss = $(this)

})


$('.delete').click(function(e){
                e.preventDefault()
                console.log(array)

                // ajax request
 $.ajax({

                type:'post',
                url:'{{route("Pannel.Notifications.Delete")}}',
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