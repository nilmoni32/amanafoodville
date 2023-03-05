@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-th"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.ingredient.index') }}">{{ __('Ingredient List') }}</a></li>
    </ul>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-3">
        <div class="tile p-0">
            @include('admin.ingredients.includes.sidebar')
        </div>
    </div>
    <div class="col-md-9">
        <div class="tile">
            <div>
                <h3>Purchase Ingredient List: {{ $ingredient->name }}</h3>
            </div>
            <div class="tile-body mt-5">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover table-bordered" id="purchaseTable">
                            <thead>
                                <tr>
                                    <th class="text-center"> # </th>
                                    <th class="text-center">Ingredient Name</th>
                                    <th class="text-center">Purchase Date</th>
                                    <th class="text-center">Exp Date </th>
                                    <th class="text-center"> Qty </th>
                                    <th class="text-center"> Price</th>
                                    {{-- <th class="text-center"> Added By</th> --}}
                                    <th style="width:100px; min-width:100px;" class="text-center text-danger"><i
                                            class="fa fa-bolt"> </i></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $purchase)
                                <tr>
                                    <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                        {{ $loop->index + 1 }}
                                    </td>
                                    <td style="padding: 0.5rem; vertical-align: 0 ;">
                                        {{ $purchase->name }}</td>
                                    <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                        {{ date('d-m-Y', strtotime($purchase->purchase_date)) }}</td>
                                    <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                        {{ date('d-m-Y', strtotime($purchase->expire_date)) }}</td>
                                    <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                        {{ $purchase->quantity }} {{ $purchase->unit }}</td>
                                    <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                        {{ $purchase->price }}</td>
                                    {{-- <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                        {{ $purchase->added_by }}</td> --}}
                                    <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                        <div class="btn-group" role="group" aria-label="Second group">
                                            <a href="{{ route('admin.ingredient.purchase.edit', $purchase->id) }}"
                                                class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                            {{-- <a href="{{ route('admin.ingredient.purchase.delete', $purchase->id) }}"
                                                class="btn btn-sm btn-danger disabled"><i class="fa fa-trash"></i></a> --}}
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
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
    $('#purchaseTable').DataTable();
</script>
@endpush
