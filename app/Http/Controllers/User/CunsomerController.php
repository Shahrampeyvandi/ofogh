<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\City\City;
use App\Models\Cunsomers\Cunsomer;
use Morilog\Jalali\Jalalian;

class CunsomerController extends Controller
{
    public function CunsomerList()
    {
        $customers = Cunsomer::latest()->get();
        return view('User.Cunsomers.CunsomerList',compact('customers'));
    }

    public function DeleteCustomers(Request  $request)
    {
        foreach ($request->array as $customer_id) {
            Cunsomer::where('id',$customer_id)->first()->useracounts()->delete();
            Cunsomer::where('id',$customer_id)->delete();
        }
        return 'success';
    }

    public function getData(Request $request)
    {
        

        $customer = Cunsomer::where('id',$request->id)->first();
      
        $csrf = csrf_token();

        $list = '<div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">ویرایش مشتری</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="edit--form" method="post" action="'.route('Customer.Edit.Submit').'" enctype="multipart/form-data">
      <input type="hidden" name="_token" value="'.$csrf.'">
      <input type="hidden" name="id" value="'.$customer->id.'">
      <div class="modal-body">
          <div class="row">
            <div class="form-group col-md-6">
              <label for="firstname" class="col-form-label">نام:</label>
              <input type="text" 
              value="'.$customer->customer_firstname.'"
              class="form-control"
              name="firstname"
              id="firstname">
            </div>
            <div class="form-group col-md-6">
              <label for="lastname" class="col-form-label">نام خانوادگی:</label>
              <input type="text"
              value="'.$customer->customer_lastname.'"
              class="form-control"
              name="lastname"
              id="lastname">
            </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">
              <label for="mobile" class="col-form-label">موبایل:</label>
              <input type="number"
              value="'.$customer->customer_mobile.'"
              class="form-control"
              name="mobile"
              id="mobile">
            </div>
            <div class="form-group col-md-6">
              <label for="national_code" class="col-form-label">کد ملی:</label>
              <input type="number" 
              value="'.$customer->customer_national_code.'"
              class="form-control"
              name="national_code"
              id="national_code">
            </div>
          </div>
          <p>تغییر وضعیت مشتری </p>
          <div class="row">
            <div class="form-group col-md-6">
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="customRadioInline1" name="status"
                class="custom-control-input checkbox__" value="1"
                '.($customer->customer_status == 1 ? 'checked=""' : '').'
                >
              <label class="custom-control-label " for="customRadioInline1">فعال</label>
            </div>
            <div class="custom-control custom-radio custom-control-inline">
              <input type="radio" id="customRadioInline2" name="status"
                class="custom-control-input checkbox__" value="0"
                '.($customer->customer_status == 0 ? 'checked=""' : '').'
                >
              <label class="custom-control-label" for="customRadioInline2">غیرفعال</label>
            </div>
          </div>
          </div>
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
        <button type="submit" class="btn btn-primary">ویرایش</button>
      </div>
      
      </form>';

      return $list;

    }

    public function EditCustomer(Request $request)
    {
        Cunsomer::where('id',$request->id)->update([
            'customer_firstname' => $request->firstname,
            'customer_lastname' => $request->lastname,
            'customer_national_code' => $request->national_code,
            'customer_mobile' => $request->mobile,
            'broker_id' => auth()->user()->id,
            'customer_status' => $request->status,
        ]);

        alert()->success('اطلاعات مشتری با موفقیت ویرایش شد ', 'عملیات موفق')->autoclose(2000);
        return back();
    }

    public function ChangeStatus(Request $request)
    {
      
      if ($request->value == '1') {
        Cunsomer::where('id',$request->id)->update([
            'customer_status' => 1
        ]);
    }

    if ($request->value == '0') {
        Cunsomer::where('id',$request->id)->update([
            'customer_status' => 0
        ]);
    }
    
    return $request->value;
    }

    public function OrderBy(Request $request)
    {

      
      if ($request->data == 'name') {
        $customers = Cunsomer::OrderBy('customer_firstname','ASC')->get();
      }
      if ($request->data == 'family') {
        $customers = Cunsomer::OrderBy('customer_lastname','ASC')->get();
      }
      if ($request->data == 'created_date') {
        $customers = Cunsomer::latest()->get();
      }
      
      $tbody ='';
      foreach ($customers as $key => $customer) {
        $tbody .= '
        <tr>
        <td>
          <div class="custom-control custom-checkbox custom-control-inline" style="margin-left: -1rem;">
          <input data-id="'.$customer->id.'" type="checkbox" id="'.$key.'" name="customCheckboxInline1" class="custom-control-input" value="1">
            <label class="custom-control-label" for="'.$key.'"></label>
          </div>
        </td>
        <td> '.($key+1).' </td>
        <td>'.$customer->customer_firstname.'</td>
        <td>'.$customer->customer_lastname.'</td>
        <td>'.($customer->customer_mobile  !== ''  ? $customer->customer_mobile : 'وارد نشده' ). '</td>
        '.($customer->customer_status == 1 ? ' <td class="text-success">
        <i class="fa fa-check"></i>
        </td>' : '<td class="text-danger">
        <i class="fa fa-close"></i>
        </td>').'
        
        <td>'.($customer->customer_national_code !== null && $customer->customer_national_code !== '' ? 
        $customer->customer_national_code : 'وارد نشده'
        ) . '</td>
        <td>'.(\Morilog\Jalali\Jalalian::forge($customer->created_at)->format('%d/ %m /%Y')). '</td>
        <td>'.(\Morilog\Jalali\Jalalian::forge($customer->update_at)->format('%d/ %m /%Y')). '</td>
      </tr>

        ';
      }
     
      return $tbody;
    }

    public function FilterCustomer(Request $request)
    {
         
      if($request->type_send == 'تاریخ ثبت'){
        $customers =   Cunsomer::where('created_at',$this->convertDate($request->word))->get();
      }  
      if($request->type_send == 'نام'){
        $customers =  Cunsomer::where('customer_firstname', 'like', '%' . $request->word. '%')
        ->get();
      }  
      if($request->type_send == 'کد ملی'){
        $customers =  Cunsomer::where('customer_national_code', 'like', '%' . $request->word. '%')
        ->get();
      }  
      if($request->type_send == 'شماره موبایل'){
        $customers =  Cunsomer::where('customer_mobile', 'like', '%' . $request->word. '%')
        ->get();
      }  
      return view('User.Cunsomers.CunsomerList',compact('customers'));
     
    }
}
