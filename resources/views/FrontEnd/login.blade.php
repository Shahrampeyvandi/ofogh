<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>افق آریا - ورود</title>

    <!-- begin::global styles -->
    <link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/vendors/bundle.css" type="text/css">
    <!-- end::global styles -->

    <!-- begin::custom styles -->
    <link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/css/app.css" type="text/css">
    <!-- end::custom styles -->

	<!-- begin::favicon -->
	<link rel="shortcut icon" href="{{route('BaseUrl')}}/Pannel/assets/media/image/favicon.png">
	<!-- end::favicon -->

	<!-- begin::theme color -->
	<meta name="theme-color" content="#3f51b5" />
	<!-- end::theme color -->

</head>
<body class="bg-white h-100-vh p-t-0">

<!-- begin::page loader-->
<div class="page-">
    <div class="spinner-"></div>
 
</div>
<!-- end::page loader -->

<div class="container h-100-vh">
    <div class="row align-items-center h-100-vh">
        <div class="col-lg-6 d-none d-lg-block p-t-b-25">
            <img class="img-fluid" src="{{route('BaseUrl')}}/Pannel/img/undraw_empty_cart_co35.svg" alt="...">
        </div>
        <div class="col-lg-4 offset-lg-1 p-t-b-25">
            <div>
                @include('Errors.ValidationErrors')
            </div>
            <div class="d-flex align-items-center m-b-20">
                <h3 class="m-0">پنل مدیریت افق آریا</h3>
            </div>
            <p>برای ادامه وارد شوید.</p>
            <form action=" {{route('Pannel.Login')}} " method="post">
                @csrf
                <div class="form-group mb-4">
                    <input type="text" name="username" class="form-control form-control-lg" id="exampleInputEmail1" autofocus placeholder="  نام کاربری">
                </div>
                <div class="form-group mb-4">
                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder="رمز عبور">
                </div>
                <button type="submit" class="btn btn-primary btn-lg btn-block btn-uppercase mb-4">ورود</button>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="rememberme" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">به خاطر سپاری</label>
                    </div>
                    <a href="#" class="auth-link text-black">فراموشی رمز عبور؟</a>
                </div>
               
               
            </form>
        </div>
    </div>
</div>

<!-- begin::global scripts -->
<script src="{{route('BaseUrl')}}/Pannel/assets/vendors/bundle.js"></script>
<!-- end::global scripts -->

<!-- begin::custom scripts -->
<script src="{{route('BaseUrl')}}/Pannel/assets/js/app.js"></script>
<!-- end::custom scripts -->

</body>
</html>