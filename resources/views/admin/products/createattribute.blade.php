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
                <h3>Add Attributes for the current food menu</h3>
            </div>
            <form action=" {{ route('admin.products.attribute.store') }} " method="POST" role="form"
                enctype="multipart/form-data">
                @csrf
                <div class="tile-body mt-5">
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="form-group">
                        <label class="control-label" for="size">Size</label>
                        <input class="form-control @error('size') is-invalid @enderror" type="text"
                            placeholder="Enter Size" id="size" name="size" value="{{ old('size') }}" />
                        <div class="invalid-feedback active">
                            <i class="fa fa-exclamation-circle fa-fw"></i> @error('size')
                            <span>{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="price">Price</label>
                                <input class="form-control @error('price') is-invalid @enderror" type="text"
                                    placeholder="Enter price" id="price" name="price" value="{{ old('price') }}" />
                                <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('price')
                                    <span>{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="special_price">Special Price</label>
                                <input class="form-control" type="text" placeholder="Enter special price"
                                    id="special_price" name="special_price" value="{{ old('special_price') }}" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tile-footer">
                    <div class="row d-print-none mt-2">
                        <div class="col-12 text-right">
                            <button class="btn btn-success" type="submit"><i
                                    class="fa fa-fw fa-lg fa-check-circle"></i>Save Attribute</button>
                            <a class="btn btn-danger"
                                href="{{ route('admin.products.attribute.index', $product->id) }}"><i
                                    class="fa fa-fw fa-lg fa-arrow-left"></i>List all attributes</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection