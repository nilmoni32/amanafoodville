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
    <a href="{{ route('admin.supplier.create') }}" class="btn btn-primary pull-right">Add Supplier</a>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Supplier Name </th>
                            <th class="text-center"> Supplier Contact</th>
                            <th class="text-center"> Supplier Address </th>
                            <th class="text-center"> Active Supplier </th>
                            <th style="min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($suppliers as $supplier)
                        <tr>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">{{ $loop->index + 1  }}
                            </td>
                            <td style="padding: 0.5rem; vertical-align: 0 ;">                               
                                {{ $supplier->name }}</td>
                            <td style="padding: 0.5rem; vertical-align: 0 ;">                               
                                {{ $supplier->phone }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $supplier->address }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $supplier->activeSupplier ? 'Yes': 'No'}}</td>
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.supplier.edit', $supplier->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('admin.supplier.delete', $supplier->id) }}"
                                        class="btn btn-sm btn-danger delete-confirm {{
                                            App\Models\SupplierStock::where('supplier_id', $supplier->id)->count()
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
</script>
@endpush
