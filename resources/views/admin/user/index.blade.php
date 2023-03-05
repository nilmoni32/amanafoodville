@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
@include('admin.partials.flash')

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="tile">
            <div class="tile-body">
                <h3 class="tile-title">{{ __('List of Funville Users & Roles') }}</h3>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Name </th>
                            <th class="text-center"> Email </th>
                            <th class="text-center"> Roles </th>
                            <th style="width:100px; min-width:100px;" class="text-center text-danger"><i
                                    class="fa fa-bolt"> </i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($admins as $admin)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1  }}</td>
                            <td class="text-center">{{ $admin->name }}</td>
                            <td class="text-center">{{ $admin->email }}</td>
                            <td class="text-center">
                                {{ implode(', ' ,$admin->roles()->get()->pluck('name')->toArray()) }}
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.users.edit', $admin->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('admin.users.destroy', $admin->id) }}"
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
@push('scripts')<script type="text/javascript" src="{{ asset('backend/js/sweetalert.min.js') }}"></script>
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

</script>
@endpush
