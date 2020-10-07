@extends('Layouts.Pannel.Template')

@section('css')

<link rel="stylesheet" href="{{route('BaseUrl')}}/Pannel/assets/css/persian-datepicker.min.css" type="text/css">


@endsection
@section('content')


     {{-- filtering --}}
     <div class="card filtering container-fluid" >
       
        <div class="card-body" style="height:350px">
          <div class="card-title">
            <h5 class="text-center">مسیر حرکت خدمت رسان ها</h5>
            <hr>
          </div>
          <div class="row " >
            <div class="form-group col-md-6">
                <form method="GET">
              <label for="recipient-name" class="col-form-label">انتخاب خدمت رسان</label>

              <select name="personal" id="personals_type"  class="js-example-basic-single" dir="rtl">
                <option></option>


                @foreach($khedmatResans as $khedmatresann)
                                            <option value="{{$khedmatresann->id}}"
                                              @if (!empty($khedmatResan))

                                              @if ($khedmatresann->id == $id[0])
                                              selected="selected"
                                              @endif
                                              @endif

                                              > {{$khedmatresann->personal_firstname}} {{$khedmatresann->personal_lastname}} - {{$khedmatresann->personal_mobile}}</option>
                                        @endforeach
            </select>
          </div>
          <div class="form-group col-md-6">
            <label for="recipient-name" class="col-form-label">تاریخ: </label>
            {{-- <input type="text" name="date" class="form-control date" id="date"


            @if (!empty($id))

            value="{{$id[1]}}"

            @endif


           >  
            --}}
           <input type="text" id="date" name="date"
           autocomplete="off"
           class="form-control text-right date-picker-shamsi"

           @if (!empty($id))

            value="{{$id[1]}}"

            @endif

            dir="ltr">


          </div>
          </div>
          <div class="row">
            <div class="form-group col-md-6">


              <button type="submit" class="btn btn-outline-primary">نمایش</button>
              {{-- {{$khedmatResans}} --}}

            
            </form>
        
            </div>
          </div>
        
        </div>
      </div>
  {{-- end filtering --}}

  @if (!empty($id[0]))

     {{-- map --}}
     <div class="card container-fluid">
        <div class="card-body">
          <div class="row" >
            <div class="col-md-12">

                <div id="map" style="width: 100%; height: 500px"></div>

            </div>
          </div>
        </div>
      </div>
  {{-- end map --}}
  @endif




@endsection

@section('js')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin="" />
<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
    integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
    crossorigin=""></script>

    <script src="{{route('BaseUrl')}}/mapmarker/leaflet.polylineDecorator.js"></script>


  {{-- <script src="{{route('BaseUrl')}}/Pannel/assets/js/jquery/jquery.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/js/persian-date.min.js"></script>
    <script src="{{route('BaseUrl')}}/Pannel/assets/js/persian-datepicker.min.js"></script> --}}








    @if (!empty($id[0]))


    <!-- map-->
    <script>
      // Creating map options
      var mapOptions = {
          center: [36.318, 59.576],
          zoom: 11
      }
      // Creating a map object
      var map = new L.map('map', mapOptions);
      // Creating a Layer object
      var layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
     // var layer = new L.TileLayer('http://{s}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png');


     var greenIcon = L.icon({
    iconUrl: '{{route('BaseUrl')}}/mapmarker/marker-icon.png',

    iconSize:     [15, 25], // size of the icon
    iconAnchor:   [7, 20], // point of the icon which will correspond to marker's location
    popupAnchor:  [0, -20] // point from which the popup should open relative to the iconAnchor
    });

    var blueIcon = L.icon({
    iconUrl: '{{route('BaseUrl')}}/mapmarker/marker-icon4.png',

    iconSize:     [30, 30], // size of the icon
    iconAnchor:   [7, 20], // point of the icon which will correspond to marker's location
    popupAnchor:  [7, -25] // point from which the popup should open relative to the iconAnchor
    });


@foreach($servicepositions as $service)
L.marker([{{$service['lat']}}, {{$service['lon']}}],{icon: blueIcon}).addTo(map)
        .bindPopup('<style>table, th, td {border: 1px solid black;  padding: 8px;}</style><table><tr><td>عملیات</td><td>{{$service['type']}}</td></tr><tr><td>ساعت</td><td>{{$service['time']}}</td></tr><tr><td>سفارش</td><td>{{$service['code']}}</td></tr></table>')
        .openPopup();
@endforeach

    var latlngs1 = [];
    var latlngs2 = [];
    var latlngs3 = [];
    var latlngs4 = [];
    var latlngs5 = [];
    var latlngs6 = [];
    var latlngs7 = [];


var number = 1;
     @foreach($khedmatResan as $key=>$position)

     @php
$kes=$key-1;
     @endphp

@if($kes>=0)

@if($position->tool != $khedmatResan[$key-1]->tool && $position->arz != $khedmatResan[$key-1]->arz)
          L.marker([{{$position->tool}}, {{$position->arz}}],{icon: greenIcon}).addTo(map)
        .bindPopup('<style>table, th, td {border: 1px solid black;  padding: 8px;}</style><table><tr><td>شمارش</td><td>{{$key+1}}</td></tr><tr><td>ساعت</td><td>{{\Morilog\Jalali\Jalalian::forge($position->created_at)->format('H:i:s')}}</td></tr><tr><td>تاریخ</td><td>{{\Morilog\Jalali\Jalalian::forge($position->created_at)->format('%Y-%m-%d')}}</td></tr></table>')
        .openPopup();
        @endif


 var hour ={{\Morilog\Jalali\Jalalian::forge($position->created_at)->format('H')}}-{{\Morilog\Jalali\Jalalian::forge($khedmatResan[$kes]->created_at)->format('H')}};
         var min ={{\Morilog\Jalali\Jalalian::forge($position->created_at)->format('i')}}-{{\Morilog\Jalali\Jalalian::forge($khedmatResan[$kes]->created_at)->format('i')}};

       if(hour>1){

        number++;


       }else if(hour>0){

        if(min>-35){
          number++;
        }
        //debugger;


       }else{

if(min>25){
  number++;
}

       }

       @else

       L.marker([{{$position->tool}}, {{$position->arz}}],{icon: greenIcon}).addTo(map)
        .bindPopup('<style>table, th, td {border: 1px solid black;  padding: 8px;}</style><table><tr><td>شمارش</td><td>{{$key+1}}</td></tr><tr><td>ساعت</td><td>{{\Morilog\Jalali\Jalalian::forge($position->created_at)->format('H:i:s')}}</td></tr><tr><td>تاریخ</td><td>{{\Morilog\Jalali\Jalalian::forge($position->created_at)->format('%Y-%m-%d')}}</td></tr></table>')
        .openPopup();
 
@endif









        // if((time)>=1){
        //   number++;
        // }

if(number === 1){

  latlngs1.push([{{$position->tool}},{{$position->arz}}])

}else if(number === 2){

  latlngs2.push([{{$position->tool}},{{$position->arz}}])

}else if(number ===3){
  latlngs3.push([{{$position->tool}},{{$position->arz}}])

}else if(number == 4){
  latlngs4.push([{{$position->tool}},{{$position->arz}}])

}else if (number === 5){
  latlngs5.push([{{$position->tool}},{{$position->arz}}])

}else if (number === 6){
  latlngs6.push([{{$position->tool}},{{$position->arz}}])

}else if (number === 7){
  latlngs7.push([{{$position->tool}},{{$position->arz}}])

}


      @endforeach



      // Adding layer to the map
      map.addLayer(layer);

      var polyline1 = L.polyline(latlngs1, {color: 'blue', weight: 10 , opacity: 0.6}).addTo(map);
     var polyline2 = L.polyline(latlngs2, {color: 'green', weight: 10 , opacity: 0.6}).addTo(map);
     var polyline3 = L.polyline(latlngs3, {color: 'yellow', weight: 10 , opacity: 0.6}).addTo(map);
    var polyline4 = L.polyline(latlngs4, {color: 'purple', weight: 10 , opacity: 0.6}).addTo(map);
      var polyline5 = L.polyline(latlngs5, {color: 'red', weight: 10 , opacity: 0.6}).addTo(map);
     var polyline6 = L.polyline(latlngs6, {color: 'blue', weight: 10 , opacity: 0.6}).addTo(map);
     var polyline7 = L.polyline(latlngs7, {color: 'blue', weight: 10 , opacity: 0.6}).addTo(map);


     var markerPatterns1 = L.polylineDecorator(polyline1, {
        patterns: [
            { offset: '5%', repeat: '10%', symbol:L.Symbol.arrowHead({pixelSize: 8, polygon: false, pathOptions: {color: '#f00',stroke: true}})}
        ]
    }).addTo(map);

    var markerPatterns2 = L.polylineDecorator(polyline2, {
        patterns: [
          { offset: '5%', repeat: '10%', symbol:L.Symbol.arrowHead({pixelSize: 8, polygon: false, pathOptions: {color: '#f00',stroke: true}})}
        ]
    }).addTo(map);


    var markerPatterns3 = L.polylineDecorator(polyline3, {
        patterns: [
          { offset: '5%', repeat: '10%', symbol:L.Symbol.arrowHead({pixelSize: 8, polygon: false, pathOptions: {color: '#f00',stroke: true}})}
        ]
    }).addTo(map);


    var markerPatterns4 = L.polylineDecorator(polyline4, {
        patterns: [
          { offset: '5%', repeat: '10%', symbol:L.Symbol.arrowHead({pixelSize: 8, polygon: false, pathOptions: {color: '#f00',stroke: true}})}
        ]
    }).addTo(map);


    var markerPatterns5 = L.polylineDecorator(polyline5, {
        patterns: [
          { offset: '5%', repeat: '10%', symbol:L.Symbol.arrowHead({pixelSize: 8, polygon: false, pathOptions: {color: '#f00',stroke: true}})}
        ]
    }).addTo(map);


    var markerPatterns6 = L.polylineDecorator(polyline6, {
        patterns: [
          { offset: '5%', repeat: '10%', symbol:L.Symbol.arrowHead({pixelSize: 8, polygon: false, pathOptions: {color: '#f00',stroke: true}})}
        ]
    }).addTo(map);


    var markerPatterns7 = L.polylineDecorator(polyline7, {
        patterns: [
          { offset: '5%', repeat: '10%', symbol:L.Symbol.arrowHead({pixelSize: 8, polygon: false, pathOptions: {color: '#f00',stroke: true}})}
        ]
    }).addTo(map);



    </script>

    @endif
@endsection
