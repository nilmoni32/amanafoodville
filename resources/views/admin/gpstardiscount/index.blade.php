@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-mobile fa-2x"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <a href="{{ route('admin.gpstar.create') }}" class="btn btn-primary pull-right">Add MO Star Type</a>
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
                            <th class="text-center"> MO Star Name </th>
                            <th class="text-center"> Status </th>
                            <th class="text-center"> Discount Slab (%)</th>
                            <th class="text-center"> Discount Lower Limit</th>
                            <th class="text-center"> Discount Upper Limit</th>
                            <th style="min-width: 50px;" class="text-center text-danger"><i class="fa fa-bolt"> </i>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gpstars as $gpstar)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1  }}</td>
                            <td class="text-center">{{ $gpstar->gp_star_name }}</td>
                            <td class="text-center">{{ $gpstar->status }}</td>
                            <td class="text-center">{{ $gpstar->discount_percent }}</td>
                            <td class="text-center">{{ round($gpstar->discount_lower_limit,2) }}</td>
                            <td class="text-center">{{ round($gpstar->discount_upper_limit,2) }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.gpstar.edit', $gpstar->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('admin.gpstar.delete', $gpstar->id) }}"
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
    // to disable anchor link.
    // a.disabled {
       // pointer-events: none;
       // cursor: default;
    //}
</script>
@endpush
