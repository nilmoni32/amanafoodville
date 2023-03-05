@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-object-group"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-10 mx-auto">
        <div class="tile">
            <form action="{{ route('admin.buffet.menu.store') }}" method="POST" role="form"
                enctype="multipart/form-data">
                @csrf
                <h3 class="tile-title">Add Buffet Details</h3>
                <hr>
                <div class="tile-body">
                    <div class="row">
                        <div class="offset-md-2"></div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label" for="buffet_name">Buffet Name</label>
                                <input class="form-control @error('buffet_name') is-invalid @enderror" type="text"
                                    placeholder="Enter name" id="buffet_name" name="buffet_name" value="{{ old('buffet_name') }}" />
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
                                    placeholder="Enter Number of guests being served" id="buffet_guest_list" name="buffet_guest_list" value="{{ old('buffet_guest_list') }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('buffet_guest_list')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="offset-md-2"></div>
                    </div>
                    
                </div>
                <div class="tile-footer">
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Save Buffet</button>
                            <a class="btn btn-danger" href="{{ route('admin.buffet.menu.index') }}"><i
                                    class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
