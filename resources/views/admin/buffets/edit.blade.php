@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-object-group"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.buffet.menu.index') }}">{{ __('Buffet Menu List') }}</a></li>
    </ul>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-2">
        <div class="tile p-0">
            @include('admin.buffets.includes.sidebar')
        </div>
    </div>
    <div class="col-md-10">
        <div class="tile px-5">
            <div>
                <h3 class="tile-title">Update the Buffet details</h3>
            </div>
            <hr>
            <div class="tile-body mt-3">
                <form action="{{ route('admin.buffet.menu.update') }}" method="POST" role="form"
                    enctype="multipart/form-data">
                    @csrf 
                    <input type="hidden" name="buffet_id" value="{{ $buffet->id }}">
                    <div class="tile-body">                        
                        <div class="row">                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="buffet_name">Buffet Name</label>
                                    <input class="form-control @error('buffet_name') is-invalid @enderror" type="text"
                                        placeholder="Enter name" id="buffet_name" name="buffet_name" value="{{ old('buffet_name', $buffet->buffet_name) }}" />
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('buffet_name')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="buffet_guest_list">Buffet Guest List</label>
                                    <input class="form-control @error('buffet_guest_list') is-invalid @enderror" type="text"
                                        placeholder="Enter Number of guests being served" id="buffet_guest_list" name="buffet_guest_list" value="{{ old('buffet_guest_list', $buffet->buffet_guest_list) }}" />
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('buffet_guest_list')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label" for="unit_sale_price">Set Unit Sale Price</label>
                                    <input class="form-control @error('unit_sale_price') is-invalid @enderror" type="text"
                                        placeholder="Set Buffet Unit Sale Price" id="unit_sale_price" name="unit_sale_price" value="{{ old('unit_sale_price',$buffet->unit_sale_price) }}" />
                                    <div class="invalid-feedback active">
                                        <i class="fa fa-exclamation-circle fa-fw"></i> @error('unit_sale_price')
                                        <span>{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>                            
                        </div>
                        
                    </div>

                    <div class="tile-footer">
                        <div class="row d-print-none mt-2">
                            <div class="col-12 text-right">
                                <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                                <a class="btn btn-danger" href="{{ route('admin.buffet.menu.index') }}"><i
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

