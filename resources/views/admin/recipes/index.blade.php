@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-modx"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <a href="{{ route('admin.recipe.create') }}" class="btn btn-primary pull-right">Add Food Recipe</a>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-9 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Food Recipe Name </th>
                            <th class="text-center"> Food Production Cost </th>
                            <th class="text-center"> Food Sale Price </th>
                            <th style="min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recipes as $recipe)
                        <tr>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">{{ $loop->index + 1  }}
                            </td>
                            <td style="padding: 0.5rem; vertical-align: 0 ;">
                                @if($recipe->product->images->first())
                                <img src="{!! asset('storage/'.$recipe->product->images->first()->full)  !!}"
                                    title="{{ $recipe->product->name }}" class="img-responsive pr-2 rounded"
                                    width="70px" height="45px" />
                                @endif
                                {{ $recipe->product->name }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ round($recipe->production_food_cost,2) }} {{ config('settings.currency_symbol') }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                 {{ round($recipe->product->price,2) }} {{ config('settings.currency_symbol') }}</td>
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.recipe.edit', $recipe->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    {{-- <a href="{{ route('admin.recipe.delete', $recipe->id) }}"
                                        class="btn btn-sm btn-danger delete-confirm {{
                                            App\Models\RecipeIngredient::where('recipe_id', $recipe->id)->count()
                                            ? 'disabled' :'' }}"><i class="fa fa-trash"></i></a> --}}
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
