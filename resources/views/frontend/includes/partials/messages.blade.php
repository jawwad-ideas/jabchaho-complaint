@if(Session::get('success', false))
    <div class="alert alert-success" role="alert">
        <i class="fa fa-check"></i>
        {{ Session::get('success') }}
    </div>
@endif

@error('error')
    <div class="alert alert-danger">{{$message}}</div>  
@enderror