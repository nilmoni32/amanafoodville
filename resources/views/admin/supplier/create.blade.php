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
    <div class="col-md-9 mx-auto">
        <div class="tile">
            <form action="{{ route('admin.supplier.store') }}" method="POST" role="form"
                enctype="multipart/form-data">
                @csrf
                <h3 class="tile-title">Add Supplier Details</h3>
                <hr>
                <div class="tile-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="name">Supplier Name</label>
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
                                <label class="control-label" for="phone">Supplier Contact</label>
                                <input class="form-control @error('phone') is-invalid @enderror" type="number"
                                    placeholder="Enter Phone no" id="phone" name="phone" value="{{ old('phone') }}" />
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
                                <textarea name="address" id="address" rows="4" class="form-control" @error('address') is-invalid @enderror" placeholder="Enter Address"></textarea>                                
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
                                            <input class="form-check-input" type="radio" name="instantPayment" id="instantPayment1" value="yes">
                                            <label class="form-check-label" for="instantPayment1">Payment before Sale</label>
                                        </div> 
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="instantPayment" id="instantPayment2" value="no" checked>
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
                                            <input class="form-check-input" type="radio" name="activeSupplier" id="activeSupplier1" value="yes" checked>
                                            <label class="form-check-label" for="activeSupplier1">Yes</label>
                                        </div> 
                                    </div>
                                    <div class="col-auto">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="activeSupplier" id="activeSupplier2" value="no">
                                            <label class="form-check-label" for="activeSupplier2">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>          
                        </div> 
                    </div> 
                </div>
                <div class="tile-footer">
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Save Supplier</button>
                            <a class="btn btn-danger" href="{{ route('admin.supplier.index') }}"><i
                                    class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
