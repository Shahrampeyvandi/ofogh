<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\Personals\Personal;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Acounting\UserAcounts;
use App\Models\Services\Service;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;
use Spatie\Permission\Models\Role;

class PersonalController extends Controller
{
    public function PersonalsList()
    {
        
        
        $personal_array = [];
        $personals = '';
        if (auth()->user()->hasRole('admin_panel')) {
            $personal_get = Personal::latest()->get();
            foreach ($personal_get as $key => $personal) {
               
                $personals .= ' 
            <tr>
                <td>
                    <div class="checkpersonal custom-control custom-checkbox custom-control-inline"
                        style="margin-left: -1rem;">
                        <input data-id="' . $personal->id . '" type="checkbox" id="' . $key . '"
                            name="checkbox" class="custom-control-input" value="1">
                        <label class="custom-control-label" for="' . $key . '"></label>
                    </div>
                </td>
                <td>' . ($key + 1) . '</td>
                <td>' . $personal->personal_firstname . '</td>
                <td>' . $personal->personal_lastname . '</td>
                <td>'
                    . ($personal->personal_mobile ?
                        $personal->personal_mobile
                        :
                        'وارد نشده') .
                    '</td>'
                    . ($personal->personal_status == 1 ?
                        '<td  class="status_show text-success">
                 <div class="form-group" style="display:inline-block;" >
                                 <div class="custom-control custom-switch custom-checkbox-success">
                                     <input data-id="' . $personal->id . '" type="checkbox" value="1" class="custom-control-input" id="status_' . $key . '" checked>
                                     <label class="custom-control-label" for="status_' . $key . '"></label>
                                 </div>
                        </div>
                </td>'
                        :
                        '<td  class="status_show text-danger">
                <div class="form-group" style="display:inline-block;" >
                <div class="custom-control custom-switch custom-checkbox-success">
                    <input type="checkbox" data-id="' . $personal->id . '" value="1" class="custom-control-input" id="status_' . $key . '" >
                    <label class="custom-control-label" for="status_' . $key . '"></label>
                </div>
            </div>
                </td>') . '
                <td>مرد</td>
                <td>' . $personal->personal_marriage . '</td>
                <td>' . $personal->personal_last_diploma . '</td>
                <td>
                     ' . ($personal->personal_home_phone ?
                        $personal->personal_home_phone
                        :
                        'وارد نشده') . '
                </td>
                <td>'
                    . ($personal->personal_office_phone ?
                        $personal->personal_office_phone :

                        'وارد نشده') .
                    '</td>
                <td>' . Jalalian::forge($personal->created_at)->format('%Y/%m/%d') . '</td>
                <td>'
                    . ($personal->personal_profile !== '' && $personal->personal_profile !== null ?
                        '<a href="#" data-toggle="modal" data-target="#showProfile" class="show-profile-btn"><img style="width:80px;" src="' . route('BaseUrl') . '/uploads/' . $personal->personal_profile . '"  /></a>'
                        :
                        '<img style="width:80px;" src="' . route('BaseUrl') . '/Pannel/img/avatar.jpg" />') .
                    '</td>
            </tr>';
            }
        } else {
            foreach (auth()->user()->roles as $key => $role) {
                if ($role->broker == 1) {
                    foreach (Service::where('service_role',$role->name)->get() as $key => $service) {
                        foreach ($service->personal as $key => $personal) {
                            $personals .= ' 
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox custom-control-inline"
                                    style="margin-left: -1rem;">
                                    <input data-id="' . $personal->id . '" type="checkbox" id="' . $key . '"
                                        name="customCheckboxInline1" class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="' . $key . '"></label>
                                </div>
                            </td>
                            <td>' . ($key + 1) . '</td>
                            <td>' . $personal->personal_firstname . '</td>
                            <td>' . $personal->personal_lastname . '</td>
    
                            <td>'
                                . ($personal->personal_mobile ?
                                    $personal->personal_mobile

                                    :
                                    'وارد نشده') .
                                '</td>'
                                . ($personal->personal_status == 1 ?
                                    '<td class="status_show text-success">
                                <i class="fa fa-check"></i>
                            </td>'
                                    :

                                    '<td class="status_show text-danger">
                                <i class="fa fa-close"></i>
                            </td>') . '
                            <td>مرد</td>
                            <td>' . $personal->personal_marriage . '</td>
                            <td>' . $personal->personal_last_diploma . '</td>
                            <td>
                                 ' . ($personal->personal_home_phone ?
                                    $personal->personal_home_phone
                                    :
                                    'وارد نشده') . '
                            </td>
                            <td>'
                                . ($personal->personal_office_phone ?
                                    $personal->personal_office_phone :

                                    'وارد نشده') .
                                '</td>
                                <td>' . Jalalian::forge($personal->created_at)->format('%Y/%m/%d') . '</td>

                            <td>'
                                . ($personal->personal_profile !== '' ?
                                '<a href="#" data-toggle="modal" data-target="#showProfile" class="show-profile-btn"><img style="width:80px;" src="' . route('BaseUrl') . '/uploads/' . $personal->personal_profile . '"  /></a>'
                                :
                                    '<img style="width:80px;" src="' . route('BaseUrl') . '/Pannel/img/avatar.jpg" />') .
                                '</td>
                        </tr>';
                        }
                    }
                } else {
                    $role_name = Role::where('id', $role->sub_broker)->first()->name;
                    $user = User::whereHas('roles', function ($q) use ($role_name) {
                        $q->where('name', $role_name);
                    })->first();

                    foreach (Service::where('service_role',$role_name)->get() as $key => $service) {
                        foreach ($service->personal as $key => $personal) {
                            $personals .= ' 
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox custom-control-inline"
                            style="margin-left: -1rem;">
                            <input data-id="' . $personal->id . '" type="checkbox" id="' . $key . '"
                                name="customCheckboxInline1" class="custom-control-input" value="1">
                            <label class="custom-control-label" for="' . $key . '"></label>
                        </div>
                    </td>
                    <td>' . ($key + 1) . '</td>
                    <td>' . $personal->personal_firstname . '</td>
                    <td>' . $personal->personal_lastname . '</td>
    
                    <td>'
                                . ($personal->personal_mobile ?
                                    $personal->personal_mobile
                                    :
                                    'وارد نشده') .
                                '</td>'
                                . ($personal->personal_status == 1 ?
                                    '<td class="text-success">
                        <i class="fa fa-check"></i>
                    </td>'
                                    :
                                    '<td class="text-danger">
                        <i class="fa fa-close"></i>
                    </td>') . '
                    <td>مرد</td>
                    <td>' . $personal->personal_marriage . '</td>
                    <td>' . $personal->personal_last_diploma . '</td>
                    <td>
                         ' . ($personal->personal_home_phone ?
                                    $personal->personal_home_phone
                                    :
                                    'وارد نشده') . '
                    </td>
                    <td>'
                                . ($personal->personal_office_phone ?
                                    $personal->personal_office_phone :

                                    'وارد نشده') .
                                '</td>
                                <td>' . Jalalian::forge($personal->created_at)->format('%Y/%m/%d') . '</td>

                    <td>'
                                . ($personal->personal_profile !== '' ?
                                '<a href="#" data-toggle="modal" data-target="#showProfile" class="show-profile-btn"><img style="width:80px;" src="' . route('BaseUrl') . '/uploads/' . $personal->personal_profile . '"  /></a>'
                                :
                                    '<img style="width:80px;" src="' . route('BaseUrl') . '/Pannel/img/avatar.jpg" />') .

                                '</td>
                </tr>';
                        }
                    }
                }
            }
        }
        return view('User.PersonalsList', compact('personals'));
    }

    public function technicianSubmit(Request $request)
    {

        if ($request->has('personal_profile')) {
            $file = 'photo' . '.' . $request->personal_profile->getClientOriginalExtension();
            $request->personal_profile->move(public_path('uploads/personals/' . $request->mobile), $file);
            $personal_profile = 'personals/' . $request->mobile . '/' . $file;
        } else {
            $personal_profile = '';
        }
        if ($request->has('first_page_certificate')) {
            $first_page = 'first_page' . '.' . $request->first_page_certificate->getClientOriginalExtension();
            $request->first_page_certificate->move(public_path('uploads/personals/' . $request->mobile), $first_page);
            $first_page_certificate = 'personals/' . $request->mobile . '/' . $first_page;
        } else {
            $first_page_certificate = '';
        }

        if ($request->has('card_Service')) {
            $card = 'duty_status' . '.' . $request->card_Service->getClientOriginalExtension();
            $request->card_Service->move(public_path('uploads/personals/' . $request->mobile), $card);
            $card_Service = 'personals/' . $request->mobile . '/' . $card;
        } else {
            $card_Service = '';
        }
        if ($request->has('backgrounds_status')) {
            $antecedent = 'antecedent_report_card' . '.' . $request->antecedent_report_card->getClientOriginalExtension();
            $request->antecedent_report_card->move(public_path('uploads/personals/' . $request->mobile), $antecedent);
            $antecedent_report_card = 'personals/' . $request->mobile . '/' . $antecedent;
        } else {
            $antecedent_report_card = '';
        }
        if ($request->has('second_page_certificate')) {
            $second_page = 'second_page' . '.' . $request->second_page_certificate->getClientOriginalExtension();
            $request->second_page_certificate->move(public_path('uploads/personals/' . $request->mobile), $second_page);
            $second_page_certificate = 'personals/' . $request->mobile . '/' . $second_page;
        } else {
            $second_page_certificate = '';
        }
        if ($request->has('national_card_front_pic')) {
            $national_front_pic = 'national_card_front_pic' . '.' . $request->national_card_front_pic->getClientOriginalExtension();
            $request->national_card_front_pic->move(public_path('uploads/personals/' . $request->mobile), $national_front_pic);
            $national_card_front_pic = 'personals/' . $request->mobile . '/' . $national_front_pic;
        } else {
            $national_card_front_pic = '';
        }
        if ($request->has('national_card_back_pic')) {
            $national_back_pic = 'first_page' . '.' . $request->national_card_back_pic->getClientOriginalExtension();
            $request->national_card_back_pic->move(public_path('uploads/personals/' . $request->mobile), $national_back_pic);
            $national_card_back_pic = 'personals/' . $request->mobile . '/' . $national_back_pic;
        } else {
            $national_card_back_pic = '';
        }
        if ($request->has('technical_credential')) {
            $technical = 'technical_credential' . '.' . $request->technical_credential->getClientOriginalExtension();
            $request->technical_credential->move(public_path('uploads/personals/' . $request->mobile), $technical);
            $technical_credential = 'personals/' . $request->mobile . '/' . $technical;
        } else {
            $technical_credential = '';
        }
        if ($request->has('expert_credential')) {
            $expert = 'expert_credential' . '.' . $request->expert_credential->getClientOriginalExtension();
            $request->expert_credential->move(public_path('uploads/personals/' . $request->mobile), $expert);
            $expert_credential = 'personals/' . $request->mobile . '/' . $expert;
        } else {
            $expert_credential = '';
        }

        $personal = Personal::create([
            'personal_status' => 1,
            'personal_firstname' => $request->firstname,
            'personal_lastname' => $request->lastname,
            'personal_birthday' => $this->convertDate($request->birth_year)->toDateString(),
            'personal_national_code' => $request->national_num,
            'personal_marriage' => $request->marriage_status,
            'personal_last_diploma' => $request->education_status,
            'personal_mobile' => $request->mobile,
            'personal_city' => $request->city,
            'personal_postal_code' => $request->postal_code,
            'personal_address' => $request->address,
            'personal_home_phone' => $request->tel_home,
            'personal_office_phone' => $request->tel_work,
            'personal_responsibility' => $request->postal_code,
            'technical_credential' => $technical_credential,
            'expert_credential' => $expert_credential,
            'personal_identity_card_first_pic' => $first_page_certificate,
            'personal_identity_card_second_pic' => $second_page_certificate,
            'personal_status_duty' => $card_Service,
            'personal_backgrounds_status' => $antecedent_report_card,
            'personal_national_card_front_pic' => $national_card_front_pic,
            'personal_national_card_back_pic' => $national_card_back_pic,
            'personal_about_specialization' => $request->about_specialization,
            'personal_work_experience_month' => $request->work_experience_month_num,
            'personal_work_experience_year' => $request->work_experience_year_num,
            'personal_profile' => $personal_profile
        ]);

        if ($request->has('service')) {

            foreach ($request->service as $key => $service) {
                if (!array_key_exists(2, $service) and !array_key_exists(3, $service)) {

                    $personal->services()->attach($service[1]);
                }
                if (array_key_exists(2, $service) and !array_key_exists(3, $service)) {

                    $personal->services()->attach($service[1], ['personal_chosen_status' => $service[2], 'personal_confirmed_services' => null]);
                }
                if (!array_key_exists(2, $service) and array_key_exists(3, $service)) {

                    $personal->services()->attach($service[1], ['personal_chosen_status' => null, 'personal_confirmed_services' => $service[3]]);
                }
                if (array_key_exists(2, $service) and array_key_exists(3, $service)) {

                    $personal->services()->attach($service[1], ['personal_chosen_status' => $service[2], 'personal_confirmed_services' => $service[3]]);
                }
            }
        }


        $acountcharge = new UserAcounts();

        $acountcharge->user = 'خدمت رسان';
        $acountcharge->type = 'شارژ';
        $acountcharge->cash = 0;
        $acountcharge->personal_id = $personal->id;


        $acountencome = new UserAcounts();

        $acountencome->user = 'خدمت رسان';
        $acountencome->type = 'درآمد';
        $acountencome->cash = 0;
        $acountencome->personal_id = $personal->id;

     
        $acountcharge->save();
        $acountencome->save();

        // Alert::success( 'اطلاعات با موفقیت ثبت شد','موفق')->persistent("باشه");
        alert()->success('خدمت رسان با موفقیت ثبت شد', 'عملیات موفق')->autoclose(2000);
        return back();
    }
    public function ChangeStatus(Request $request)
    {

        if ($request->value == '1') {
            Personal::where('id', $request->id)->update([
                'personal_status' => 1
            ]);
        }

        if ($request->value == '0') {
            Personal::where('id', $request->id)->update([
                'personal_status' => 0
            ]);
        }

        return $request->value;
    }

    public function DeletePersonal(Request $request)
    {

        foreach ($request->array as $personal_id) {
            Personal::find($personal_id)->services()->detach();
            Personal::find($personal_id)->useracounts()->delete();
            Personal::where('id', $personal_id)->delete();
        }
        return 'success';
    }

    public function FilterData(Request $request)
    {
        $personal_array = [];
        $personals = '';
        if (auth()->user()->hasRole('admin_panel')) {
            if ($request->type_send == 'وضعیت') {
                if ($request->word == 'فعال') {
                    $personal_get =  Personal::where('personal_status', 1)
                        ->get();
                }
                if ($request->word == 'غیر فعال') {
                    $personal_get =  Personal::where('personal_status', 0)
                        ->get();
                }
            }


            foreach ($personal_get as $key => $personal) {
                $personals .= ' 
            <tr>
                <td>
                    <div class="checkpersonal custom-control custom-checkbox custom-control-inline"
                        style="margin-left: -1rem;">
                        <input data-id="' . $personal->id . '" type="checkbox" id="' . $key . '"
                            name="customCheckboxInline1" class="custom-control-input" value="1">
                        <label class="custom-control-label" for="' . $key . '"></label>
                    </div>
                </td>
                <td>' . ($key + 1) . '</td>
                <td>' . $personal->personal_firstname . '</td>
                <td>' . $personal->personal_lastname . '</td>

                <td>'
                    . ($personal->personal_mobile ?
                        $personal->personal_mobile

                        :
                        'وارد نشده') .
                    '</td>'
                    . ($personal->personal_status == 1 ?
                        '<td  class="status_show text-success">
                
                 <div class="form-group" style="display:inline-block;" >
                                 <div class="custom-control custom-switch custom-checkbox-success">
                                     <input data-id="' . $personal->id . '" type="checkbox" value="1" class="custom-control-input" id="status_' . $key . '" checked>
                                     <label class="custom-control-label" for="status_' . $key . '"></label>
                                 </div>
                        </div>
                </td>'
                        :

                        '<td  class="status_show text-danger">
                <div class="form-group" style="display:inline-block;" >
                <div class="custom-control custom-switch custom-checkbox-success">
                    <input type="checkbox" data-id="' . $personal->id . '" value="1" class="custom-control-input" id="status_' . $key . '" >
                    <label class="custom-control-label" for="status_' . $key . '"></label>
                </div>
            </div>
                </td>') . '
                <td>مرد</td>
                <td>' . $personal->personal_marriage . '</td>
                <td>' . $personal->personal_last_diploma . '</td>
                <td>
                     ' . ($personal->personal_home_phone ?
                        $personal->personal_home_phone
                        :
                        'وارد نشده') . '
                </td>
                <td>'
                    . ($personal->personal_office_phone ?
                        $personal->personal_office_phone :

                        'وارد نشده') .
                    '</td>
                <td>' . Jalalian::forge($personal->created_at)->format('%Y/%m/%d') . '</td>
                <td>'
                    . ($personal->personal_profile !== '' && $personal->personal_profile !== null ?
                        '<img style="width:80px;" src="' . route('BaseUrl') . '/uploads/personals/' . $personal->personal_profile . '"  />'
                        :
                        '<img style="width:80px;" src="' . route('BaseUrl') . '/Pannel/img/avatar.jpg" />') .
                    '</td>
            </tr>';
            }
        } else {
            foreach (auth()->user()->roles as $key => $role) {
                if ($role->broker == 1) {
                    foreach (auth()->user()->services as $key => $service) {
                        foreach ($service->personal->where('personal_status', 1) as $key => $personal) {
                            $personals .= ' 
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox custom-control-inline"
                                    style="margin-left: -1rem;">
                                    <input data-id="' . $personal->id . '" type="checkbox" id="' . $key . '"
                                        name="customCheckboxInline1" class="custom-control-input" value="1">
                                    <label class="custom-control-label" for="' . $key . '"></label>
                                </div>
                            </td>
                            <td>' . ($key + 1) . '</td>
                            <td>' . $personal->personal_firstname . '</td>
                            <td>' . $personal->personal_lastname . '</td>
    
                            <td>'
                                . ($personal->personal_mobile ?
                                    $personal->personal_mobile

                                    :
                                    'وارد نشده') .
                                '</td>'
                                . ($personal->personal_status == 1 ?
                                    '<td class="status_show text-success">
                                <i class="fa fa-check"></i>
                            </td>'
                                    :

                                    '<td class="status_show text-danger">
                                <i class="fa fa-close"></i>
                            </td>') . '
                            <td>مرد</td>
                            <td>' . $personal->personal_marriage . '</td>
                            <td>' . $personal->personal_last_diploma . '</td>
                            <td>
                                 ' . ($personal->personal_home_phone ?
                                    $personal->personal_home_phone
                                    :
                                    'وارد نشده') . '
                            </td>
                            <td>'
                                . ($personal->personal_office_phone ?
                                    $personal->personal_office_phone :

                                    'وارد نشده') .
                                '</td>
                            <td>' . Jalalian::forge($personal->created_at)->format('%Y/%m/%d') . '</td>
                            <td>'
                                . ($personal->personal_profile !== '' && $personal->personal_profile !== null ?
                                    '<img style="width:80px;" src="' . route('BaseUrl') . '/uploads/personals/' . $personal->personal_profile . '"  />'
                                    :
                                    '<img style="width:80px;" src="' . route('BaseUrl') . '/Pannel/img/avatar.jpg" />') .
                                '</td>
                        </tr>';
                        }
                    }
                } else {
                    $role_name = Role::where('id', $role->sub_broker)->first()->name;
                    $user = User::whereHas('roles', function ($q) use ($role_name) {
                        $q->where('name', $role_name);
                    })->first();

                    foreach ($user->services as $key => $service) {
                        foreach ($service->personal->where('personal_status', 1) as $key => $personal) {
                            $personals .= ' 
                <tr>
                    <td>
                        <div class="custom-control custom-checkbox custom-control-inline"
                            style="margin-left: -1rem;">
                            <input data-id="' . $personal->id . '" type="checkbox" id="' . $key . '"
                                name="customCheckboxInline1" class="custom-control-input" value="1">
                            <label class="custom-control-label" for="' . $key . '"></label>
                        </div>
                    </td>
                    <td>' . ($key + 1) . '</td>
                    <td>' . $personal->personal_firstname . '</td>
                    <td>' . $personal->personal_lastname . '</td>
    
                    <td>'
                                . ($personal->personal_mobile ?
                                    $personal->personal_mobile

                                    :
                                    'وارد نشده') .
                                '</td>'
                                . ($personal->personal_status == 1 ?
                                    '<td class="text-success">
                        <i class="fa fa-check"></i>
                    </td>'
                                    :

                                    '<td class="text-danger">
                        <i class="fa fa-close"></i>
                    </td>') . '
                    <td>مرد</td>
                    <td>' . $personal->personal_marriage . '</td>
                    <td>' . $personal->personal_last_diploma . '</td>
                    <td>
                         ' . ($personal->personal_home_phone ?
                                    $personal->personal_home_phone
                                    :
                                    'وارد نشده') . '
                    </td>
                    <td>'
                                . ($personal->personal_office_phone ?
                                    $personal->personal_office_phone :

                                    'وارد نشده') .
                                '</td>
                    <td>' . Jalalian::forge($personal->created_at)->format('%Y/%m/%d') . '</td>
                    <td>'
                                . ($personal->personal_profile !== '' && $personal->personal_profile !== null ?
                                    '<img style="width:80px;" src="' . route('BaseUrl') . '/uploads/personals/' . $personal->personal_profile . '"  />'
                                    :
                                    '<img style="width:80px;" src="' . route('BaseUrl') . '/Pannel/img/avatar.jpg" />') .
                                '</td>
                </tr>';
                        }
                    }
                }
            }
        }
        return view('User.PersonalsList', compact('personals'));
    }

    public function CheckMobile(Request $request)
    {

        $personal =  Personal::where('personal_mobile', $request->data)->count();
        if ($personal) {
            return ['error' => 'کاربر با این شماره تلفن از قبل ثبت شده است'];
        }
    }

    public function getPersonalData(Request $request)
    {

        $personal = Personal::where('id', $request->personal_id)->first();

        $csrf = csrf_token();
        $list = '
        <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ویرایش کاربر</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
        </div>
        <div class="modal-body">
        <div id="wizard2">
            <form id="example-advanced-form1" method="post" action="' . route('Personal.Edit.Submit') . '"
                enctype="multipart/form-data">
                <input type="hidden" name="_token" value="' . $csrf . '">
                <input type="hidden" name="personal_id" value="' . $personal->id . '">
                <h3>مشخصات فردی</h3>
                <section>
                <div class="row">
                <div class="col-md-12" style="display: flex;align-items: center;justify-content: center;">
                  <div class="profile-img">
                      <div class="chose-img">
                          <input type="file" class="btn-chose-img" name="personal_profile" title="نوع فایل میتواند png , jpg  باشد">
                      </div>
                      ' . ($personal->personal_profile !== '' || $personal->personal_profile !== null ?
            '<img style="border-radius: 50%;object-fit: contain; background: #fff; max-width: 100%; height: 100%; width: 100%;" src="' . route('BaseUrl') . '/uploads/' . $personal->personal_profile . '" alt="">
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
                            <label>نام </label>
                            <input type="text" id="firstname"
                             name="firstname" class="form-control"
                             value="' . $personal->personal_firstname . '"
                                placeholder="نام">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                        <div class="form-group col-md-6">
                            <label>نام خانوادگی</label>
                            <input type="text" id="lastname"
                             name="lastname" class="form-control"
                             value="' . $personal->personal_lastname . '"
                                placeholder="نام خانوادگی">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>تاریخ تولد (فرمت صحیح: 1366/11/02)</label>
                            <input type="text" name="birth_year"
                             class="date-picker-shamsi-list form-control"
                             value="' . Jalalian::forge($personal->personal_birthday)->format('%Y/%m/%d') . '"
                             onblur="isValidDate(event,this.value)"
                             id="birth_year1" 
                             placeholder="">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                        <div class="form-group col-md-6">
                            <label>کد ملی </label>
                            <input type="number"
                            data-id="' . $personal->id . '" 
                            name="national_num" 
                            id="national_num" class="form-control"
                            value="' . $personal->personal_national_code . '"
                                placeholder="">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">وضعیت تاهل: </label>
                            <select name="marriage_status" class="form-control" id="exampleFormControlSelect2">
                                <option ' . ($personal->personal_marriage == 'مجرد' ? 'selected=""' : '') . ' value="مجرد">مجرد</option>
                                <option ' . ($personal->personal_marriage == 'متاهل' ? 'selected=""' : '') . ' value="متاهل">متاهل</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">اخرین مدرک تحصیلی </label>
                            <select name="education_status" class="form-control" id="exampleFormControlSelect2">
                                <option ' . ($personal->personal_last_diploma == 'سیکل' ? 'selected=""' : '') . ' value="سیکل">سیکل</option>
                                <option ' . ($personal->personal_last_diploma == 'دیپلم' ? 'selected=""' : '') . ' value="دیپلم">دیپلم</option>
                                <option ' . ($personal->personal_last_diploma == 'فوق دیپلم' ? 'selected=""' : '') . ' value="فوق دیپلم">فوق دیپلم</option>
                                <option ' . ($personal->personal_last_diploma == 'لیسانس' ? 'selected=""' : '') . ' value="لیسانس">لیسانس</option>
                            </select>
                        </div>
                    </div>
                </section>
                <h3>اطلاعات تماس</h3>
                <section>
                    <div class="row">
                        <div class="form-group col-md-6" style="padding-top: 11px;">
                            <label class="form-control-label"> <span class="text-danger">*</span> تلفن همراه
                            </label>
                            <input id="email" class="form-control text-right"
                            value="' . $personal->personal_mobile . '"
                             name="mobile" placeholder=""
                                type="text" dir="ltr">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">نام شهر: </label>
                            <select name="city" class="form-control" id="exampleFormControlSelect2">
                                <option ' . ($personal->personal_city == 'مشهد' ? 'selected=""' : '') . ' value="مشهد">مشهد</option>
                                <option ' . ($personal->personal_city == 'نیشابور' ? 'selected=""' : '') . ' value="نیشابور">نیشابور</option>
                                <option ' . ($personal->personal_city == 'فریمان' ? 'selected=""' : '') . ' value="فریمان">فریمان</option>
                                <option ' . ($personal->personal_city == 'سبزوار' ? 'selected=""' : '') . ' value="سبزوار">سبزوار</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> کد پستی: </label>
                            <input id="postal_code" class="form-control text-right" type="num"
                                name="postal_code" placeholder="0"
                                value="' . $personal->personal_postal_code . '"
                                type="text" dir="ltr">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-control-label"> نشانی دقیق منزل: </label>
                            <textarea id="address" class="form-control text-right" type="text" name="address"
                                dir="ltr">
                                ' . $personal->personal_address . '
                    </textarea>
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> تلفن منزل: </label>
                            <input id="tel_home" 
                            value="' . $personal->personal_home_phone . '"
                            class="form-control text-right" type="num" name="tel_home"
                                placeholder="0" type="text" dir="ltr">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> تلفن محل کار: </label>
                            <input id="tel_work" 
                            value="' . $personal->personal_office_phone . '"
                            class="form-control text-right" type="num" name="tel_work"
                                placeholder="0" type="text" dir="ltr">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                </section>
                <h3>مدارک اولیه: </h3>
                <section>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> تصویر دو صفحه اول شناسنامه: </label>
                            <input id="first_page_certificate" class="form-control text-right" type="file"
                                name="first_page_certificate" placeholder="0" type="text" dir="rtl">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> تصویر دو صفحه دوم شناسنامه: </label>
                            <input id="first_page_certificate" class="form-control text-right" type="file"
                                name="second_page_certificate" placeholder="0" type="text" dir="rtl">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> تصویر کارت پایان خدمت: </label>
                            <input id="Card_Service" class="form-control text-right" type="file"
                                name="card_Service" placeholder="0" type="text" dir="rtl">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> تصویر برگه عدم سوء پیشینه: </label>
                            <input id="antecedent_report_card" class="form-control text-right" type="file"
                                name="antecedent_report_card" placeholder="0" type="text" dir="rtl">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> تصویر روی کارت ملی: </label>
                            <input id="national_card_front_pic" class="form-control text-right" type="file"
                                name="national_card_front_pic" placeholder="0" type="text" dir="rtl">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> تصویر پشت کارت ملی: </label>
                            <input id="national_card_back_pic" class="form-control text-right" type="file"
                                name="national_card_back_pic" placeholder="0" type="text" dir="rtl">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                </section>
                <h3>مشخصات حرفه ای: </h3>
                <section>
                    <div class="row">
                        <div class="form-group col-md-12">
                            <label class="form-control-label"> درباره تخصص: </label>
                            <textarea id="about_specialization" class="form-control text-right" type="text"
                                name="about_specialization" dir="rtl">
                                ' . $personal->personal_about_specialization . '
                </textarea>
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">تعداد ماه سابقه کار: </label>
                            <input id="work_experience_month_num"
                            value="' . $personal->personal_work_experience_month . '"
                            class="form-control text-right" type="number"
                                name="work_experience_month_num" type="number" dir="rtl">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="recipient-name" class="col-form-label">تعداد سال سابقه کار: </label>
                            <input id="work_experience_year_num" 
                            value="' . $personal->personal_work_experience_year . '"
                            class="form-control text-right" type="number"
                                name="work_experience_year_num" type="number" dir="rtl">
                        </div>
                    </div>
                </section>
                <h3>تخصص: </h3>
                <section>';

        if (auth()->user()->hasRole('admin_panel')) {
            foreach (\App\Models\Services\Service::all() as $key => $service) {
                $dd = DB::table('personal_service')
                    ->where('personal_id', $request->personal_id)
                    ->where('service_id', $service->id)
                    ->first();


                if ($dd !== null) {
                    $list .= ' <div class="row">
                         <div class="form-group col-md-4">
                             <div 
                                 style="margin-left: -1rem;">
                                 <input type="checkbox" id="service_1" name="service[service_' . ($key + 1) . '][1]"
                                      value="' . $service->id . '"
                                      ' . ($dd !== null ? 'checked=""' : '') . '
                                      >
                                 <label  for="service_1">' . $service->service_title . '</label>
                             </div>
                         </div><!-- form-group -->
                         <div class="form-group col-md-4">
                             <div 
                                 style="margin-left: -1rem;">
                                 <input type="checkbox" id="service_2" name="service[service_' . ($key + 1) . '][2]"
                                      value="1"
                                      ' . ($dd->personal_chosen_status !== null  ? 'checked=""' : '') . '
                                      >
                                 <label  for="service_2">خدمت رسان ارشد</label>
                             </div>
                         </div><!-- form-group -->
                         <div class="form-group col-md-4">
                             <div 
                                 style="margin-left: -1rem;">
                                 <input type="checkbox" id="service_3" name="service[service_' . ($key + 1) . '][3]"
                                      value="1"
                                      ' . ($dd->personal_confirmed_services !== null  ? 'checked=""' : '') . '
                                      >
                                 <label  for="service_3">مورد تایید است</label>
                             </div>
                         </div><!-- form-group -->
                     </div>';
                } else {
                    $list .= ' <div class="row">
                         <div class="form-group col-md-4">
                             <div 
                                 style="margin-left: -1rem;">
                                 <input type="checkbox" id="service_1" name="service[service_' . ($key + 1) . '][1]"
                                      value="' . $service->id . '"
                                    
                                      >
                                 <label  for="service_1">' . $service->service_title . '</label>
                             </div>
                         </div><!-- form-group -->
                         <div class="form-group col-md-4">
                             <div 
                                 style="margin-left: -1rem;">
                                 <input type="checkbox" id="service_2" name="service[service_' . ($key + 1) . '][2]"
                                      value="1"
                                      
                                      >
                                 <label  for="service_2">خدمت رسان ارشد</label>
                             </div>
                         </div><!-- form-group -->
                         <div class="form-group col-md-4">
                             <div 
                                 style="margin-left: -1rem;">
                                 <input type="checkbox" id="service_3" name="service[service_' . ($key + 1) . '][3]"
                                      value="1"
                                      
                                      >
                                 <label  for="service_3">مورد تایید است</label>
                             </div>
                         </div><!-- form-group -->
                     </div>';
                }
            }
        } else {
            if (auth()->user()->roles->first()->sub_broker !== null) {
                $role_id = auth()->user()->roles->first()->sub_broker;

                $user =  User::whereHas('roles', function ($q) use ($role_id) {
                    $q->where('id', $role_id);
                })->get();
                $services = $user->services;
                foreach ($services as $key => $service) {
                    $dd = DB::table('personal_service')
                        ->where('personal_id', $request->personal_id)
                        ->where('service_id', $service->id)
                        ->first();


                    if ($dd !== null) {
                        $list .= ' <div class="row">
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_1" name="service[service_' . ($key + 1) . '][1]"
                                          value="' . $service->id . '"
                                          ' . ($dd !== null ? 'checked=""' : '') . '
                                          >
                                     <label  for="service_1">' . $service->service_title . '</label>
                                 </div>
                             </div><!-- form-group -->
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_2" name="service[service_' . ($key + 1) . '][2]"
                                          value="1"
                                          ' . ($dd->personal_chosen_status !== null  ? 'checked=""' : '') . '
                                          >
                                     <label  for="service_2">خدمت رسان ارشد</label>
                                 </div>
                             </div><!-- form-group -->
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_3" name="service[service_' . ($key + 1) . '][3]"
                                          value="1"
                                          ' . ($dd->personal_confirmed_services !== null  ? 'checked=""' : '') . '
                                          >
                                     <label  for="service_3">مورد تایید است</label>
                                 </div>
                             </div><!-- form-group -->
                         </div>';
                    } else {
                        $list .= ' <div class="row">
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_1" name="service[service_' . ($key + 1) . '][1]"
                                          value="' . $service->id . '"
                                        
                                          >
                                     <label  for="service_1">' . $service->service_title . '</label>
                                 </div>
                             </div><!-- form-group -->
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_2" name="service[service_' . ($key + 1) . '][2]"
                                          value="1"
                                          
                                          >
                                     <label  for="service_2">خدمت رسان ارشد</label>
                                 </div>
                             </div><!-- form-group -->
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_3" name="service[service_' . ($key + 1) . '][3]"
                                          value="1"
                                          
                                          >
                                     <label  for="service_3">مورد تایید است</label>
                                 </div>
                             </div><!-- form-group -->
                         </div>';
                    }
                }
            }

            if (auth()->user()->roles->first()->broker !== null) {
                $services = auth()->user()->services;
                foreach ($services as $key => $service) {
                    $dd = DB::table('personal_service')
                        ->where('personal_id', $request->personal_id)
                        ->where('service_id', $service->id)
                        ->first();


                    if ($dd !== null) {
                        $list .= ' <div class="row">
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_1" name="service[service_' . ($key + 1) . '][1]"
                                          value="' . $service->id . '"
                                          ' . ($dd !== null ? 'checked=""' : '') . '
                                          >
                                     <label  for="service_1">' . $service->service_title . '</label>
                                 </div>
                             </div><!-- form-group -->
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_2" name="service[service_' . ($key + 1) . '][2]"
                                          value="1"
                                          ' . ($dd->personal_chosen_status !== null  ? 'checked=""' : '') . '
                                          >
                                     <label  for="service_2">خدمت رسان ارشد</label>
                                 </div>
                             </div><!-- form-group -->
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_3" name="service[service_' . ($key + 1) . '][3]"
                                          value="1"
                                          ' . ($dd->personal_confirmed_services !== null  ? 'checked=""' : '') . '
                                          >
                                     <label  for="service_3">مورد تایید است</label>
                                 </div>
                             </div><!-- form-group -->
                         </div>';
                    } else {
                        $list .= ' <div class="row">
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_1" name="service[service_' . ($key + 1) . '][1]"
                                          value="' . $service->id . '"
                                        
                                          >
                                     <label  for="service_1">' . $service->service_title . '</label>
                                 </div>
                             </div><!-- form-group -->
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_2" name="service[service_' . ($key + 1) . '][2]"
                                          value="1"
                                          
                                          >
                                     <label  for="service_2">خدمت رسان ارشد</label>
                                 </div>
                             </div><!-- form-group -->
                             <div class="form-group col-md-4">
                                 <div 
                                     style="margin-left: -1rem;">
                                     <input type="checkbox" id="service_3" name="service[service_' . ($key + 1) . '][3]"
                                          value="1"
                                          
                                          >
                                     <label  for="service_3">مورد تایید است</label>
                                 </div>
                             </div><!-- form-group -->
                         </div>';
                    }
                }
            }
        }
        $list .= '</section>
                <h3>مدارک فنی: </h3>
                <section>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> بارگذاری: </label>
                            <input id="technical_credential" class="form-control text-right" type="file"
                                name="technical_credential" type="text" dir="rtl">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                </section>
                <h3>مدارک تحصیلی: </h3>
                <section>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-control-label"> بارگذاری: </label>
                            <input id="expert_credential" class="form-control text-right" type="file"
                                name="expert_credential" type="text" dir="rtl">
                            <div class="valid-feedback">
                                صحیح است!
                            </div>
                        </div><!-- form-group -->
                    </div>
                </section>
            </form>
        </div>
    </div>';


        return $list;
    }



    public function SubmitPersonalEdit(Request $request)
    {

        $personal = Personal::where('id', $request->personal_id)->first();
        if ($request->has('first_page_certificate')) {
            File::delete(public_path() . '/uploads/personals/' . $request->mobile . '/' . $personal->personal_identity_card_first_pic);
            $first_page_certificate_img = 'first_page' . '.' . $request->first_page_certificate->getClientOriginalExtension();
            $request->first_page_certificate->move(public_path('uploads/personals/' . $request->mobile), $first_page_certificate_img);
            $first_page_certificate = $request->mobile . '/' . $first_page_certificate_img;
        } else {
            $first_page_certificate = $personal->personal_identity_card_first_pic;
        }
        if ($request->has('personal_profile')) {
            File::delete(public_path() . '/uploads/' . $personal->personal_profile);

            $personal_img = 'photo' . '.' . $request->personal_profile->getClientOriginalExtension();
            $request->personal_profile->move(public_path('uploads/personals/' . $request->mobile), $personal_img);
            $personal_profile ='personals/' . $request->mobile . '/' . $personal_img;
        } else {
            $personal_profile = $personal->personal_profile;
        }
        if ($request->has('card_Service')) {
            File::delete(public_path() . '/uploads/personals/' . $request->mobile . '/' . $personal->personal_status_duty);
            $card_Service_img = 'duty_status' . '.' . $request->card_Service->getClientOriginalExtension();
            $request->card_Service->move(public_path('uploads/personals/' . $request->mobile), $card_Service_img);
            $card_Service = 'personals/' . $request->mobile . '/' . $card_Service_img;
        } else {
            $card_Service = $personal->personal_status_duty;
        }
        if ($request->has('backgrounds_status')) {
            File::delete(public_path() . '/uploads/personals/' . $request->mobile . '/' . $personal->personal_backgrounds_status);
            $antecedent_report_card_img = 'antecedent_report_card' . '.' . $request->antecedent_report_card->getClientOriginalExtension();
            $request->antecedent_report_card->move(public_path('uploads/personals/' . $request->mobile), $antecedent_report_card_img);
            $antecedent_report_card = 'personals/' . $request->mobile . '/' . $antecedent_report_card_img;
        } else {
            $antecedent_report_card = $personal->personal_backgrounds_status;
        }

        if ($request->has('second_page_certificate')) {
            File::delete(public_path() . '/uploads/personals/' . $request->mobile . '/' . $personal->personal_identity_card_second_pic);
            $second_page_certificate_img = 'second_page' . '.' . $request->second_page_certificate->getClientOriginalExtension();
            $request->second_page_certificate->move(public_path('uploads/personals/' . $request->mobile), $second_page_certificate_img);
            $second_page_certificate = 'personals/' . $request->mobile . '/' . $second_page_certificate_img;
        } else {
            $second_page_certificate = $personal->personal_identity_card_second_pic;
        }
        if ($request->has('national_card_front_pic')) {
            File::delete(public_path() . '/uploads/personals/' . $request->mobile . '/' . $personal->personal_national_card_front_pic);
            $national_card_front_pic_img = 'national_card_front_pic' . '.' . $request->national_card_front_pic->getClientOriginalExtension();
            $request->national_card_front_pic->move(public_path('uploads/personals/' . $request->mobile), $national_card_front_pic_img);
            $national_card_front_pic ='personals/' .  $request->mobile . '/' . $national_card_front_pic_img;
        } else {
            $national_card_front_pic = $personal->personal_national_card_front_pic;
        }
        if ($request->has('national_card_back_pic')) {
            File::delete(public_path() . '/uploads/personals/' . $request->mobile . '/' . $personal->personal_national_card_back_pic);
            $national_card_back_pic_img = 'first_page' . '.' . $request->national_card_back_pic->getClientOriginalExtension();
            $request->national_card_back_pic->move(public_path('uploads/personals/' . $request->mobile), $national_card_back_pic_img);
            $national_card_back_pic = 'personals/' . $request->mobile . '/' . $national_card_back_pic_img;
        } else {
            $national_card_back_pic = $personal->personal_national_card_back_pic;
        }
        if ($request->has('technical_credential')) {
            File::delete(public_path() . '/uploads/personals/' . $request->mobile . '/' . $personal->technical_credential);
            $technical_credential_img = 'technical_credential' . '.' . $request->technical_credential->getClientOriginalExtension();
            $request->technical_credential->move(public_path('uploads/personals/' . $request->mobile), $technical_credential_img);
            $technical_credential ='personals/' .  $request->mobile . '/' . $technical_credential_img;
        } else {
            $technical_credential = $personal->technical_credential;
        }
        if ($request->has('expert_credential')) {
            File::delete(public_path() . '/uploads/personals/' . $request->mobile . '/' . $personal->expert_credential);
            $expert_credential_img = 'expert_credential' . '.' . $request->expert_credential->getClientOriginalExtension();
            $request->expert_credential->move(public_path('uploads/personals/' . $request->mobile), $expert_credential_img);
            $expert_credential ='personals/' . $request->mobile . '/' . $expert_credential_img;
        } else {
            $expert_credential = $personal->expert_credential;
        }

        Personal::where('id', $request->personal_id)
            ->update([

                'personal_firstname' => $request->firstname,
                'personal_lastname' => $request->lastname,
                'personal_birthday' => $this->convertDate($request->birth_year)->toDateString(),
                'personal_national_code' => $request->national_num,
                'personal_marriage' => $request->marriage_status,
                'personal_last_diploma' => $request->education_status,
                'personal_mobile' => $request->mobile,
                'personal_city' => $request->city,
                'personal_postal_code' => $request->postal_code,
                'personal_address' => $request->address,
                'personal_home_phone' => $request->tel_home,
                'personal_office_phone' => $request->tel_work,
                'personal_responsibility' => $request->postal_code,
                'technical_credential' => $technical_credential,
                'expert_credential' => $expert_credential,
                'personal_identity_card_first_pic' => $first_page_certificate,
                'personal_identity_card_second_pic' => $second_page_certificate,
                'personal_status_duty' => $card_Service,
                'personal_backgrounds_status' => $antecedent_report_card,
                'personal_national_card_front_pic' => $national_card_front_pic,
                'personal_national_card_back_pic' => $national_card_back_pic,
                'personal_about_specialization' => $request->about_specialization,
                'personal_work_experience_month' => $request->work_experience_month_num,
                'personal_work_experience_year' => $request->work_experience_year_num,
                'personal_profile' => $personal_profile
            ]);


        if ($request->has('service')) {
            $personal->services()->detach();
            foreach ($request->service as $key => $service) {
                if (!array_key_exists(2, $service) and !array_key_exists(3, $service)) {

                    $personal->services()->attach($service[1]);
                }
                if (array_key_exists(2, $service) and !array_key_exists(3, $service)) {

                    $personal->services()->attach($service[1], ['personal_chosen_status' => $service[2], 'personal_confirmed_services' => null]);
                }
                if (!array_key_exists(2, $service) and array_key_exists(3, $service)) {

                    $personal->services()->attach($service[1], ['personal_chosen_status' => null, 'personal_confirmed_services' => $service[3]]);
                }
                if (array_key_exists(2, $service) and array_key_exists(3, $service)) {

                    $personal->services()->attach($service[1], ['personal_chosen_status' => $service[2], 'personal_confirmed_services' => $service[3]]);
                }
            }
        }

        alert()->success('خدمت رسان با موفقیت ویرایش شد', 'عملیات موفق')->autoclose(2000);
        return back();
    }

    public function CheckNationalNum(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'personal_national_code' => 'unique:personals,personal_national_code,' . $request->personal_id,
        ]);
        if (!$validator->passes()) {
            return ['error' => 'کاربری با این کد ملی از قبل ثبت شده است!'];
        }
    }


    public function PersonalOrderBy(Request $request)
    {

        if ($request->data == 'name') {
            $personals = Personal::OrderBy('personal_firstname', 'ASC')->get();
        }
        if ($request->data == 'family') {
            $personals = Personal::OrderBy('personal_lastname', 'ASC')->get();
        }
        //   if ($request->data == 'gender') {
        //     $personals = Service::OrderBy('gender','ASC')->get();
        //   }

        $tbody = '';
        foreach ($personals as $key => $personal) {
            $tbody .= '
            <tr>
            <td>
              <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
              <input data-id="' . $personal->id . '" type="checkbox" id="' . $key . '" name="customCheckboxInline1" class="custom-control-input" value="1">
                <label class="custom-control-label" for="' . $key . '"></label>
              </div>
            </td>
            <td> ' . ($key + 1) . ' </td>
            <td>' . $personal->personal_firstname . '</td>
            <td>' . $personal->personal_lastname . '</td>
            <td>' . ($personal->personal_mobile  !== null ? $personal->personal_mobile : 'وارد نشده') . '</td>
            ' . ($personal->personal_status == 1 ? ' <td class="text-success">
            <i class="fa fa-check"></i>
            </td>' : '<td class="text-danger">
            <i class="fa fa-close"></i>
            </td>') . '
            <td>مرد</td>
            <td>' . $personal->personal_marriage . '</td>
            <td>' . ($personal->personal_last_diploma  !== null ? $personal->personal_last_diploma : 'وارد نشده') . '</td>
            <td>' . ($personal->personal_home_phone  !== null ? $personal->personal_home_phone : 'وارد نشده') . '</td>
            <td>' . ($personal->personal_office_phone  !== null ? $personal->personal_office_phone : 'وارد نشده') . '</td>
          </tr>
    
            ';
        }

        return $tbody;
    }
}
