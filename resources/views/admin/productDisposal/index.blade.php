@extends('admin.app')
@section('title') {{ $subTitle }} @endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-th"></i> {{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <a href="{{ route('admin.product.disposal.create') }}" class="btn btn-primary pull-right">Create Product Disposal</a>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> Disposal No </th>
                            <th class="text-center"> Disposal Date </th>                            
                            <th class="text-center"> Reason</th>
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
         ajax: "{{route('admin.product.disposals')}}", 
         columns: [
            { data: 'id', className: 'text-center' },
            { data: 'created_at', className: 'text-center' },  
            { data: 'reason', className: 'text-center' },
            { data: 'action', className: 'text-center'},            
         ]
      });
	
</script>
@endpush
