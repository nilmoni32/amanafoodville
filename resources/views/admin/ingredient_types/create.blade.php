@extends('admin.app')
@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-ils"></i>&nbsp;{{$pageTitle}}</h1>
    </div>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="tile">
            <h3 class="tile-title">{{$subTitle}}</h3>
            <form action=" {{ route('admin.ingredienttypes.store') }} " method="POST" role="form"
                enctype="multipart/form-data">
                @csrf
                <div class="tile-body">
                    <div class="form-group">
                        <label class="control-label" for="name">Name<span class="text-danger"> *</span></label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" name="name"
                            id="ingredient_types_name" value="{{ old('name') }}">
                        @error('name') {{ $message }}@enderror
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="description">Description</label>
                        <textarea class="form-control" rows="4" name="description"
                            id="description">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="parent">Parent Category<span class="text-danger"> *</span></label>
                        <select class="form-control custom-select mt-15 @error('parent_id') is-invalid @enderror"
                            id="parent" name="parent_id">
                            <option value="0">Select a parent category</option>
                            @foreach($ingredienttypes as $ingredienttype)
                            <option value="{{ $ingredienttype->id }}">{{ $ingredienttype->name }}</option>
                            @endforeach
                        </select>
                        @error('parent_id') {{ $message }} @enderror
                    </div>
                </div>
                <div class="tile-footer">
                    <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Save
                        Category</button>
                    &nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="{{ route('admin.ingredienttypes.index') }}"><i
                            class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection