@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-modx"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.recipe.index') }}">{{ __('Recipe List') }}</a></li>
    </ul>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-3">
        <div class="tile p-0">
            @include('admin.recipes.includes.sidebar')
        </div>
    </div>
    <div class="col-md-9">
        <div class="tile">
            <h3 class="tile-title">Edit Food Recipe</h3>
            <hr>
            <form action="{{ route('admin.recipe.update') }}" method="POST" role="form">
                @csrf
                <div class="tile-body">
                    <div class="row">
                        <div class="col-md-7 mx-auto">
                            <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                            <div class="form-group">
                                <label class="control-label" for="name">Recipe Name</label>
                                {{-- Checking whether ingredient is added to recipe, if true it is disabled recipe name --}}
                                <select name="recipe" id="recipe_id"
                                    class="form-control @error('recipe') is-invalid @enderror" {{
                                        App\Models\RecipeIngredient::where('recipe_id', $recipe->id)->count()
                                        ? 'disabled' :'' }}>
                                    @foreach($products as $product)
                                    @php $check = $recipe->product_id == $product->id ?
                                    'selected' : '';@endphp
                                    <option value={{ $product->id }} {{ $check }}>
                                        {{ $product->name }}</option>
                                    @endforeach
                                </select>
                                @error('recipe') {{ $message }}@enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class=" tile-footer">
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Update Recipe</button>
                            <a class="btn btn-danger" href="{{ route('admin.recipe.index') }}"><i
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
    $(document).ready(function () {
    $('#recipe_id').select2({
                placeholder: "Select a Food Menu",              
                multiple: false, 
                //minimumResultsForSearch: -1, 
                width: '100%',                        
             });

    });
</script>

@endpush
