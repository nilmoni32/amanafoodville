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
    <a href="{{ route('admin.supplier.challan.create') }}" class="btn btn-primary pull-right">Create Supplier Challan</a>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>                            
                            <th class="text-center"> Challan No </th>
                            <th class="text-center"> Requisition No </th>
                            <th class="text-center"> Challan Date </th>
                            <th class="text-center"> Payment Date </th>
                            <th class="text-center"> Supplier </th>
                            <th class="text-center"> Total Quantity </th>  
                            <th class="text-center"> Total Cost </th>
                            <th class="text-center text-danger"><i class="fa fa-bolt"></i></th>
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
         ajax: "{{route('admin.supplier.challans')}}", 
         columns: [
            { data: 'chalan_no', className: 'text-center' },
            { data: 'requisition_to_supplier_id', className: 'text-center' }, 
            { data: 'chalan_date', className: 'text-center' },
            { data: 'payment_date', className: 'text-center' }, 
            { data: 'supplier_id', className: 'text-center' },                                
            { data: 'total_quantity', className: 'text-center' },
            { data: 'total_amount', className: 'text-center' },            
            { data: 'action', className: 'text-center'},            
         ]
      });


	
</script>
@endpush
