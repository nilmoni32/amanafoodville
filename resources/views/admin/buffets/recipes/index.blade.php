@extends('admin.app')
@section('title'){{ $subTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-object-group"></i> {{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.buffet.menu.index') }}">{{ __('Buffet List') }}</a></li>
    </ul>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-3">
        <div class="tile p-0">
            @include('admin.buffets.includes.sidebar')
        </div>
    </div>    
    <div class="col-md-9">
        <div class="tile mx-2">                       
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-left"> Food Recipe Name </th>
                            <th class="text-center"> Food Production Cost </th>
                            <th class="text-center"> Food Sale Price </th>
                            <th style="min-width:100px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buffet_foods as $buffet_food)
                        <tr>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">{{ $loop->index + 1  }}
                            </td>
                            <td style="padding: 0.5rem; vertical-align: 0 ;">                                    
                                {{ $buffet_food->recipe->product->name }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ round($buffet_food->recipe_cost_price, 2) }} {{ config('settings.currency_symbol') }}</td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                    {{ round($buffet_food->recipe_sale_price, 2) }} {{ config('settings.currency_symbol') }}</td>
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.buffet.recipe.edit', $buffet_food->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('admin.buffet.recipe.delete', $buffet_food->id) }}"
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
