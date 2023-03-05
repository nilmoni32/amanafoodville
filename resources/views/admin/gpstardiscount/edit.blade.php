@extends("admin.app")
@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-mobile fa-2x"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="tile">
            <h3 class="tile-title text-center">{{$subTitle}}</h3>
            <form action=" {{ route('admin.gpstar.update') }} " method="POST" role="form">
                @csrf
                <div class="tile-body">
                    <input type="hidden" name="id" value="{{ $gpstar->id }}">
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label class="control-label" for="gp_star_name">Name<span class="text-danger"> *</span></label>
                            <input class="form-control @error('gp_star_name') is-invalid @enderror" type="text" name="gp_star_name"
                                id="gp_star_name" value="{{ old('gp_star_name', $gpstar->gp_star_name) }}" placeholder="Enter MO Star Name" required>
                            @error('gp_star_name') {{ $message }}@enderror
                        </div>
                    </div>
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label for="bank_type">Status<span class="text-danger"> *</span></label>
                            <select class="form-control custom-select mt-15 @error('status') is-invalid @enderror"
                                id="status" name="status" required>
                                <option value="" disabled selected>Select a status</option>
                                @foreach(['Active', 'Inactive'] as $status_type)
                                <option value="{{ $status_type }}" {{ $status_type == $gpstar->status ? "selected": "" }}>{{ $status_type }}</option>
                                @endforeach
                            </select>
                            @error('status') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label class="control-label" for="discount_percent">Discount Percentage(%)<span class="text-danger">
                                    *</span></label>
                            <input class="form-control @error('discount_percent') is-invalid @enderror"
                                type="text" name="discount_percent" id="discount_percent"
                                value="{{ old('discount_percent', $gpstar->discount_percent ) }}"
                                placeholder="Enter discount slab percentage">
                            @error('discount_percent') {{ $message }}@enderror
                        </div>
                    </div>
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label class="control-label" for="discount_lower_limit">Discount Lower Limit (Tk)<span class="text-danger">
                                    *</span></label>
                            <input class="form-control @error('discount_lower_limit') is-invalid @enderror"
                                type="text" name="discount_lower_limit" id="discount_lower_limit"
                                value="{{ old('discount_lower_limit', round($gpstar->discount_lower_limit, 0)) }}"
                                placeholder="Discount Lower Limit">
                            @error('discount_lower_limit') {{ $message }}@enderror
                        </div>
                    </div>
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label class="control-label" for="discount_upper_limit">Discount Upper Limit (Tk)<span class="text-danger">
                                    *</span></label>
                            <input class="form-control @error('discount_upper_limit') is-invalid @enderror"
                                type="text" name="discount_upper_limit" id="discount_upper_limit"
                                value="{{ old('discount_upper_limit', round($gpstar->discount_upper_limit, 0)) }}"
                                placeholder="Discount Upper Limit">
                            @error('discount_upper_limit') {{ $message }}@enderror
                        </div>
                    </div>
                </div>
                <div class="tile-footer pb-5">
                    <div class="pull-right">
                        <button class="btn btn-primary" type="submit"><i
                                class="fa fa-fw fa-lg fa-check-circle"></i>Update
                            Details</button>
                        &nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="{{ route('admin.gpstar.index') }}"><i
                                class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection