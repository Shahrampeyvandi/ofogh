@extends('Layouts.FrontEnd.Template')
@section('content')
<div class="container h-100-vh">
    <div class="row align-items-center h-100-vh">
        <div class="col-lg-6 d-none d-lg-block p-t-b-25">
            <img class="img-fluid" src="{{route('BaseUrl')}}/Pannel/assets/media/image/login.png" alt="...">
        </div>
        <div class="col-lg-4 offset-lg-1 p-t-b-25">
            <div class="d-flex align-items-center m-b-20">
                @if (\Session::has('error'))
                <div class="alert alert-danger text-center">
                    <p>{{ \Session::get('error') }}</p>
                </div><br />
                @endif
                {{-- <img src="assets/media/image/dark-logo.png" class="m-l-15" width="40" alt=""> --}}
                <h3 class="m-0">ایجاد حساب کاربری</h3>
            </div>
            <p>موارد زیر را تکمیل کنید</p>
            @include('FrontEnd.errors')
            @if (\Session::has('success'))
            <div class="alert alert-success text-center">
                <p>{{ \Session::get('success') }}</p>
            </div><br />
            @endif

            <form action="{{route('AddAccount')}}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group mb-4">
                    <input type="text" required class="form-control form-control-lg" name="schoolname" autofocus
                        placeholder="  نام مدرسه">
                </div>
                <select name="school_section" class="form-control custom-select  mb-3">
                    @php
                    $sections = \App\Models\SectionModel::get();
                    @endphp
                    @foreach ($sections as $item)
                    <option value=" {{$item->sections_id}} ">{{$item->sections_name}}</option>
                    @endforeach
                </select>
                <div class="form-group mb-4">
                    <input type="text" required class="form-control form-control-lg" name="fullname" autofocus
                        placeholder="  نام نام خانوادگی">
                </div>
                <div class="form-group mb-4">
                    <input type="text" id="mobile" required class="form-control form-control-lg" name="mobile"
                        placeholder="   تلفن همراه">
                    <p class="errorMassage" id="massage1"></p>
                </div>
                <div class="form-group mb-4">
                    <input type="number" required class="form-control form-control-lg" name="student_count"
                        placeholder=" تعداد دانش آموزان">
                </div>

                <div class="form-group mb-4">
                    <input type="text" required class="form-control form-control-lg" name="username"
                        placeholder="  نام کاربری">
                </div>
                <div class="form-group mb-4">
                    <input type="password" required class="form-control form-control-lg" name="password"
                        placeholder="رمز عبور">
                </div>
                <div class="form-group mb-4">
                    <label for="">لوگوی مدرسه: </label>
                    <input type="file" required class="form-control " name="school_profile"
                        >
                </div>
                <div class="form-group mb-4">
                    <textarea name="address" id="" required placeholder="آدرس" class="form-control form-control-lg"
                        cols="10"></textarea>
                </div>


                <button class="btn subForm btn-success btn-lg btn-block btn-uppercase mb-2">ثبت نام</button>

            </form>
            <hr>
            <a class="d-block" href="{{route('ForgetPassword')}}">کلمه عبورتان را فراموش کرده اید ؟</a>
            <a class="d-block my-3 " href="{{route('BaseUrl')}}">ورود به حساب کاربری</a>
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
