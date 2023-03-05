@extends('admin.app')
@section('title') {{ $subTitle }} @endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-th"></i> {{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <a href="{{ route('admin.ingredient.create') }}" class="btn btn-primary pull-right">Add Ingredient</a>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Name </th>
                            <th class="text-center"> Category</th>
                            <th class="text-center"> Stock Unit</th>
                            <th class="text-center"> Qty </th>
                            <th class="text-center"> Price </th>
                            <th class="text-center"> Alert Qty </th>
                            <th class="text-center"> Small Unit</th>
                            <th class="text-center"> S.U. Cost</th>
                            <th class="text-center text-danger"><i class="fa fa-bolt"> </i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingredients as $ingredient)
                        <tr>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $loop->index + 1  }}
                            </td>
                            <td style="padding: 0.5rem; vertical-align: 0 ;">
                                @if($ingredient->pic)
                                <img src="{{ asset('/storage/images/'. $ingredient->pic)}}" width="40" class="mr-1">
                                @endif
                                {{ $ingredient->name }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <span class="badge badge-info">{{ $ingredient->typeingredient->name}}</span>
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $ingredient->measurement_unit }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $ingredient->total_quantity }} {{ $ingredient->measurement_unit }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $ingredient->total_price }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $ingredient->alert_quantity }} {{ $ingredient->measurement_unit }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $ingredient->smallest_unit }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $ingredient->smallest_unit_price }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.ingredient.edit', $ingredient->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    {{-- <a href="{{ route('admin.ingredient.delete', $ingredient->id) }}"
                                        class="btn btn-sm btn-danger delete-confirm {{
                                            App\Models\IngredientPurchase::where('ingredient_id', $ingredient->id)->count()
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
