@extends('Layouts.FrontEnd.Template')
@section('content')
<div class="container h-100-vh">
    <div class="row align-items-center h-100-vh">
        <div class="col-lg-6 d-none d-lg-block p-t-b-25">
            <img class="img-fluid" src="{{route('BaseUrl')}}/Pannel/assets/media/image/login.png" alt="...">
        </div>
        <div class="col-lg-4 offset-lg-1 p-t-b-25">
            <div class="d-flex align-items-center m-b-20">
                {{-- <img src="assets/media/image/dark-logo.png" class="m-l-15" width="40" alt=""> --}}
                <h3 class="m-0">فراموشی کلمه عبور   </h3>
            </div>
            <p>تلفن همراه خود را وارد کنید تا کلمه عبور جدید برای شما ارسال شود</p>
            @include('FrontEnd.errors')
            <form action="#" >
                @csrf
                <div class="form-group mb-4">
                    <input type="text" id="mobile" class="form-control form-control-lg" name="mobile" autofocus
                        placeholder="تلفن همراه ثبت شده ">
                        <p class="errorMassage" id="massage1"></p>
                </div>
                <button class="btn btn-success btn-lg btn-block btn-uppercase mb-4">ارسال</button>

            </form>
            <hr>
            <a class="d-block" href="{{route('BaseUrl')}}">ورود به حساب کاربری </a>
        </div>
    </div>
</div>
@endsection
@section('css')
<style>
    .errorMassage {
        color: #ed1636;
    }
</style>
@endsection
@section('js')
<script>
    (function () {
        'use strict';
          $('#mobile').on('change',function(e){
            
            if($(this).val().length>11 || $(this).val().length <11)
            {
                $("#massage1").html('شماره موبایل وارد شده اشتباه است');
                $('.subForm').attr("disabled", true);
                 
            }else{
                $("#massage1").html('');
                $('.subForm').attr("disabled", false);
            }
          });


          $("#mobile").change(function () {
        var VAL = this.value;
        var rgx = /(\+98|0)?9\d{9}/;
        var mobile = new RegExp(rgx);

        if (mobile.test(VAL)) {
            $("#massage1").html('');
                $('.subForm').attr("disabled", false);
        }else{
            $("#massage1").html('  شماره موبایل وارد شده اشتباه است');
                $('.subForm').attr("disabled", true);
        }
    });
})();
</script>
@endsection