@extends('admin.app')
@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-object-ungroup"></i>&nbsp;{{$pageTitle}}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="tile">
            <h3 class="tile-title">{{$subTitle}}</h3>
            <form action=" {{ route('admin.ingredientunit.store') }} " method="POST" role="form">
                @csrf
                <div class="tile-body">
                    <div class="form-group">
                        <label class="control-label" for="measurement_unit">Stock Measurement Unit<span
                                class="text-danger"> *</span></label>
                        <input class="form-control @error('measurement_unit') is-invalid @enderror" type="text"
                            name="measurement_unit" id="measurement_unit" value="{{ old('measurement_unit') }}"
                            placeholder="Enter Stock Measurement unit e.g. kg" required>
                        @error('measurement_unit') {{ $message }}@enderror
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="smallest_measurement_unit">Smallest Measurement
                            Unit<span class="text-danger"> *</span></label>
                        <input class="form-control @error('smallest_measurement_unit') is-invalid @enderror" type="text"
                            name="smallest_measurement_unit" id="smallest_measurement_unit"
                            value="{{ old('smallest_measurement_unit') }}"
                            placeholder="Enter Smallest unit of measurement e.g. gm" required>
                        @error('smallest_measurement_unit') {{ $message }}@enderror
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="unit_conversion">Unit Conversion (e.g. 1 kg = 1000gm)<span
                                class="text-danger">
                                *</span></label>
                        <input class="form-control @error('unit_conversion') is-invalid @enderror" type="text"
                            name="unit_conversion" id="unit_conversion" value="{{ old('unit_conversion') }}"
                            placeholder="Enter unit conversion value" required>
                        @error('unit_conversion') {{ $message }}@enderror
                    </div>
                </div>
                <div class="tile-footer pb-5">
                    <div class="pull-right">
                        <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save
                            Measurement Unit</button>
                        &nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="{{ route('admin.ingredientunit.index') }}"><i
                                class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection