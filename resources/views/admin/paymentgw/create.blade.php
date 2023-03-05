@extends('admin.app')
@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-credit-card"></i>&nbsp;{{$pageTitle}}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="tile">
            <h3 class="tile-title text-center">{{$subTitle}}</h3>
            <hr>
            <form action=" {{ route('admin.payment.gw.store') }} " method="POST" role="form">
                @csrf
                <div class="tile-body">
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label class="control-label" for="bank_name">Bank Name<span class="text-danger"> *</span></label>
                            <input class="form-control @error('bank_name') is-invalid @enderror" type="text" name="bank_name"
                                id="bank_name" value="{{ old('bank_name') }}" placeholder="Enter Bank Name" required>
                            @error('bank_name') {{ $message }}@enderror
                        </div>
                    </div>
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label for="bank_type">Gateway Type<span class="text-danger"> *</span></label>
                            <select class="form-control custom-select mt-15 @error('bank_type') is-invalid @enderror"
                                id="bank_type" name="bank_type" required>
                                <option value="" disabled selected>Select a GW Type</option>
                                @foreach(['card', 'mobile'] as $gw_type)
                                <option value="{{ $gw_type }}">{{ $gw_type }}</option>
                                @endforeach
                            </select>
                            @error('bank_type') {{ $message }} @enderror
                        </div>
                    </div>
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label class="control-label" for="discount_percent">Discount Percentage
                                (%)<span class="text-danger">
                                    *</span></label>
                            <input class="form-control @error('discount_percent') is-invalid @enderror"
                                type="text" name="discount_percent" id="discount_percent"
                                value="{{ old('discount_percent') }}"
                                placeholder="Enter discount percentage" required>
                            @error('discount_percent') {{ $message }}@enderror
                        </div>
                    </div>
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label class="control-label" for="discount_lower_limit">Discount Lower Limit
                                (Tk)<span class="text-danger">
                                    *</span></label>
                            <input class="form-control @error('discount_lower_limit') is-invalid @enderror"
                                type="text" name="discount_lower_limit" id="discount_lower_limit"
                                value="{{ old('discount_lower_limit', 0) }}"
                                placeholder="Discount Lower Limit">
                            @error('discount_lower_limit') {{ $message }}@enderror
                        </div>
                    </div>
                    <div class="col-md-8 mx-auto">
                        <div class="form-group">
                            <label class="control-label" for="discount_upper_limit">Discount Upper Limit
                                (Tk)<span class="text-danger">
                                    *</span></label>
                            <input class="form-control @error('discount_upper_limit') is-invalid @enderror"
                                type="text" name="discount_upper_limit" id="discount_upper_limit"
                                value="{{ old('discount_upper_limit') }}"
                                placeholder="Discount Upper Limit" required>
                            @error('discount_upper_limit') {{ $message }}@enderror
                        </div>
                    </div>
                </div>
                <div class="tile-footer pb-5">
                    <div class="text-center">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Add
                            Payment GW</button>
                        &nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="{{ route('admin.payment.gw.index') }}"><i
                                class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection