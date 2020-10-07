<?php

namespace App\Http\Controllers\App;

use App\Models\App\Slideshow;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class SlideshowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $slideshows = Slideshow::orderBy('id','DESC')->paginate(20);
        return view('User.App.SlideShow' , compact('slideshows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $this->getValidationFactory()->make($request->all(), [
            'title' => 'required',
            'place' => 'required',
            'release' => 'required',
            'expiry' => 'required',
            'image' => 'required',

        ]);

        if ($validation->fails()) {

            //return response()->json(['messsage' => 'invalid'], 400);
            alert()->error('باید تمامی فیلد های الزامل را پر کنید!', 'ثبت صورت نپذیرفت')->autoclose(2000);
            //return 'error';
            return back();

        }

        $file = $request->file('image');
        $file_name = 'photo-'.time().'.'.$file->getClientOriginalExtension();
        $file->move('uploads/slideshow',$file_name);

        $slideshow = new Slideshow();
        $slideshow->title = $request->title;
        $slideshow->image = 'slideshow/'.$file_name;
        if (isset($request->status)) {
            $slideshow->status = 1;
        }else{
            $slideshow->status = 0;
        }
        if (isset($request->default)) {
            $slideshow->default = 1;
        }else{
            $slideshow->default = 0;
        }

        $slideshow->place = $request->place;
        $slideshow->release = $this->convertDate($request->release);
        $slideshow->expiry = $this->convertDate($request->expiry);
        $slideshow->page_id = 0;
        $slideshow->save();
        alert()->success('اسلایدشو با موفقیت ثبت شد', 'عملیات موفق')->autoclose(3000);
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function show(Slideshow $slideshow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function edit(Slideshow $slideshow)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slideshow $slideshow)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Slideshow  $slideshow
     * @return \Illuminate\Http\Response
     */
    public function DeleteSlideshow(Request $request)
    {
        foreach ($request->array as $slideshow_id) {

            Slideshow::where('id', $slideshow_id)->delete();
        }
        return 'success';
    }


    public function getData(Request $request)
    {
        $slideshow = Slideshow::where('id', $request->id)->first();
        $csrf = csrf_token();
        $list = '<form id="edit--slideshow" action="' . route('SlideShow.Edit.Insert') . '" method="post"  enctype="multipart/form-data">
        <input type="hidden" name="_token" value="' . $csrf . '">
        <input type="hidden" name="id" value="' . $slideshow->id . '">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">ویرایش اسلایدشو</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group col-md-12">
                <label for="title" class="col-form-label">عنوان:  </label>
                <input type="text" class="form-control" name="title" id="title" value="' . $slideshow->title . '">
            </div>
            <div class="form-group col-md-12">
                <label for="image" class="col-form-label">تصویر:  </label>
                <input type="file" class="form-control" name="image" id="image">
                <input type="hidden" name="oldimage" id="oldimage" value="' . $slideshow->image . '">
            </div>
            <div class="form-group col-md-12">
                تصویر انتخاب شده : <span>' . basename($slideshow->image) . '</span>
                <img width="100%" height=="100%" src="'.asset('uploads/'.$slideshow->image).'"> 
            </div><br>
            <div class="form-group col-md-12">
                منتشرشده: ';
                if($slideshow->status==1){
                    $list .='<input type="checkbox" checked name="status" id="status">';
                }else{
                    $list .='<input type="checkbox" name="status" id="status">'; 
                   }
                $list .='
            </div>
            <div class="form-group col-md-12">
                پیش فرض: ';
                if($slideshow->default==1){
                    $list .='<input type="checkbox" checked name="default" id="default">';
                }else{
                    $list .='<input type="checkbox" name="default" id="default">'; 
                   }
                $list .='
            </div>
            <div class="form-group col-md-12">
                <label for="place" class="col-form-label">مکان:  </label>
                <select class="form-control" name="place">';
                if($slideshow->place=='خانه'){
                    $list .='<option value="خانه" selected>خانه</option>
                    <option value="خدمات">خدمات</option>';
                }else{
                    $list .='<option value="خانه">خانه</option>
                    <option selected value="خدمات">خدمات</option>'; 
                   }
                $list .='</select>
            </div>
            <div class="form-group col-md-12">
                <label for="newrelease" class="col-form-label">تاریخ انتشار:  </label>
                <input readonly type="text" class="form-control newrelease " name="release" id="newrelease" value="' . Jalalian::forge($slideshow->release)->format('%Y/%m/%d') . '">
            </div>
            <div class="form-group col-md-12">
                <label for="newexpiry" class="col-form-label">تاریخ انقضا:  </label>
                <input readonly type="text" class="form-control newexpiry " name="expiry" id="newexpiry" value="' . Jalalian::forge($slideshow->expiry)->format('%Y/%m/%d') . '">
            </div>
            <script>
            $(document).ready(function() {
                $(".newrelease").datepicker({
                    dateFormat: "yy/mm/dd",
                    showOtherMonths: true,
                    selectOtherMonths: false
                });
                $(".newexpiry").datepicker({
                    dateFormat: "yy/mm/dd",
                    showOtherMonths: true,
                    selectOtherMonths: false
                });
            });
            </script>';

          
          
       $list .=' </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">بستن</button>
          <button type="submit" class="btn btn-primary">ویرایش </button>
        </div>
    </form>';

    return $list;
    }

    public function EditSlideshow(Request $request)
    {

        $validation = $this->getValidationFactory()->make($request->all(), [
            'title' => 'required',
            'place' => 'required',
            'release' => 'required',
            'expiry' => 'required',
        ]);

        if ($validation->fails()) {

            //return response()->json(['messsage' => 'invalid'], 400);
            alert()->error('باید تمامی فیلد های الزامل را پر کنید!', 'ثبت صورت نپذیرفت')->autoclose(2000);
            //return 'error';
            return back();

        }

        if(empty($request->file('image')))
        {
            $image = $request->oldimage;
        }else{
            $file = $request->file('image');
            $file_name = 'photo-'.time().'.'.$file->getClientOriginalExtension();
            $file->move('uploads/slideshow',$file_name);
            $image = 'slideshow/'.$file_name;
            File::delete('uploads/'.$request->oldimage);
        }
        if (isset($request->status)) {
            $status = 1;
        }else{
            $status = 0;
        }
        if (isset($request->default)) {
            $default = 1;
        }else{
            $default = 0;
        }
        Slideshow::where('id',$request->id)->update([
            'title' => $request->title,
            'image' => $image,
            'status' => $status,
            'default' => $default,
            'place' => $request->place,
            'release' => $this->convertDate($request->release),
            'expiry' => $this->convertDate($request->expiry),
            'page_id' => 0,
        ]);
        alert()->success('اسلایدشو با موفقیت ویرایش شد ', 'عملیات موفق')->autoclose(2000);
        return back();
    }
}
