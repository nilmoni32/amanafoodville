@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-th"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-10 mx-auto">
        <div class="tile">
            <form action="{{ route('admin.ingredient.store') }}" method="POST" role="form"
                enctype="multipart/form-data">
                @csrf
                <h3 class="tile-title">Add Ingredient Details</h3>
                <hr>
                <div class="tile-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="name">Ingredient Name</label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text"
                                    placeholder="Enter name" id="name" name="name" value="{{ old('name') }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('name')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="ingredienttypes">Ingredient Types</label>
                                <select name="typeingredient_id" id="ingredienttypes" class="form-control">
                                    @foreach($ingredienttypes as $ingredienttype)
                                    <option value=""></option>
                                    <option value="{{ $ingredienttype->id }}">{{ $ingredienttype->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-3 text-left">
                            <img src="https://via.placeholder.com/500X300?text=Image" width="130" id="beforeUpload">
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label" for="name">Image</label>
                                <input type="file" name="pic" class="form-control @error('pic') is-invalid @enderror"
                                    id="pic">
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('pic')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="measurement_unit">Stock Measurement Unit</label>
                                <select name="measurement_unit" id="measurement_unit" class="form-control">
                                    @foreach($units as $unit)
                                    <option></option>
                                    <option value="{{ $unit->measurement_unit }}">{{ $unit->measurement_unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="name">Alert Quantity</label>
                                <input class="form-control @error('alert_quantity') is-invalid @enderror" type="text"
                                    placeholder="Enter Quantity threshold value" id="alert_quantity"
                                    name="alert_quantity" value="{{ old('alert_quantity') }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('alert_quantity')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="control-label" for="description">Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control"></textarea>
                    </div>
                </div>
                <div class="tile-footer">
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Save Product</button>
                            <a class="btn btn-danger" href="{{ route('admin.ingredient.index') }}"><i
                                    class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $( document ).ready(function() {
             $('#ingredienttypes').select2({
                placeholder: "Select an ingredient types",
               // allowClear: true,
                multiple: false,  
                width: '100%',                        
             });
             $('#measurement_unit').select2({
                placeholder: "Select an measurement Unit",              
                multiple: false, 
                width: '100%',
               // minimumResultsForSearch: -1,                        
             });
            
            //  This code show the image beforeUpload
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = function(e) {
                    $('#beforeUpload').attr('src', e.target.result);
                    }
                    
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }

            $("#pic").change(function() {
                readURL(this);
            });
        });
      
</script>
@endpush