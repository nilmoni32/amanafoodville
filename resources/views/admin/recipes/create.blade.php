@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-modx"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-9 mx-auto">
        <div class="tile">
            <h3 class="tile-title">Add Food Recipe</h3>
            <hr>
            <form action="{{ route('admin.recipe.store') }}" method="POST" role="form">
                @csrf
                <div class="tile-body">
                    <div class="row">
                        <div class="col-md-7 mx-auto">
                            <div class="form-group">
                                <label class="control-label" for="name">Recipe Name</label>
                                <select name="recipe" id="recipe_id"
                                    class="form-control @error('recipe') is-invalid @enderror">
                                    <option></option>
                                    @foreach($products as $product)
                                    @if($product->status)
                                    <option value={{ $product->id }}>
                                        {{ $product->name }}</option>
                                    @endif
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
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Save Recipe</button>
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