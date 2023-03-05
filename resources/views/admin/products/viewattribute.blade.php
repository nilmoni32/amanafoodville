@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-cutlery"></i> {{ $pageTitle }} - {{ $subTitle }}</h1>
    </div>
</div>
@include('admin.partials.flash')
<div class="row user">
    <div class="col-md-3">
        <div class="tile p-0">
            <ul class="nav flex-column nav-tabs user-tabs">
                <li>
                    <a class="app-menu__item bg-white text-dark {{ Route::currentRouteName() == 'admin.products.edit' ? 'active' : ''}}"
                        href="{{ route('admin.products.edit', $product->id) }}">
                        <span class="app-menu__label">General</span>
                    </a>
                </li>
                <li class="treeview">
                    <a class="app-menu__item bg-white text-dark" href="#" data-toggle="treeview">
                        <span class="app-menu__label">Attributes</span>
                        <i class="treeview-indicator fa fa-angle-right"></i>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a class="treeview-item bg-white text-dark"
                                href="{{ route('admin.products.attribute.index', $product->id)}}">List all
                                Attributes</a>
                        </li>
                        <li>
                            <a class="treeview-item bg-white text-dark"
                                href="{{ route('admin.products.attribute.create', $product->id)}}">Add Attribute</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tile mt-4">
            <div>
                <h3>List all Attributes for the current food menu</h3>
            </div>
            <div class="tile-body mt-5">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Product Name </th>
                            <th class="text-center"> Size </th>
                            <th class="text-center"> Price </th>
                            <th class="text-center"> Discount Price </th>
                            <th style="width:100px; min-width:100px;" class="text-center text-danger"><i
                                    class="fa fa-bolt"> </i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attributes as $attribute)
                        <tr>
                            <td class="text-center">{{ $attribute->id }}</td>
                            <td class="text-center">{{ $attribute->product->name }}</td>
                            <td class="text-center">{{ $attribute->size }}</td>
                            <td class="text-center">{{ $attribute->price }}</td>
                            <td class="text-center">{{ $attribute->special_price }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.products.attribute.edit', $attribute->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('admin.products.attribute.delete', $attribute->id) }}"
                                        class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
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