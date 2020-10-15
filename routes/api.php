<?php

Route::post('sendcode','Api\SMSCodeController@sendcode');


// اطلاعات مربوط به مشتری
// تایید کد پیامک شده به مشتری
Route::post('customer/verify','Api\CustomerController@verify');
// ثبت نام مشتری با اطلاعات کامل
Route::post('customer/register','Api\CustomerController@register');
// دریافت اطلاعات مشتری برای صفحه پروفایل
Route::get('customer/getData','Api\CustomerController@getCustomer');
// به روز رسانی اطلاعات پروفایل مشتری
Route::post('customer/updatedata','Api\CustomerController@updateCustomerData');
// به روز رسانی تصویر پروفایل مشتری
Route::post('customer/updateprofile','Api\CustomerController@updateProfile');
// دذریافت اطلاعات پایه ای صفحه اصلی اپ مشتری
Route::get('customer/homedetail','Api\CustomerController@getHomePageDetail');
// دریافت تمامی سفارشات مشتری
Route::get('customer/orders','Api\CustomerController@getAllOrders');
// دریافت اطلاعات کامل یک سفارش خاص برای مشتاری
Route::get('customer/order','Api\CustomerController@getOrder');
// دریافت دسته بندی ها
Route::get('categories','Api\CustomerController@getCategories');
// دریافقت لیست سرویس ها
Route::get('customer/services','Api\CustomerController@getServices');
// دریافت لیستی از دسته بندی ها به صورت ردیفی و پشت سر هم
Route::get('categoryarrange','Api\CustomerController@getCategoryArrange');
// دریافت اطلاعات اولیه صفحه اصلی اپ مشتتری
Route::get('customer/gethomep','Api\AppCustomerController@index');

// دریافت مشخصات کامل به همراه محصولات یک فروشگاه
Route::get('customer/getstore','Api\StoreController@getStore');
Route::post('getstore','Api\StoreController@getStore');
Route::post('getproduct','Api\StoreController@getProduct');

// دریافت توکن برای انتقال به صفحه پیرداخت
Route::post('pay/token','Api\PayController@gettoken');
// انتقال پول از درامد تو شارژ
Route::post('pay/intoch','Api\PayController@incometocharge');


// ثبت سفارش خرید از فروشگاه
Route::post('store/order','Api\GoodsOrderController@order');
// اپلود تصویر برای یفارش خرید
Route::post('store/uploadpic','Api\GoodsOrderController@uploadpic');
// دریافت سفار کالای مشتری
Route::get('store/orderc','Api\GoodsOrderController@getgoodsordercustomer');
// دریافت سفارشات کالالی مشتری
Route::get('store/ordersc','Api\GoodsOrderController@getallorderscustomer');
// دریافت سفارشات ارشیو شده مشتری
Route::get('store/ordersac','Api\GoodsOrderController@getgoodsordersacustomer');


// جستجوی مشتیر در میان خدمت فروشگاه و ححصول
Route::get('customer/search','Api\AppCustomerController@search');

// برررسی کد تخفیف در اپ مشتری
Route::post('customer/checkoffcode','Api\OffCodeController@firstcheckoffcode');

