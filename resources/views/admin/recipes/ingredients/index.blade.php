@extends('admin.app')
@section('title') {{ $subTitle }} @endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-modx"></i> {{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.recipe.index') }}">{{ __('Recipe List') }}</a></li>
    </ul>
    {{-- <a href="{{ route('admin.recipe.ingredient.create', $recipe->id) }}" class="btn btn-primary pull-right">Add
    Ingredient</a> --}}
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-3">
        <div class="tile p-0">
            @include('admin.recipes.includes.sidebar')
        </div>
    </div>
    <div class="col-md-9">
        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Ingredient Name </th>
                            <th class="text-center"> Qty </th>
                            <th class="text-center"> P.U Cost </th>
                            <th class="text-center"> Total Cost </th>
                            <th class="text-center text-danger"><i class="fa fa-bolt"> </i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recipeIngredients as $recipeIngredient)
                        <tr>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $loop->index + 1  }}
                            </td>
                            <td style="padding: 0.5rem; vertical-align: 0 ;">
                                @if($recipeIngredient->ingredient->pic)
                                <img src="{{ asset('/storage/images/'. $recipeIngredient->ingredient->pic)}}" width="40"
                                    class="mr-1">
                                @endif
                                {{ $recipeIngredient->ingredient->name }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $recipeIngredient->quantity }} {{ $recipeIngredient->measure_unit }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $recipeIngredient->unit_price }} {{ config('settings.currency_symbol') }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $recipeIngredient->ingredient_total_cost }} {{ config('settings.currency_symbol') }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.recipe.ingredient.edit', $recipeIngredient->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('admin.recipe.ingredient.delete', $recipeIngredient->id) }}"
                                        class="btn btn-sm btn-danger delete-confirm"><i class="fa fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- we need to add  @stack('scripts') in the app.blade.php for the following scripts --}}
@push('scripts')
<script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
<script type="text/javascript">
	$('.delete-confirm').on('click', function (event) {
        event.preventDefault();
        const url = $(this).attr('href');
        swal({
            title: 'Are you sure?',
            text: 'This record and it`s details will be permanantly deleted!',
            icon: 'warning',
            buttons: true,
            buttons: ["Cancel", "Yes!"],
        }).then(function(value) {
            if (value) {
                window.location.href = url;
            }
        });
    });
    $('#sampleTable').DataTable();
</script>
@endpush
