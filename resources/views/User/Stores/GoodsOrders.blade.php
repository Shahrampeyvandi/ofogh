@extends('Layouts.Pannel.Template')

@section('content')

<div class="modal fade" id="showStore" tabindex="-1" role="dialog" aria-labelledby="showStoreLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img src="" alt="" class="w-100 img-fluid">
            </div>

        </div>
    </div>
</div>
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
                @if (auth()->user()->hasRole('admin_panel'))
                <a href="#" title="حذف " data-toggle="modal" data-target="#exampleModal" class="order-delete   m-2">
                    <span class="__icon bg-danger">
                        <i class="fa fa-trash"></i>
                    </span>
                </a>
                @endif
                @if (auth()->user()->can('stores_edit'))

                <a href="#" title="ویرایش فروشگاه" data-toggle="modal" data-target=".bd-example-modal-lg-edit" class="mx-2">
                    <span class="edit-personal __icon bg-info">
                        <i class="fa fa-edit"></i>
                    </span>
                </a>
                @endif
            </div>
            <div>
                {{-- <a href="#" class="mx-2 btn--filter" title="فیلتر اطلاعات">
                    <span class="__icon bg-info">
                        <i class="fa fa-search"></i>
                    </span>
                </a> --}}
                @if (auth()->user()->can('stores_create'))
                <a href="#" data-toggle="modal" data-target=".bd-example-modal-lg" title="افزودن فروشگاه">
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
    <div class="card filtering" style="display:none;">
        <div class="card-body">
            <form action=" {{route('Personals.FilterData')}} " method="post">
                @csrf
                <div class="row ">

                    <div class="form-group col-md-6">
                        <label for="recipient-name" class="col-form-label">فیلتر اطلاعات براساس: </label>
                        <select required name="type_send" class="form-control" id="personal-filter">

                            <option value="نام">نام</option>
                            <option value="نام خانوادگی">نام خانوادگی</option>
                            <option value="وضعیت">وضعیت</option>
                            <option value="نام کاربری">نام کاربری</option>
                            <option value="کد ملی">کد ملی</option>
                            <option value="شماره موبایل">شماره موبایل</option>

                        </select>
                    </div>
                    <div class="word_field form-group col-md-6" style="display:block;">
                        <label for="recipient-name" class="col-form-label">عبارت مورد نظر: </label>
                        <input type="text" name="word" class="form-control" id="word">
                    </div>
                    <div class="status_options form-group col-md-6" style="display:none;">
                        <label for="recipient-name" class="col-form-label">وضعیت: </label>
                        <select required name="word" class="form-control" id="word">
                            <option value="فعال">فعال</option>
                            <option value="غیر فعال">غیر فعال</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">

                        <button type="submit" class="btn btn-outline-primary">جست و جو</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-title">
                <h5 class="text-center">مدیریت سفارشات کالا</h5>
                <hr>
            </div>
            <div style="overflow-x: auto;">
                <table id="example1" class="table table-striped  table-bordered">
                    <thead>
                        <tr>
                            <th></th>
                            <th>ردیف</th>
                            <th>
                                کد سفارش
                                {{-- <a href="#" data-id="name" class="store_field text-white">
                                    نام فروشگاه
                                    <i class="fa fa-angle-down"></i>
                                </a> --}}
                            </th>
                            <th>نام و نام خانوادگی مشتری</th>
                            <th>موبایل مشتری</th>
                            <th>نام فروشگاه</th>
                            <th>جمع مبلغ کالاها</th>
                            <th>
                                وضعیت
                               
                            </th>
                            <th>تاریخ انتخابی تحویل</th>
                            <th>تاریخ ثبت سفارش</th>
                          

                        </tr>
                    </thead>
                    <tbody class="tbody">
                        @foreach ($goodsorders as $key=>$goodsorder)
                        <tr>
                            <td>
                                <div class="checkstores custom-control custom-checkbox custom-control-inline"
                                    style="margin-left: -1rem;">
                                    <input data-id="{{$goodsorder->id}}" type="checkbox" id="{{ $key}}" name="checkbox"
                                        class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="{{$key}}"></label>
                                </div>
                            </td>
                            <td> {{$key+1}} </td>
                           <td>{{$goodsorder->orderuniquecode}} </td>
                           <td>
                               {{$goodsorder->cunsomers->customer_firstname}} 
                               {{$goodsorder->cunsomers->customer_lastname}} 

                            </td>
                           <td>{{$goodsorder->cunsomer_mobile}} </td>

                           <td>{{$goodsorder->store->store_name}} </td>
                           <td>{{$goodsorder->totalamountitems}} </td>
                           <td>{{$goodsorder->status}} </td>
                           <td>
                                {{\Morilog\Jalali\Jalalian::forge($goodsorder->deliverdate)->format('%B %d، %Y')}} 
                            {{$goodsorder->delivertime}}
                            </td>
                            <td>
                                {{\Morilog\Jalali\Jalalian::forge($goodsorder->created_at)->format('%B %d، %Y')}} 
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
<!-- begin::form wizard -->
<link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/vendors/form-wizard/jquery.steps.css" type="text/css">
<!-- end::form wizard -->
@endsection
@section('js')
<!-- begin::form wizard -->
<script src="{{route('BaseUrl')}}/Pannel/assets/vendors/form-wizard/jquery.steps.min.js"></script>
<script src="{{route('BaseUrl')}}/Pannel/assets/js/examples/form-wizard.js"></script>
<!-- end::form wizard -->
<script src="{{route('BaseUrl')}}/Pannel/assets/input-mask/jquery.inputmask.js"></script>
<script src="{{route('BaseUrl')}}/Pannel/assets/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="{{route('BaseUrl')}}/Pannel/assets/input-mask/jquery.inputmask.extensions.js"></script>
<script>

</script>
@endsection