@extends('Layouts.Pannel.Template')

@section('css')



@endsection
@section('content')


     {{-- filtering --}}
     <div class="card filtering container-fluid" >
        <div class="card-body">
          <div class="card-title">
            <h5 class="text-center">تنظیمات</h5>
            <hr>
        </div>
          <div class="row" >
            <div class="col-md-12">

              <form method="POST" action="{{route('Pannel.Setting.Change')}}">
                {!! csrf_field() !!}
                <!-- form-group -->
                <div class="row">
                  <div class="form-group col-md-6">
                    <label>ضریب ستاره مشتری برای انجام کار در هفته </label>
                  <input type="text" name="zarib_setare_moshtari" class="form-control" placeholder="" value="{{$setting->zaribsetaremoshtari}}">
                      <div class="valid-feedback">
                          صحیح است!
                      </div>
                  </div><!-- form-group -->
                  <div class="form-group col-md-6">
                      <label>ضریب تعداد سر وقت رسیدن به محل کار</label>
                      <input type="text" name="zaribtedadsarevaghtresidan" id="service_offered_price" class="form-control" placeholder="" value="{{$setting->zaribtedadsarevaghtresidan}}">
                      <div class="valid-feedback">
                          صحیح است!
                      </div>
                  </div><!-- form-group -->
              </div>



              <!-- form-group -->
              <div class="row">
                <div class="form-group col-md-6">
                  <label>ضریب تعداد دیر رسیدن به محل کار </label>
                    <input type="text" value="{{$setting->zaribetedadedirresidan}}" name="zaribetedadedirresidan" id="service_percentage" class="form-control" placeholder="">
                    <div class="valid-feedback">
                        صحیح است!
                    </div>
                </div><!-- form-group -->
                <div class="form-group col-md-6">
                    <label>ضریب تعداد قطعی های کنسل شده در هفته خدمت رسان</label>
                    <input type="text" name="zaribetedadeghatehayecancelshode" id="service_offered_price" 
                    value="{{$setting->zaribetedadeghatehayecancelshode}}"
                    class="form-control" placeholder="">
                    <div class="valid-feedback">
                        صحیح است!
                    </div>
                </div><!-- form-group -->
            </div>



            <!-- form-group -->
            <div class="row">
              <div class="form-group col-md-6">
                <label>ضریب تعداد شروع به کار کنسل شده در هفته خدمت رسان</label>
                <input type="text" name="zaribetedadeshoroebkarcancellshode" id="service_percentage" 
                value="{{$setting->zaribetedadeshoroebkarcancellshode}}"
                class="form-control" placeholder="">
                  <div class="valid-feedback">
                      صحیح است!
                  </div>
              </div><!-- form-group -->
              <div class="form-group col-md-6">
                  <label>ضریب تعداد پیشنهادات داده شده در هفته</label>
                  <input type="text" name="zaribetedadepishnaddarnmah" id="service_offered_price" 
                  value="{{$setting->zaribetedadepishnaddarnmah}}"
                  class="form-control" placeholder="">
                  <div class="valid-feedback">
                      صحیح است!
                  </div>
              </div><!-- form-group -->
          </div>


          <!-- form-group -->
          <div class="row">
            <div class="form-group col-md-6">
              <label>ضریب تعداد کار های ثبت نام اولیه فعال شده در هفته </label>
                <input type="text" name="zaribetedadkarsabtnamavalie" id="service_percentage" 
                value="{{$setting->zaribetedadkarsabtnamavalie}}"
                
                class="form-control" placeholder="">
                <div class="valid-feedback">
                    صحیح است!
                </div>
            </div><!-- form-group -->
            <div class="form-group col-md-6">
                <label>حد اول امتیاز خدمت رسان</label>
                <input type="text" name="emtiazkhedmatresanhad1" id="service_offered_price" 
                value="{{$setting->emtiazkhedmatresanhad1}}"
                
                class="form-control" placeholder="">
                <div class="valid-feedback">
                    صحیح است!
                </div>
            </div><!-- form-group -->
        </div>



        <!-- form-group -->
        <div class="row">
          <div class="form-group col-md-6">
            <label>حد دوم امتیاز خدمت رسان</label>
            <input type="text" name="emtiazkhedmatresanhad2" id="service_percentage"
            id="service_offered_price" 
                value="{{$setting->emtiazkhedmatresanhad2}}"
                
                class="form-control" placeholder="">
              <div class="valid-feedback">
                  صحیح است!
              </div>
          </div><!-- form-group -->
          <div class="form-group col-md-6">
            <label>حد سوم امتیاز خدمت رسان</label>
            <input type="text" name="emtiazkhedmatresanhad3" id="service_offered_price"
            id="service_offered_price" 
                value="{{$setting->emtiazkhedmatresanhad3}}"
                
                class="form-control" placeholder="">
              <div class="valid-feedback">
                  صحیح است!
              </div>
          </div><!-- form-group -->
      </div>



      <!-- form-group -->
      <div class="row">
        <div class="form-group col-md-6">
          <label>تعداد روز تعلیق </label>
            <input type="text" name="tedadrooztaligh" id="service_percentage"
            id="service_offered_price" 
                value="{{$setting->tedadrooztaligh}}"
                
                class="form-control" placeholder="">
            <div class="valid-feedback">
                صحیح است!
            </div>
        </div><!-- form-group -->
        <div class="form-group col-md-6">
            <label>لینک سوالات متداول/درباره ما</label>
            <input name="linkfaq" 
            value="{{$setting->linkfaq}}"
            
            id="service_offered_price" class="form-control" placeholder="">
            <div class="valid-feedback">
                صحیح است!
            </div>
        </div><!-- form-group -->
    </div>



    <!-- form-group -->
    <div class="row">
      <div class="form-group col-md-6">
        <label>لینک قوانین و مقررات</label>
        <input  name="linklaw" id="service_percentage" class="form-control" 
        value="{{$setting->linklaw}}"
        placeholder="">
          <div class="valid-feedback">
              صحیح است!
          </div>
      </div><!-- form-group -->
      <div class="form-group col-md-6">
        <label>لینک اپ متخصص</label>
        <input  name="linkappservicer" id="service_offered_price" class="form-control" 
        value="{{$setting->linkappservicer}}"
        
        placeholder="">
          <div class="valid-feedback">
              صحیح است!
          </div>
      </div><!-- form-group -->
  </div>




  <!-- form-group -->
  <div class="row">
    <div class="form-group col-md-6">
      <label>شماره اپراتور </label>
        <input  name="shomareoperator" 
        value="{{$setting->shomareoperator}}"
        
        id="service_percentage" class="form-control" placeholder="">
        <div class="valid-feedback">
            صحیح است!
        </div>
    </div><!-- form-group -->
    <div class="form-group col-md-6">
        <label>شماره پشتیبانی</label>
        <input name="shomareposhtibani" 
        value="{{$setting->shomareposhtibani}}"
        
        id="service_offered_price" class="form-control" placeholder="">
        <div class="valid-feedback">
            صحیح است!
        </div>
    </div><!-- form-group -->
</div>


  <!-- form-group -->
  <div class="row">
    <div class="form-group col-md-6">
      <label>تلگرام پشتیبانی</label>
        <input  name="telegramposhtibani" 
        value="{{$setting->telegramposhtibani}}"
        
        id="service_percentage" class="form-control" placeholder="">
        <div class="valid-feedback">
            صحیح است!
        </div>
    </div><!-- form-group -->
    <div class="form-group col-md-6">

    </div><!-- form-group -->
</div>



<div class="row">
  <div class="form-group col-md-10">

  </div>
  <div class="form-group col-md-2 pull-center">

    <button type="submit" class="btn btn-outline-primary">ذخیره سازی</button>
  </div>

</form>
</div>


          </div>
        </div>
      </div>
  {{-- end filtering --}}



@endsection

@section('js')





@endsection
