@extends('admin.app')
@section('title') {{ $subTitle }} @endsection
{{-- @section('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"> 
@endsection --}}
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-th"></i> {{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <a href="{{ route('admin.supplier.requisition.create') }}" class="btn btn-primary pull-right">Create Supplier Requisition</a>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> Requisition No </th>
                            <th class="text-center"> Date </th>
                            <th class="text-center"> Supplier </th>
                            <th class="text-center"> Total Quantity</th>  
                            <th class="text-center"> Total Cost</th>                                                      
                            <th class="text-center"> Remarks</th>
                            <th class="text-center text-danger"><i class="fa fa-bolt"> </i></th>
                        </tr>
                    </thead>
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

    // DataTable
    $('#sampleTable').DataTable({
         processing: true,
         serverSide: true,
         ajax: "{{route('admin.supplier.requisitions')}}", 
         columns: [
            { data: 'id', className: 'text-center' },
            { data: 'requisition_date', className: 'text-center' },  
            { data: 'supplier_id', className: 'text-center' },                                
            { data: 'total_quantity', className: 'text-center' },
            { data: 'total_amount', className: 'text-center' },
            { data: 'remarks', className: 'text-center' },
            { data: 'action', className: 'text-center'},            
         ]
      });


	// $('.delete-confirm').on('click', function (event) {
    //     event.preventDefault();
    //     const url = $(this).attr('href');
    //     swal({
    //         title: 'Are you sure?',
    //         text: 'This record and it`s details will be permanantly deleted!',
    //         icon: 'warning',
    //         buttons: true,
    //         buttons: ["Cancel", "Yes!"],
    //     }).then(function(value) {
    //         if (value) {
    //             window.location.href = url;
    //         }
    //     });
    // });
    // $('#sampleTable').DataTable();
</script>
@endpush
