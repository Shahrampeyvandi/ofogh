@extends('Layouts.Pannel.Template')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="row">
            <div class="col-lg-12 col-xs-6 my-3">
                <h4 class="text-center bg-primary mx-3 p-2" style="box-shadow: 0 3px 9px 1px #777474;
               border-radius: 4px;"></h4>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-xs-6 my-3">
                <!-- small box -->
                <div class="small-box mx-5"
                    style=" display: flex;
                justify-content: space-between; padding: 21px;   box-shadow: 0 6px 20px 0 rgba(255,202,40,.5)!important; background: linear-gradient(-45deg,#ff6f00,#ffca28)!important;color: #fff;border-radius: 7px;">
                    <div class="inner">
                        <h3>
4
                        </h3>

                        <p> کاربران</p>
                    </div>
                    <div class="icon" style="padding: 31px 0 10px 34px;
                    font-size: 50px;">
                        <i class="fa fa-exclamation"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-xs-6 my-3">
                <!-- small box -->
                <div class="small-box mx-5" style="display: flex;justify-content: space-between; padding: 20px;
                box-shadow: 0 6px 20px 0 #8794f3!important;
    background: linear-gradient(-45deg,#052c9c,#8794f3)!important;
                color: #fff;
                border-radius: 7px;">
                    <div class="inner">
                        <h3>
10
                        </h3>

                        <p> سفارشات</p>
                    </div>
                    <div class="icon" style="padding: 31px 0 10px 34px;
                    font-size: 50px;">
                        <i class="fa fa-exclamation"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-xs-6 my-3">
                <!-- small box -->
                <div class="small-box mx-5" style="        display: flex;        justify-content: space-between; padding: 20px;
                box-shadow: 0 6px 20px 0 rgba(29,233,182,.5)!important;
                background: linear-gradient(-45deg,#43a047,#1de9b6)!important;
                color: #fff;
                border-radius: 7px;">
                    <div class="inner">
                        <h3>
                            100

                        </h3>

                        <p> محصولات</p>
                    </div>
                    <div class="icon" style="padding: 31px 0 10px 34px;
                    font-size: 50px;">
                        <i class="fa fa-exclamation"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="row">

        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-center">پرفروش ترین محصولات</h5>
                    <hr>
                </div>
              
            <div class="col-md-10 offset-md-1">
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="chart_demo_4"></canvas>
                    </div>

                </div>
            </div>
           

        </div>
    </div>

</div>

</div>
@endsection

@section('js')
<script src="{{route('BaseUrl')}}/Pannel/assets/vendors/charts/chart.min.js"></script>
<script>
    Chart.defaults.global.defaultFontFamily = '"primary-font", "segoe ui", "tahoma"';
var chartColors = {
    primary: {
        base: '#3f51b5',
        light: '#c0c5e4'
    },
    danger: {
        base: '#f2125e',
        light: '#fcd0df'
    },
    success: {
        base: '#0acf97',
        light: '#cef5ea'
    },
    warning: {
        base: '#ff8300',
        light: '#ffe6cc'
    },
    info: {
        base: '#00bcd4',
        light: '#e1efff'
    },
    dark: '#37474f',
    facebook: '#3b5998',
    twitter: '#55acee',
    linkedin: '#0077b5',
    instagram: '#517fa4',
    whatsapp: '#25D366',
    dribbble: '#ea4c89',
    google: '#DB4437',
    borderColor: '#e8e8e8',
    fontColor: '#999'
};

chart_demo_4();

     function chart_demo_4() {
        if ($('#chart_demo_4').length) {
            var ctx = document.getElementById("chart_demo_4").getContext("2d");
            var densityData = {
                backgroundColor: chartColors.success.base,
                data: ['100','300','50']
            };
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['برنج محسن','پنیر گرینه','ماسک'],
                    datasets: [densityData]
                },
                options: {
                    scaleFontColor: "#FFFFFF",
                    legend: {
                        display: false,
                        labels: {
                            fontColor: chartColors.fontColor
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                color: chartColors.primary.light
                            },
                            ticks: {
                                fontColor: chartColors.dark
                            }
                        }],
                        yAxes: [{
                            gridLines: {
                                color: chartColors.primary.light
                            },
                            ticks: {
                                fontColor: chartColors.warning,
                                min: 0,
                                max:  200,
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });
        }
    }
</script>
@endsection