<?php

use Illuminate\Support\Facades\Auth;

Route::get('/', 'Auth\LoginController@index')->name('BaseUrl');
Route::post('/Dashboard', 'Auth\LoginController@Login')->name('Pannel.Login');
Route::get('/Logout', 'Auth\LoginController@LogOut')->name('User.Logout');


Route::group(['middleware' => 'checkAdmin'], function () {

    Route::get('/dashboard', 'User\MainController@index')->name('Dashboard');
    Route::get('/product-list', 'User\StoreController@index')->name('Pannel.Services.Stores');

    Route::get('/getTime', 'User\MainController@getTime')->name('getOnlineTime');

    Route::get('/users-list', 'User\MainController@UserList')->name('Pannel.User.List');

    Route::post('UserInsert', 'User\MainController@SubmitUser')->name('User.Submit');

    Route::post('UserEdit', 'User\MainController@SubmitUserEdit')->name('User.Edit.Submit');

    Route::post('UserOrderBy', 'User\MainController@UserOrderBy')->name('User.OrderBy.Table');

    Route::post('Users/FilterData', 'User\MainController@FilterUsers')->name('Users.FilterData');

    Route::post('UserDelete', 'User\MainController@DeleteUser')->name('Users.Delete');

    Route::post('getUserData', 'User\MainController@getUserData')->name('User.Edit.getData');
    Route::put('getUserData', 'User\MainController@getUserData')->name('User.getProfile');



    Route::get('categories', 'User\ServiceCategoryController@CategoryList')->name('Pannel.Category');

    Route::post('categories', 'User\ServiceCategoryController@SubmitServiceCategory')->name('Pannel.Category');

    Route::post('categories/Delete', 'User\ServiceCategoryController@DeleteCategory')->name('Category.Delete');

    Route::post('categories/getData', 'User\ServiceCategoryController@getData')->name('category.Edit.getData');

    Route::post('editCategory/submit', 'User\ServiceCategoryController@SubmitCategoryEdit')->name('Category.Edit.Submit');


    Route::get('edit-product/{id}', 'User\StoreController@EditProduct')->name('Panel.EditProduct');
    Route::post('edit-product/{id}', 'User\StoreController@SaveEditProduct')->name('Panel.EditProduct');


    Route::post('Edit/getData', 'User\ServiceController@getData')->name('Service.Edit.getData');

    Route::post('Services/Edit/Submit', 'User\ServiceController@SubmitServiceEdit')->name('Service.Edit.Submit');

    Route::post('ServiceDelete', 'User\ServiceController@DeleteService')->name('Service.Delete');

    Route::post('Service/OrderBy', 'User\ServiceController@ServiceOrderBy')->name('Service.OrderBy.Table');

    Route::post('Service/FilterData', 'User\ServiceController@FilterServices')->name('Service.FilterData');

    Route::post('/SubmitService', 'User\ServiceController@SubmitService')->name('Service.Submit');

    Route::get('/Services/Personals', 'User\PersonalController@PersonalsList')->name('Pannel.Services.Personels');

    Route::post('Services/technician', 'User\PersonalController@technicianSubmit')->name('Service.technician.Submit');

    Route::post('Personal/FilterData', 'User\PersonalController@FilterData')->name('Personals.FilterData');

    Route::post('getPersonalData', 'User\PersonalController@getPersonalData')->name('Personal.Edit.getData');

    Route::post('Personal/Edit/Submit', 'User\PersonalController@SubmitPersonalEdit')->name('Personal.Edit.Submit');

    Route::post('Personal/CheckMobile', 'User\PersonalController@CheckMobile')->name('Personal.CheckMobile');

    Route::post('ChangeStatus', 'User\PersonalController@ChangeStatus')->name('Personal.ChangeStatus');

    Route::post('Services/Personals/N', 'User\PersonalController@CheckNationalNum')->name('Personal.CheckNationalNum');

    Route::post('Services/Personals/OrderBy', 'User\PersonalController@PersonalOrderBy')->name('Personal.OrderBy.Table');

    Route::post('Services/Personals/Delete', 'User\PersonalController@DeletePersonal')->name('Personal.Delete');

    Route::get('/Cunsomers/List', 'User\CunsomerController@CunsomerList')->name('Pannel.Cunsomers.List');

    Route::post('/Cunsomers/Delete', 'User\CunsomerController@DeleteCustomers')->name('Customers.Delete');

    Route::post('/Cunsomers/ChangeStatus', 'User\CunsomerController@ChangeStatus')->name('Customer.ChangeStatus');

    Route::post('/Cunsomers/getData', 'User\CunsomerController@getData')->name('Customer.Edit.getData');

    Route::post('/Cunsomer/Edit', 'User\CunsomerController@EditCustomer')->name('Customer.Edit.Submit');

    Route::post('Customers/OrderBy', 'User\CunsomerController@OrderBy')->name('Customers.OrderBy.Table');

    Route::post('Customers/Filter', 'User\CunsomerController@FilterCustomer')->name('Customers.Filter');

    Route::get('/Cities/List', 'User\CityController@CityList')->name('Pannel.City.List');

    Route::post('Cities/List', 'User\CityController@SubmitCity')->name('Pannel.City.Insert');

    Route::post('Cities/Neighborhood', 'User\CityController@addNeighborhood')->name('Pannel.Neighborhood.Insert');

    Route::post('Cities/Delete', 'User\CityController@DeleteCity')->name('City.Delete');

    Route::post('/Cities/getData', 'User\CityController@getData')->name('City.Edit.getData');

    Route::post('Cities/Edit', 'User\CityController@EditCity')->name('City.Edit.Insert');

    Route::get('/Services/OnlinePersonals', 'User\ServiceCategoryController@OnlinePersonals')->name('Pannel.Services.OnlinePersonals');

    Route::get('/RolesList', 'User\RoleController@RolesList')->name('Pannel.Roles');

    Route::post('/InsertRole', 'User\RoleController@InsertRole')->name('Role.Submit');

    Route::post('/DeleteRole', 'User\RoleController@DeleteRole')->name('Role.Delete');

    Route::post('/Roles/getData', 'User\RoleController@getData')->name('Roles.Edit.getData');

    Route::post('/Roles/Edit/Submit', 'User\RoleController@SubmitEditRole')->name('Roles.Edit.Submit');

    Route::get('/OrderList', 'User\OrderController@OrderList')->name('Pannel.Customers.Orders');

    Route::post('/SubmitOrder', 'User\OrderController@SubmitOrder')->name('Order.Submit');

    Route::post('/getServices', 'User\OrderController@getServices')->name('Order.Category.getService');

    Route::post('checkCustomer', 'User\OrderController@checkCustomer')->name('Order.CheckCustomer');

    Route::post('getPersonals', 'User\OrderController@getPersonals')->name('Order.Edit.getPersonals');

    Route::post('getChosenPersonal', 'User\OrderController@getChosenPersonal')->name('Order.getChosenPersonal');

    Route::post('getChosenPersonal', 'User\OrderController@getChosenPersonal')->name('Order.getChosenPersonal');

    Route::post('choisePersonal', 'User\OrderController@choisePersonal')->name('Order.ChoicePersonal');

    Route::post('ChosenPersonal', 'User\OrderController@choiseChosenPersonal')->name('Order.Choise.ChosenPersonal');

    Route::post('getDetailOrder', 'User\OrderController@getDetailOrder')->name('Order.getDetailOrder');

    Route::post('deleteOrder', 'User\OrderController@deleteOrder')->name('Order.Delete');

    Route::get('/Personals/Online', 'User\TrackPersonalController@OnlinePersonals')->name('Pannel.Personals.Online');

    Route::get('/Personals/Track', 'User\TrackPersonalController@TrackPersonals')->name('Pannel.Personals.Track');

    Route::get('/Acounting/UserAcounts/Personals', 'Acounting\UserAcountsController@personals')->name('Pannel.Acounting.PersonalAcounts');
    Route::get('/Acounting/UserAcounts/Customers', 'Acounting\UserAcountsController@customers')->name('Pannel.Acounting.CustomerAcounts');

    Route::get('/Acounting/Transactions/Personals', 'Acounting\TransactionsController@personals')->name('Pannel.Acounting.Transactions.Personals');
    Route::get('/Acounting/Transactions/Customers', 'Acounting\TransactionsController@customers')->name('Pannel.Acounting.Transactions.Customers');
    Route::POST('/Acounting/Transactions/Submit', 'Acounting\TransactionsController@submit')->name('Pannel.Acounting.Transactions.Submit');

    Route::get('/Acounting/CheckoutPersonals', 'Acounting\CheckoutPersonalsController@index')->name('Pannel.Acounting.CheckoutPersonals');
    Route::POST('/Acounting/CheckoutPersonals/Submit', 'Acounting\CheckoutPersonalsController@submit')->name('Pannel.Acounting.CheckoutPersonals.Submit');
    Route::POST('/Acounting/CheckoutPersonals/Delete', 'Acounting\CheckoutPersonalsController@delete')->name('Pannel.Acounting.CheckoutPersonals.Delete');
    Route::POST('/Acounting/CheckoutPersonals/Pay', 'Acounting\CheckoutPersonalsController@pay')->name('Pannel.Acounting.CheckoutPersonals.Pay');

    Route::get('Setting', 'User\SettingController@Setting')->name('Pannel.Setting');

    Route::post('Setting/Change', 'User\SettingController@SettingChange')->name('Pannel.Setting.Change');

    Route::get('App/Appmenu', 'App\AppMenuController@index')->name('Pannel.AppManage.Menu');
    Route::post('App/Appmenu/Submit', 'App\AppMenuController@submit')->name('Pannel.AppManage.Menu.Submit');
    Route::post('App/Appmenu/Delete', 'App\AppMenuController@delete')->name('Pannel.AppManage.Menu.Delete');

    Route::get('/Notifications', 'Notifications\NotificationsController@index')->name('Pannel.Notifications');
    Route::POST('/Notifications/Submit', 'Notifications\NotificationsController@submit')->name('Pannel.Notifications.Submit');
    Route::POST('/Notifications/Send', 'Notifications\NotificationsController@send')->name('Pannel.Notifications.Send');
    Route::POST('/Notifications/Delete', 'Notifications\NotificationsController@delete')->name('Pannel.Notifications.Delete');
    Route::get('/PannelNotifications', 'Notifications\PannelNotificationsController@index')->name('Pannel.Notifications.Pannel');
    Route::get('/PannelNotifications/Get', 'Notifications\PannelNotificationsController@get')->name('Pannel.Notifications.Pannel.Get');

    Route::get('App/WorkerappNotifications', 'Notifications\WorkerappNotificationsController@index')->name('Pannel.AppManage.WorkerApp.Notifications');
    Route::POST('App/WorkerappNotifications/Submit', 'Notifications\WorkerappNotificationsController@submit')->name('Pannel.AppManage.WorkerApp.Notifications.Submit');
    Route::POST('App/WorkerappNotifications/Delete', 'Notifications\WorkerappNotificationsController@delete')->name('Pannel.AppManage.WorkerApp.Notifications.Delete');


    Route::get('/Acounting/CheckoutPersonals/Export', 'Acounting\CheckoutPersonalsController@export')->name('Pannel.Acounting.CheckoutPersonals.Export');

    
    Route::post('/product/save', 'User\StoreController@saveproduct')->name('Pannel.Product');
    Route::post('/Services/Stores/delete', 'User\StoreController@deleteStore')->name('Stores.Delete');
    Route::put('/Services/Stores/Insert', 'User\StoreController@submitEditStore')->name('Pannel.Services.submitStore');
    Route::post('/getRegions', 'User\StoreController@getCityRegions')->name('Store.getCityRegions');
    Route::post('/getEditRegions', 'User\StoreController@getEditCityRegions')->name('Store.Edit.getCityRegions');

    Route::post('/getLocations', 'User\StoreController@getLocations')->name('Store.getLocations');

    Route::post('/getOwnerData', 'User\StoreController@getOwnerData')->name('Store.getOwnerData');

    Route::post('/getStoreData', 'User\StoreController@getStoreData')->name('Store.Edit.getData');




    Route::get('slideshow', 'App\SlideshowController@index')->name('SlideShow');
    Route::post('slideshow/store', 'App\SlideshowController@store')->name('SlideShow.store');
    Route::post('slideshow/Delete', 'App\SlideshowController@DeleteSlideshow')->name('SlideShow.Delete');
    Route::post('slideshow/getData', 'App\SlideshowController@getData')->name('SlideShow.Edit.getData');
    Route::post('slideshow/Edit', 'App\SlideshowController@EditSlideshow')->name('SlideShow.Edit.Insert');


    Route::get('goodsorders', 'Store\GoodsOrdersController@index')->name('GoodsOrders');
}); /*    E N D - R O U T E - G R O U P    */

Route::get('/pay', 'Acounting\PayController@pay')->name('Acounting.Pay');
Route::get('/payconfitm', 'Acounting\PayController@confirm')->name('Acounting.Pay.Confirm');

Route::get('/store', 'App\AppMenuController@applinkshow')->name('App.Link.Store');
