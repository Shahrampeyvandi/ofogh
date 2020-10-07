<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Services\Service;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MainController extends Controller
{
  public function index()
  {

    $header = 'مدیر';
    if (Cache::has('users')) {
      $users = User::all();
    } else {
      $users = Cache::remember('users', 60, function () {
        return User::all();
      });
    }
    return view('User.Dashboard');
  }


  public function UserList()
  {



    $users = \App\Models\User::latest()->get();



    return view('User.UsersList', compact(['users']));
  }

  public function UserOrderBy(Request $request)
  {

    if ($request->data == 'name') {
      $users = \App\Models\User::OrderBy('user_firstname', 'ASC')->get();
    }
    if ($request->data == 'lastname') {
      $users = \App\Models\User::OrderBy('user_lastname', 'ASC')->get();
    }
    if ($request->data == 'username') {
      $users = \App\Models\User::OrderBy('user_username', 'ASC')->get();
    }

    $tbody = '';
    foreach ($users as $key => $user) {
      $tbody .= '
        <tr>
        <td>
          <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
          <input data-id="' . $user->id . '" type="checkbox" id="' . $key . '" name="customCheckboxInline1" class="custom-control-input" value="1">
            <label class="custom-control-label" for="' . $key . '"></label>
          </div>
        </td>
        <td> ' . ($key + 1) . ' </td>
        <td>' . $user->user_firstname . '</td>
        <td>' . $user->user_lastname . '</td>
        <td>' . $user->user_username . '</td>
        <td>' . $user->user_responsibility . '</td>
        <td>' . $user->user_national_code . '</td>
        <td>' . $user->user_mobile . '</td>
        <td>
          ' . ($user->user_prfile_pic !== '' ?
        '<img width="75px" class="img-fluid " src="uploads/users/profile_pic/' . $user->user_national_code . '/' . $user->user_prfile_pic . ' " />'
        :
        '<img width="75px" class="img-fluid " src="Pannel/img/avatar.jpg" />') . '
          
        </td>
      </tr>

        ';
    }

    return $tbody;
  }

  public function FilterUsers(Request $request)
  {

    $users =  User::where('user_firstname', 'like', '%' . $request->word . '%')
      ->get();
    return view('User.UsersList', compact('users'));
  }

  public function SubmitUser(Request $request)
  {




    // if ($request->user_national_num !== null && User::where('user_national_code', $request->user_national_num)->first() !== null) {
    //   alert()->error('کاربر دیگری با این کد ملی ثبت نام کرده است', 'عملیات ناموفق')->autoclose(3500);
    //   return back();
    // }

    if (User::where('user_mobile', $request->user_mobile)->first() !== null) {
      alert()->error('کاربر دیگری با این شماره همراه ثبت نام کرده است', 'عملیات ناموفق')->autoclose(3500);
      return back();
    }



    $URL = public_path('user-images');

    if ($request->has('user_profile')) {
      $file = $request->user_mobile . '.' . $request->user_profile->getClientOriginalExtension();

      $request->user_profile->move($URL . '/' . $request->user_mobile, $file);
      $fileName = $request->user_mobile . '/' . $file;
    } else {
      $fileName = '';
    }

    $user = User::create([
      'user_firstname' => $request->user_name,
      'user_lastname' => $request->user_family,
      'user_username' => '',
      'user_email' => $request->user_email,
      'user_mobile' => $request->user_mobile,
      'user_password' => Hash::make($request->user_pass),
      'user_prfile_pic' => $fileName,
    ]);



    // Alert::success( 'اطلاعات با موفقیت ثبت شد','موفق')->persistent("باشه");
    alert()->success('کاربر با موفقیت ثبت شد', 'عملیات موفق')->autoclose(2000);
    return back();
  }

  public function getUserData(Request $request)
  {


    if (request()->method() == "POST") {
      $user = User::where('id', $request->user_id)->first();
    }
    if (request()->method() == "PUT") {
      $user = auth()->user()->first();
    }



    $csrf = csrf_token();
    $list = '
        <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ویرایش کاربر</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="edit-user-form" method="post" action="' . route('User.Edit.Submit') . '" enctype="multipart/form-data">
      
      <input type="hidden" name="_token" value="' . $csrf . '">
     
      <input type="hidden" name="user_id" value="' . $user->id . '">

      <div class="modal-body">
          <div class="row">
            <div class="col-md-12" style="display: flex;align-items: center;justify-content: center;">
              <div class="profile-img">
                  <div class="chose-img">
                      <input type="file" class="btn-chose-img" name="user_profile" title="نوع فایل میتواند png , jpg  باشد">
                  </div>
                  ' . ($user->user_prfile_pic !== '' && $user->user_prfile_pic !== null ?
      '<img style="border-radius: 50%;object-fit: contain; background: #fff; max-width: 100%; height: 100%; width: 100%;" src="' . route('BaseUrl') . '/uploads/brokers/' . $user->user_prfile_pic . '" alt="">
                  <p class="text-chose-img" style="position: absolute;top: 82%;left: 14%;font-size: 13px;">تغییر
                      پروفایل</p>
                  ' : '<img style="border-radius: 50%;object-fit: contain; background: #fff; max-width: 100%; height: 100%; width: 100%;" src="' . route('BaseUrl') . '/Pannel/img/temp_logo.jpg" alt="">
                  <p class="text-chose-img" style="position: absolute;top: 44%;left: 14%;font-size: 13px;">ثبت
                      پروفایل</p>
                  ') . '
                  
              </div>
          </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="user_name" class="col-form-label"><span class="text-danger">*</span> نام: </label>
              <input type="text" class="form-control" name="user_name" value="' . $user->user_firstname . '" id="user_name">
            </div>
            <div class="form-group col-md-6">
              <label for="user_family" class="col-form-label"><span class="text-danger">*</span> نام خانوادگی:</label>
              <input type="text" class="form-control" name="user_family" value="' . $user->user_lastname . '" id="user_family">
            </div>
          </div>
         ';

    $list .= ' <div class="row">
           <div class="form-group col-md-6">
             <label for="user_pass" class="col-form-label"><span class="text-danger">*</span> تغییر پسورد: </label>
             <input type="text" class="form-control" name="user_pass" id="user_passa">
           </div>
           <div class="form-group col-md-6">
             <label for="confirm_user_pass" class="col-form-label"><span class="text-danger">*</span> تکرار
               پسورد:</label>
             <input type="text" class="form-control" name="confirm_user_pass" id="confirm_user_pass">
           </div>
         </div>';


    $list .= '<div class="row">
            <div class="form-group col-md-6">
              <label for="user_email" class="col-form-label">ایمیل:</label>
              <input type="text" value="' . $user->user_email . '"  class="form-control" name="user_email" id="user_email">
            </div>
            <div class="form-group col-md-6">
              <label for="user_mobile" class="col-form-label"><span class="text-danger">*</span> موبایل:</label>
              <input type="text" disabled value="' . $user->user_mobile . '"  class="form-control"  id="user_mobile">
              <input type="hidden"  value="' . $user->user_mobile . '"  class="form-control" name="user_mobile">

              </div>
          </div>
        
         
          
          <div class="row">';

    $list .= ' </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
          <button type="submit" class="btn btn-primary">ویرایش</button>
        </div>
      </form>
        ';

    return $list;
  }

  public function SubmitUserEdit(Request $request)
  {
    $URL = public_path('user-images');
    $user = User::where('id', $request->user_id)->first();

    if ($request->has('user_profile')) {

      File::delete($URL . '/' . $user->user_prfile_pic);

      $file = $request->user_mobile . '.' . $request->user_profile->getClientOriginalExtension();

      $request->user_profile->move($URL . '/' . $request->user_mobile, $file);
      $fileName =  $request->user_mobile . '/' . $file;
      $user->user_prfile_pic = $fileName;
    }

    $user->user_firstname = $request->user_name;
    $user->user_lastname = $request->user_family;
    $user->user_username = $request->username;
    $user->user_email = $request->user_email;
    $user->user_mobile = $request->user_mobile;
    $user->user_national_code = $request->user_national_num;
    $user->user_responsibility = $request->user_responsibility;
    if (isset($request->user_pass) && $request->user_pass) {
      $user->user_password = Hash::make($request->user_pass);
    }

    $user->update();





    alert()->success('اطلاعات کاربر با موفقیت ویرایش شد ', 'عملیات موفق')->autoclose(2000);
    return back();
  }
  public function DeleteUser(Request $request)
  {


    foreach ($request->array as $user_id) {
    
      User::where('id', $user_id)->delete();
    }
    return 'success';
  }

  public function getTime()
  {
    $data = array(
      'fulldate' => date('d-m-Y H:i:s'),
      'date' => date('d'),
      'month' => date('m'),
      'year' => date('Y'),
      'hour' => date('H'),
      'minute' => date('i'),
      'second' => date('s')
    );
    return json_encode($data);
  }
}
