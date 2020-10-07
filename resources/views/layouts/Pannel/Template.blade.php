<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token()}}">
    <title>افق آریا - فروشگاه اینترنتی</title>
    <link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/vendors/bundle.css" type="text/css">
    @yield('css')
    <link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/css/app.css" type="text/css">
    <link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/css/custom.css" type="text/css">
    <link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/vendors/select2/css/select2.min.css"
        type="text/css">
    <link rel="stylesheet"
        href="{{route('BaseUrl')}}/Pannel/assets/vendors/datepicker-jalali/bootstrap-datepicker.min.css">
    <link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/vendors/datepicker/daterangepicker.css">
    {{-- <link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/vendors/clockpicker/bootstrap-clockpicker.min.css" 
         type="text/css"> --}}
    <link rel="shortcut icon" href="{{route('BaseUrl')}}/Pannel/assets/media/image/icon.png">

    <link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/css/Style.css" type="text/css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- begin::theme color -->
    <meta name="theme-color" content="#8dc63f" />
    <!-- end::theme color -->





</head>

<body class="layout-container dark icon-side-menu" >
    @include('sweet::alert')

    <!-- begin::page loader-->
    <div class="page-">
        <div class="spinner-"></div>
       
    </div>
    <!-- end::page loader -->

   

    <!-- Pannel SideBar -->
    @include('Layouts.Pannel.SideBar')
    <!-- End Pannel SideBar -->

    <!-- Pannel NavBar -->
    @include('Layouts.Pannel.NavBar')
    <!-- End Pannel NavBar -->

    <!-- Main -->
    <main class=" main-content">
        @yield('content')
    </main>

    


    <script src="{{route('BaseUrl')}}/Pannel/assets/vendors/bundle.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/js/app.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/vendors/select2/js/select2.min.js"></script>

    <script src="{{route('BaseUrl')}}/Pannel/assets/js/examples/select2.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/vendors/datepicker-jalali/bootstrap-datepicker.min.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/vendors/datepicker-jalali/bootstrap-datepicker.fa.min.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/vendors/datepicker/daterangepicker.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/js/examples/datepicker.js"></script>
    {{-- <script src="{{route('BaseUrl')}}/Pannel/assets/js/examples/clockpicker.js"></script> --}}
    {{-- <script src="{{route('BaseUrl')}}/Pannel/assets/vendors/clockpicker/bootstrap-clockpicker.min.js"></script> --}}
    <script src="{{route('BaseUrl')}}/Pannel/assets/js/examples/sweet-alert.js"></script>

    <!-- begin::dataTable -->
    <script src="{{route('BaseUrl')}}/Pannel/assets/vendors/dataTable/jquery.dataTables.min.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/vendors/dataTable/dataTables.bootstrap4.min.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/vendors/dataTable/dataTables.responsive.min.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/js/examples/datatable.js"></script>

    <script src="{{route('BaseUrl')}}/Pannel/assets/js/jquery.validate.min.js"></script>


    @yield('js')

    <script>
// function validatePhone(event,inputtxt) {
//     var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;
//     if(!phoneno.test(inputtxt)){
//       swal("", "شماره تماس وارد شده معتبر نمیباشد", "error", {
// 			button: "باشه"
//     });
//    event.target.value = ''
    
//     }
// }
function validateMobile(event,input){
  var phoneno = /^09[0-9]{9}$/;
  if(phoneno.test(input)) {
}else{
  swal("", "شماره همراه وارد شده معتبر نمیباشد", "error", {
		button: "باشه"
     });
    event.target.value = ''
}
}

function isValidDate(event,dtValue2) {
    // your desired pattern
    
    var pattern = /^(\d{4})\/(\d{2})\/(\d{2})$/
    var m = dtValue2.match(pattern);
    if (!m){
        swal("", "تاریخ تولد صحیح نمیباشد", "error", {
			button: "باشه"
        });
        event.target.value = '';
    }
   

}
function checknationalcode(meli_code) {
  if(meli_code.length == 0){
      return 
  }  
 if (meli_code.length == 10) {
     if (meli_code == '1111111111' || meli_code == '0000000000' || meli_code == '2222222222' || meli_code == '3333333333' || meli_code == '4444444444' || meli_code == '5555555555' || meli_code == '6666666666' || meli_code == '7777777777' || meli_code == '8888888888' || meli_code == '9999999999') {
      swal("", "کد ملی وارد شده معتبر نمیباشد", "error", {
			button: "باشه"
    });
    document.getElementById("user_national_num").value = ''
     }
     c = parseInt(meli_code.charAt(9));
     n = parseInt(meli_code.charAt(0)) * 10 + parseInt(meli_code.charAt(1)) * 9 + parseInt(meli_code.charAt(2)) * 8 + parseInt(meli_code.charAt(3)) * 7 + parseInt(meli_code.charAt(4)) * 6 + parseInt(meli_code.charAt(5)) * 5 + parseInt(meli_code.charAt(6)) * 4 + parseInt(meli_code.charAt(7)) * 3 + parseInt(meli_code.charAt(8)) * 2;
     r = n - parseInt(n / 11) * 11;
     if ((r == 0 && r == c) || (r == 1 && c == 1) || (r > 1 && c == 11 - r)) {
        
     } else {
      swal("", "کد ملی وارد شده معتبر نمیباشد", "error", {
			button: "باشه"
    });
    document.getElementById("user_national_num").value = ''
     }
 } else {
  swal("", "کد ملی وارد شده معتبر نمیباشد", "error", {
			button: "باشه"
    });
    document.getElementById("user_national_num").value = ''
 }
}


var _h = 0;
var _m = 0;
var _s = 0;
$.ajax({ 
    url: '{{route("getOnlineTime")}}',
    type: 'GET',
    dataType: 'JSON', 
    cache:true,
    success: function(res) {
        var timer = setInterval(serverTime,1000);
        function serverTime(){
            h = parseInt(res.hour)+_h;
            m = parseInt(res.minute)+_m;
            s = parseInt(res.second)+_s;
            if (s>59){                  
                s=s-60;
                _s=_s-60;                   
            }
            if(s==59){
                _m++;   
            }
            if (m>59){
                m=m-60;
                _m=_m-60;                   
            }
            if(m==59&&s==59){
                _h++;   
            }   
            _s++;
            $('#server_time').html(append_zero(h)+':'+append_zero(m)+':'+append_zero(s));               }
        function append_zero(n){
            if(n<10){
                return '0'+n;
            }
            else
                return n;
        }
    }
});




$('.modal-profile').on('shown.bs.modal', function (event) {
       
       $.ajax({
       type:'put',
       url:'{{route("User.getProfile")}}',
       cache: false,
        async: true,
       
       success:function(data){
          $('.content-profile').html(data)
          editform= $('#edit--form')
          editform.validate({
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
                 
               confirm_user_pass:{
                           
                           equalTo : "#user_passa"
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
                   required: "لطفا پسورد را وارد نمایید"
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
                 user_mobile: {
                   //minlength: jQuery.format("Zip must be {0} digits in length"),
                   //maxlength: jQuery.format("Please use a {0} digit zip code"),
                   required: "لطفا شماره موبایل را وارد نمایید",
                   digits: 'شماره موبایل بایستی به صورت عددی وارد شود',
                   minlength: 'شماره موبایل بایستی 11 رقم باشد',
                   maxlength: 'شماره موبایل بایستی 11 رقم باشد',
                 }
               }
             })
           }
         })
        })  




// function display_c(){
// var refresh=1000; // Refresh rate in milli seconds
// mytime=setTimeout('display_ct()',refresh)
// }

// function display_ct() {
// var x = new Date();

// if (x.getHours() < 10 && x.getHours() > 0 ) {
//     var h = '0' + x.getHours();
// }else{
//     var h = x.getHours();
// }
// if (x.getMinutes() < 10 && x.getMinutes() > 0) {
//     var m = '0' + x.getMinutes();
// }else{
//     var m = x.getMinutes();
// }

// x1 = h + ":" + m + ":" +  x.getSeconds();
// document.getElementById('ct').innerHTML = x1;
// display_c();
//  }
$(document).ready(function(){

$.ajax({

type:'get',
url:'{{route("Pannel.Notifications.Pannel")}}',
headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
 data:{},
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

var $table = $('<table></table>');

$('#notiifcationdialog').html('');
//$('#notificontainer').html('');

if(data.length > 0){
  $('#notificontainer').append('<a href="#" class="nav-link nav-link-notify" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell"></i></a>');

  




}else{
  $('#notificontainer').append('<a href="#" class="nav-link" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-bell"></i></a>');

}

for(var i=0;i<data.length;i++) {

var id=data[i]['id'];

                 $table.append('<a id="'+id+'" href="profile.html" class="dropdown-item noti" data-toggle="modal" data-target=".modal-profile1"><i class="fa fa-envelope ml-2"></i>'+data[i]['title']+'</a>');


            



             }

             $('#notiifcationdialog').append($table);
 

             $('.noti').click(function() {
        var $id = $(this).attr('id');
        
         //inside the each loop, the 'this' keyword refers to the current <td>


         $.ajax({

type:'get',
url:'{{route("Pannel.Notifications.Pannel.Get")}}',
headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
 data:{id:$id},
// ,success:function(data){
//   swal("پرداخت با موفقیت انجام شد", {
//     icon: "success",
// 	          button: "تایید"
//        });

      
 
 success:function(data){

  var $table = $('<table></table>');
  $('#shownotif').html('');
  $table.append('<div class="row"><div class="form-group col-md-12"> <label for="recipient-name" class="col-form-label">تیتر : </label>'+data['title']+'</div></div><div class="row"><div class="form-group col-md-12"><label for="user_address" class="col-form-label"> متن اطلاعیه: </br> '+data['text']+'</label></textarea></div></div><div class="row"><div class="form-group col-md-12"><label for="link" class="col-form-label"> تاریخ: '+data['time']+'</label></div></div>');
  $('#shownotif').append($table);


}
})
        
    });

}
})
})
    </script>
    
</body>

</html>