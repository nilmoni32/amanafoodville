@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-object-group"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.buffet.menu.index') }}">{{ __('Buffet List') }}</a></li>
    </ul>
</div>
@include('admin.partials.flash')

<div class="row user">
    <div class="col-md-3">
        <div class="tile p-0">
            @include('admin.buffets.includes.sidebar')
        </div>
    </div>
    <div class="col-md-9">
        <div class="tile">
            <h6 class="tile-title h6">Change the food : </h6>
            <hr>
            <form action="{{ route('admin.buffet.recipe.update') }}" method="POST" role="form">
                @csrf
                <div class="tile-body">
                    <input type="hidden" name="buffet_id" value="{{ $buffet->id }}">
                    <input type="hidden" name="buffet_recipe_id" value="{{ $buffet_recipe->id }}">
                    <div class="row">
                        <div class="col-md-7 mx-auto">
                            <div class="form-group">
                                <label class="control-label" for="recipe_name">Food Name</label>
                                <select name="recipe_id" id="recipe_id"
                                    class="form-control @error('recipe_id') is-invalid @enderror">
                                    <option></option>
                                    @foreach(App\Models\Recipe::all() as $recipe)
                                    <option value={{ $recipe->id }}
                                        {{ $recipe->id == $buffet_recipe->recipe_id ? 'selected' : ''}}>
                                        {{ $recipe->product->name}}</option>
                                    @endforeach
                                </select>
                                @error('recipe_id') {{ $message }}@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Update</button>
                            <a class="btn btn-danger"
                                href="{{ route('admin.buffet.recipe.index', $buffet->id) }}"><i
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
<script type="text/javascript">
    // getting CSRF Token from meta tag
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function () {
            $('#recipe_id').select2({
                placeholder: "Select a food",              
                multiple: false, 
                //minimumResultsForSearch: -1,
                width: '100%',                         
             });
            

    });


</script>

@endpush
