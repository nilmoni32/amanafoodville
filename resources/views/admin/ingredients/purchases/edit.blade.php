@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-th"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.ingredient.purchase.index', $ingredient->id)}}">{{ __('Ingredient Purchase List') }}</a></li>
    </ul>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-3">
        <div class="tile p-0">
            @include('admin.ingredients.includes.sidebar')
        </div>
    </div>
    <div class="col-md-9">
        <div class="tile">
            <div>
                <h3>Edit the purchase ingredient details</h3>
            </div>
            <hr>
            <div class="tile-body mt-5">
                <form action="{{ route('admin.ingredient.purchase.update') }}" method="POST" role="form">
                    @csrf
                    <div class="tile-body">
                        <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="name">Product Name</label>
                                    <input class="form-control @error('name') is-invalid @enderror" type="text"
                                        placeholder="Enter Ingredient name" id="name" name="name"
                                        value="{{ old('name', $purchase->name) }}" />
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('name')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="price">Price</label>
                                    <input class="form-control @error('price') is-invalid @enderror" type="text"
                                        placeholder="Enter price" id="price" name="price"
                                        value="{{ old('price', $purchase->price) }}" />
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('price')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="quantity">Quantity</label>
                                    <input class="form-control @error('quantity') is-invalid @enderror" type="text"
                                        placeholder="Enter Quantity" id="quantity" name="quantity"
                                        value="{{ old('quantity', $purchase->quantity) }}" />
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('quantity')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="unit">Measurement Unit</label>
                                    <select name="unit" id="unit" class="form-control">
                                        <option></option>
                                        @if($ingredient->measurement_unit == $ingredient->smallest_unit)
                                        <option value={{ $ingredient->measurement_unit }} selected>
                                            {{ $ingredient->measurement_unit }}</option>
                                        @elseif($ingredient->smallest_unit == $purchase->unit)
                                        <option value={{ $ingredient->smallest_unit }} selected>
                                            {{ $ingredient->smallest_unit }}</option>
                                        <option value={{ $ingredient->measurement_unit }}>
                                            {{ $ingredient->measurement_unit }}
                                            @elseif($ingredient->measurement_unit == $purchase->unit)
                                        <option value={{ $ingredient->measurement_unit }} selected>
                                            {{ $ingredient->measurement_unit }}</option>
                                        <option value={{ $ingredient->smallest_unit }}>{{ $ingredient->smallest_unit }}
                                        </option>
                                        @endif

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row pb-2">
                            <div class="col-md-6">
                                <div class="form-group my-2">
                                    <label class="control-label" for="purchase_date">Purchase Date</label>
                                    <input type="text" class="form-control datetimepicker" name="purchase_date"
                                        value="{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d-m-Y') }}"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group my-2">
                                    <label class="control-label" for="expire_date">Expiry Date</label>
                                    <input type="text" class="form-control datetimepicker" name="expire_date"
                                        value="{{ \Carbon\Carbon::parse($purchase->expire_date)->format('d-m-Y') }}"
                                        required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tile-footer">
                        <div class="row d-print-none mt-2">
                            <div class="col-12 text-right">
                                <button class="btn btn-success" type="submit"><i
                                        class="fa fa-fw fa-lg fa-check-circle"></i>Update
                                    Purchase Ingredient</button>
                                <a class="btn btn-danger"
                                    href="{{ route('admin.ingredient.purchase.index', $ingredient->id) }}"><i
                                        class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
    $('.datetimepicker').datetimepicker({
        timepicker:false,
        datepicker:true,        
        format: 'd-m-Y',              
    });
    $(".datetimepicker").attr("autocomplete", "off");

    $('#unit').select2({
                placeholder: "Select an measurement Unit",              
                multiple: false, 
                minimumResultsForSearch: -1,  
                width: '100%',                       
             });

    });
</script>
@endpush
