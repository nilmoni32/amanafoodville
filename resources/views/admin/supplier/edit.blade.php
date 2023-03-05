@extends("admin.app")
@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-ils"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-9 mx-auto">
        <div class="tile">
            <h3 class="tile-title text-center">{{$subTitle}}</h3>
            <form action=" {{ route('admin.supplier.update') }} " method="POST" role="form">
                @csrf
                <h3 class="tile-title">Edit Supplier Details</h3><hr>
                <input type="hidden" name="id" value="{{ $supplier->id }}">
                <div class="tile-body"> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="name">Supplier Name<span class="text-danger"> *</span></label>
                                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                                    id="name" value="{{ old('name', $supplier->name) }}" placeholder="Enter Name" required>
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('name')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="phone">Supplier Contact<span class="text-danger"> *</span></label>
                                <input class="form-control @error('phone') is-invalid @enderror" type="number"
                                    placeholder="Enter Phone no" id="phone" name="phone" value="{{ old('phone', $supplier->phone ) }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('phone')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="address">Supplier Address</label>
                                <textarea name="address" id="address" rows="4" class="form-control" @error('address') is-invalid @enderror" placeholder="Enter Address">{{ old('address', $supplier->address) }}</textarea>                                
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('address')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-6"> 
                            <div class="form-group">
                                <label class="control-label" for="instantPayment">Payment Option</label>
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="instantPayment" id="instantPayment1" value="yes" 
                                            {{ (isset($supplier->instantPayment) && $supplier->instantPayment == 1 ) ? 'checked' : '' }} >
                                            <label class="form-check-label" for="instantPayment1">Payment before Sale</label>
                                        </div> 
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="instantPayment" id="instantPayment2" value="no"
                                            {{ (isset($supplier->instantPayment) && $supplier->instantPayment == 0 ) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="instantPayment2">Payment After Sale</label>
                                        </div>
                                    </div>
                                </div>                            
                            </div>                                                        
                            <div class="form-group">
                                <label class="control-label" for="phone">Active Supplier</label>
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="activeSupplier" id="activeSupplier1" value="yes" 
                                            {{ (isset($supplier->activeSupplier) && $supplier->activeSupplier == 1 ) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="activeSupplier1">Yes</label>
                                        </div> 
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="activeSupplier" id="activeSupplier2" value="no"
                                            {{ (isset($supplier->activeSupplier) && $supplier->activeSupplier == 0 ) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="activeSupplier2">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>          
                        </div> 
                    </div>
                </div>
                <div class="tile-footer pb-5">
                    <div class="pull-right">
                        <button class="btn btn-primary" type="submit"><i
                                class="fa fa-fw fa-lg fa-check-circle"></i>Update Details</button>
                        &nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="{{ route('admin.supplier.index') }}"><i
                                class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection