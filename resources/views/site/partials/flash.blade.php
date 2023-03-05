{{-- we need to three things here; error, session success and session error--}}
@if($errors->any())
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <ul>
                    @foreach ($errors->all() as $error)
                    <p>{{$error}}</p>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif

@if(Session::has('success'))
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-success">
                <p>{{ Session::get('success') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

@if(Session::has('error'))
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-danger">
                <p>{{ Session::get('error') }}</p>
            </div>
        </div>
    </div>
</div>
@endif

@if ($message = Session::get('warning'))
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-warning alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        </div>
    </div>
</div>
@endif


@if ($message = Session::get('info'))
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-info alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        </div>
    </div>
</div>
@endif