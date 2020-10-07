@if($errors->any())

    <div class="alert alert-danger">
        @foreach($errors->all() as $error1)
          <p class="m-3">{{$error1}}</p>
        @endforeach
    </div>

@endif
@if(isset($error))
<div class="alert alert-danger">
<p style="color: #ff3d00;" >{{$error}}</p>
</div>
@endif
