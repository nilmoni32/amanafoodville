@extends('admin.app')
@section('title') {{ $subTitle }} @endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-cutlery"></i> {{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary pull-right">Add Food Item</a>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Name </th>
                            <th> Slug </th>
                            <th class="text-center"> Categories </th>
                            <th class="text-center"> Price </th>
                            <th class="text-center"> Special Price </th>
                            <th class="text-center"> Status </th>
                            <th style="min-width:70px;" class="text-center text-danger"><i class="fa fa-bolt"> </i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->slug }}</td>
                            <td>@foreach($product->categories as $category)
                                <span class="badge badge-info">{{ $category->category_name}}</span>
                                @endforeach
                            </td>
                            <td class="text-center">{{ config('settings.currency_symbol') }} {{ $product->price }}</td>
                            <td class="text-center">
                                @if($product->discount_price)
                                {{ config('settings.currency_symbol') }}
                                {{ $product->discount_price }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if($product->status == 1)
                                <span class="badge badge-success">Active</span>
                                @else
                                <span class="badge badge-danger">Not Active</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                        {{-- the food will be deleted only if this food has not placed an order (cartbackup and salebackup) or 
                                        there is no cooresponding food recipe is exist --}}
                                    <a href="{{ route('admin.products.delete', $product->id) }}"
                                        class="btn btn-sm btn-danger {{
                                            App\Models\Recipe::where('product_id', $product->id)->count() ||
                                            App\Models\Cartbackup::where('product_id', $product->id)->count()
                                           || App\Models\Salebackup::where('product_id', $product->id)->count()
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
<script type="text/javascript">
    $('#sampleTable').DataTable();
</script>
@endpush