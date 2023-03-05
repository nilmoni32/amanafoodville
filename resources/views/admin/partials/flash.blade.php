{{-- getting the flash messages of all types from the Laravel session --}}
{{-- showing the different type of bootstrap alerts based on the flash message type --}}
@php $errors = Session::get('error');
$info = Session::get('info');
$messages = Session::get('success');
$warnings = Session::get('warning');
@endphp

@if($errors)
    @foreach($errors as $key => $value)
    <div class="alert alert-danger alert-dismissible" role="alert">    
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Error!</strong>{{ $value }}
        </button>
    </div>
    @endforeach
@endif

@if($messages)
    @foreach($messages as $key => $value)
    <div class="alert alert-success alert-dismissible" role="alert">    
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Success!</strong>{{ $value }}
        </button>
    </div>
    @endforeach
@endif

@if($info)
    @foreach($info as $key => $value)
    <div class="alert alert-info alert-dismissible" role="alert">    
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Info!</strong>{{ $value }}
        </button>
    </div>
    @endforeach
@endif

@if($warnings)
    @foreach($warnings as $key => $value)
    <div class="alert alert-warning alert-dismissible" role="alert">    
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Warning!</strong>{{ $value }}
        </button>
    </div>
    @endforeach
@endif