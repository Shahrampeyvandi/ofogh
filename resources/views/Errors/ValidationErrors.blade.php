@if(\Session::has('success'))
<div class="alert alert-success">
    <p>
        {{\Session::get('success')}}
    </p>
</div>
@endif

@if(\Session::has('Error'))
<div class="alert alert-danger">
    <p>
        {{\Session::get('Error')}}
    </p>
</div>
@endif