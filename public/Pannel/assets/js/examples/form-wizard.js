'use strict';
$(document).ready(function () {

    $('#wizard1').steps({
        headerTag: 'h3',
        bodyTag: 'section',
        autoFocus: true,
        titleTemplate: '#index# #title#',
		labels: {
			cancel: 'انصراف',
			current: 'قدم کنونی:',
			pagination: 'صفحه بندی',
			finish: 'ارسال',
			next: 'بعدی',
			previous: 'قبلی',
			loading: 'در حال بارگذاری ...'
		}
    });
    var form = $("#example-advanced-form").show();
    form.validate({
        rules: {
          title: {
            required: true,
            // digits: true,
            // minlength: 5,
            // maxlength: 5
          },
         
          service_percentage: {
            required: true,
            range:[0,100]
          },
          firstname: {
            required:true
        }, 
        lastname: {
            required:true
        },
        national_num:{
            required:true,
            
            maxlength:10
        },
        work_experience_month_num: {
            required:true,
            range:[0,12]
        },
        date_one: {
            required:true
        },
        birth_year: {
            required:true
        },
        tel_work: {
            required:true
        },
        store_name: {
            required:true
        },
        store_city : {
            required:true
        },
        store_main_street:{
            required:true
        },
        store_pluck_num:{
            required:true
        },
        mobile:{
            required:true
        }

        
        },
        messages: {
          title: {
            //minlength: jQuery.format("Zip must be {0} digits in length"),
            //maxlength: jQuery.format("Please use a {0} digit zip code"),
            required: "لطفا عنوان را وارد نمایید"
          },
          service_category: {
            required:'سرگروه خدمت را انتخاب نمایید'
        },
        service_percentage: {
            required:'درصد پورسانت را وارد نمایید',
            range:'پورسانت حداکثر 100% میباشد'
        },
        firstname: {
            required:'لطفا نام خود را وارد نمایید'
        }, 
        lastname: {
            required:'لطفا نام خانوادگی خود را وارد نمایید'
        },
        national_num:{
            required: ' کد ملی خود را وارد نمایید',
            maxlength:'کد ملی بایستی حداکثر 10 رقم باشد'
        },
        work_experience_month_num: {
            required:'فیلد اجباری است',
            range:'ماه باید در بازه 0 تا12 باشد',
           
        },date_one: {
            required:'تاریخ سفارش را انتخاب کنید',
        },
        birth_year: {
            required:'تاریخ تولد را انتخاب کنید',
        } , tel_work: {
            required:'شماره محل کار را وارد نمایید'
        },
        store_name: {
            required:' نام فروشگاه را وارد نمایید'
        },
        store_city: {
            required:' نام شهر را وارد نمایید'
        },
        store_main_street:{
            required: 'نام خیابان اصلی را وارد نمایید'
        },
        store_pluck_num:{
            required: 'شماره پلاک را وارد نماید'
        },
        mobile:{
            required: 'شماره موبایل را وارد کنید'
        }
        }
      });
    form.steps({
    headerTag: "h3",
    bodyTag: "section",
    transitionEffect: "slideLeft",
    labels: {
        cancel: 'انصراف',
        current: 'قدم کنونی:',
        pagination: 'صفحه بندی',
        finish: 'ثبت اطلاعات',
        next: 'بعدی',
        previous: 'قبلی',
        loading: 'در حال بارگذاری ...'
    },
    onStepChanging: function (event, currentIndex, newIndex)
    {
        // Allways allow previous action even if the current form is not valid!
        if (currentIndex > newIndex)
        {
            return true;
        }
        // Forbid next action on "Warning" step if the user is to young
        if (newIndex === 3 && Number($("#age-2").val()) < 18)
        {
            return false;
        }
        // Needed in some cases if the user went back (clean up)
        if (currentIndex < newIndex)
        {
            // To remove error styles
            form.find(".body:eq(" + newIndex + ") label.error").remove();
            form.find(".body:eq(" + newIndex + ") .error").removeClass("error");
        }
        
        return form.valid();
    },
    onStepChanged: function (event, currentIndex, priorIndex)
    {
        // Used to skip the "Warning" step if the user is old enough.
        if (currentIndex === 2 && Number($("#age-2").val()) >= 18)
        {
            form.steps("next");
        }
        // Used to skip the "Warning" step if the user is old enough and wants to the previous step.
        if (currentIndex === 2 && priorIndex === 3)
        {
            form.steps("previous");
        }
    },
    onFinishing: function (event, currentIndex)
    {
        form.validate().settings.ignore = ":disabled";
        return form.valid();
    },
    onFinished: function (event, currentIndex)
    {
        // var file =$('#service_icon')
        // var formData = new FormData($(this)[0]);
        // formData.append('file',$('#service_icon'))
        // console.log(formData)
        form.submit()
        
    }
}).validate({
    errorPlacement: function errorPlacement(error, element) { element.before(error); },
    rules: {
        confirm: {
            equalTo: "#password-2"
        }
    }
});

});
