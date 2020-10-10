@extends('Layouts.Pannel.Template')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <form id="example-advanced-form" method="post" action="{{route('Panel.EditProduct',$product->id)}}"
                enctype="multipart/form-data">
                @csrf
                <h3>محصول</h3>
                <section>
                    <div class="row product-detail mb-2" style="position: relative;">
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">نام محصول</label>
                            <input id="product_name" class="form-control text-right" name="product_name" required
                                value="{{$product->product_name}}" type="text" dir="rtl">

                        </div>
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">قیمت محصول</label>
                            <input id="product_price" class="form-control text-right" name="product_price" required
                                value="{{$product->product_price}}" type="number" dir="rtl">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">تخفیف (درصد)</label>
                            <input id="discount" class="form-control text-right" name="discount" required type="number"
                                value="{{$product->discount}}" dir="rtl">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">تعداد</label>
                            <input id="count" class="form-control text-right" name="count" required type="number"
                                value="{{$product->count}}" dir="rtl">
                        </div>
                        @if ($product->product_picture)
                        <div class="form-group col-md-6">
                            <img style="width: 100%" src="{{asset($product->product_picture)}}" alt=""></div>
                        @endif
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">تغییر تصویر</label>
                            <input id="product_picture" class="form-control text-right" name="product_picture"
                                type="file" dir="rtl">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">تغییر دسته بندی</label>
                            <select name="category" id="category" class="form-control">
                                <option value="">باز کردن فهرست انتخاب</option>

                                @foreach (\App\Models\Category::all() as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="form-group  col-md-6 pt-4">
                                    <span>وضعیت محصول</span>
                                    <div class="">
                                        <label class="" for="">فعال</label>
                                        <input style="display:inline-block;" value="1" type="checkbox" class=""
                                            name="product_status" id="">
                                    </div>
                                </div> --}}
                        <div class="form-group col-md-12">
                            <label for="recipient-name" class="col-form-label">توضیح محصول: </label>
                            <textarea id="product_description" class="form-control text-right"
                        name="product_description" type="text" dir="rtl">{{$product->product_description}}</textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary">ویرایش</button>
                        </div>
                    </div>

                </section>
            </form>
        </div>
    </div>
</div>
</div>
@endsection