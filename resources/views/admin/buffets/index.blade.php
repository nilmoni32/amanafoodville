@extends('admin.app')
@section('title') {{ $subTitle }} @endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-object-group"></i> {{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <div class="pt-3">
        <a href="{{ route('admin.buffet.menu.create') }}" class="btn btn-primary pull-right ml-2">Create New Buffet</a>
        <a href="{{ route('admin.buffet.menu.listorder') }}" class="btn btn-primary pull-right">Buffet Order List</a>
    </div>    
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
                            <th class="text-center"> Buffet Name </th>
                            <th class="text-center"> Buffet Guest List </th>
                            <th class="text-center"> Production Cost</th>
                            <th class="text-center"> P. Unit Cost</th>
                            <th class="text-center"> No. of guests is served </th>                            
                            <th class="text-center"> Total Sale Price</th>
                            <th class="text-center"> Unit Sale Price</th>                           
                            <th class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buffets as $buffet)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1  }}</td>                            
                            <td class="text-center">{{ $buffet->buffet_name}}</td>
                            <td class="text-center">{{ $buffet->buffet_guest_list }}</td>
                            <td class="text-center">{{ round($buffet->unit_cost_price * $buffet->buffet_guest_list,2) }}</td>
                            <td class="text-center">{{ $buffet->unit_cost_price }}</td>
                            <td class="text-center">{{ $buffet->buffet_guest_list_served }}</td>
                            <td class="text-center">{{ round($buffet->unit_sale_price * $buffet->buffet_guest_list_served,2)  }}</td>                            
                            <td class="text-center">{{ $buffet->unit_sale_price }}</td> 
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.buffet.menu.edit', $buffet->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>                                   
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
    $('#sampleTable').DataTable();
</script>
@endpush
