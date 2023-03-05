@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-object-ungroup"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <a href="{{ route('admin.ingredientunit.create') }}" class="btn btn-primary pull-right">Add Measurement Unit</a>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Stock Measurement Unit </th>
                            <th class="text-center"> Smallest Measurement Unit </th>
                            <th class="text-center"> Unit Conversion </th>
                            <th style="width:100px; min-width:100px;" class="text-center text-danger"><i
                                    class="fa fa-bolt"> </i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingredient_units as $unit)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1  }}</td>
                            <td class="text-center">{{ $unit->measurement_unit }}</td>
                            <td class="text-center">{{ $unit->smallest_measurement_unit }}</td>
                            <td class="text-center">{{ $unit->unit_conversion }} {{ $unit->smallest_measurement_unit }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.ingredientunit.edit', $unit->id) }}" class="btn btn-sm btn-primary {{
                                            App\Models\Ingredient::where('measurement_unit', $unit->measurement_unit)->count()
                                            ? 'disabled' :'' }}"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('admin.ingredientunit.delete', $unit->id) }}" class="btn btn-sm btn-danger delete-confirm {{
                                            App\Models\Ingredient::where('measurement_unit', $unit->measurement_unit)->count()
                                            ? 'disabled' :'' }}"><i class="fa fa-trash"></i></a>
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
    // to disable anchor link.
    
</script>
@endpush
