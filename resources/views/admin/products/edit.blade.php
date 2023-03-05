@extends('admin.app')
@section('title'){{ $pageTitle }}@endsection
{{-- injecting the Dropzone’s required CSS  --}}
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('backend/js/plugins/dropzone/dist/min/dropzone.min.css') }}" />
@endsection
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
            <ul class="nav flex-column nav-tabs user-tabs" id="tabMenu">
                <li class="nav-item"><a class="nav-link active" href="#general" data-toggle="tab">General</a></li>
                <li class="nav-item"><a class="nav-link" href="#images" data-toggle="tab">Images</a></li>
                {{-- <li class="treeview">
                    <a class="app-menu__item bg-white text-primary" href="#" data-toggle="treeview">
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
            </li> --}}
            </ul>
        </div>
    </div>
    <div class="col-md-9">
        <div class="tab-content">
            <div class="tab-pane active" id="general">
                <div class="tile">
                    <form action="{{ route('admin.products.update') }}" method="POST" role="form">
                        @csrf
                        <h3 class="tile-title">Edit Food Item Details</h3>
                        <hr>
                        <div class="tile-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Name</label>
                                        <input class="form-control @error('name') is-invalid @enderror" type="text"
                                            placeholder="Enter name" id="name" name="name"
                                            value="{{ old('name', $product->name) }}" />
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <div class="invalid-feedback active">
                                            <i class="fa fa-exclamation-circle fa-fw"></i> @error('name')
                                            <span>{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="categories">Categories</label>
                                        <select name="categories[]" id="categories" class="form-control" multiple>
                                            @foreach($categories as $category)
                                            {{-- Laravel Pluck method can be very useful when extract certain column values without loading all the columns. --}}
                                            {{-- in_array() function is used to check whether a given value exists in an array or not --}}
                                            @php $check = in_array($category->id,
                                            $product->categories->pluck('id')->toArray()) ? 'selected' : ''
                                            @endphp
                                            <option value="{{ $category->id }}" {{ $check }}>
                                                {{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="price">Price</label>
                                        <input class="form-control @error('price') is-invalid @enderror" type="text"
                                            placeholder="Enter Food price" id="price" name="price"
                                            value="{{ old('price', $product->price) }}" />
                                        <div class="invalid-feedback active">
                                            <i class="fa fa-exclamation-circle fa-fw"></i> @error('price')
                                            <span>{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="discount_price">Discount Price</label>
                                        <input class="form-control @error('discount_price') is-invalid @enderror"
                                            type="text" placeholder="Enter product special price" id="discount_price"
                                            name="discount_price"
                                            value="{{ old('discount_price', $product->discount_price) }}" />
                                        <div class="invalid-feedback active">
                                            <i class="fa fa-exclamation-circle fa-fw"></i> @error('discount_price')
                                            <span>{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>                         
                            <div class="form-group">
                                <label class="control-label" for="description">Description</label>
                                <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', $product->description) }}
                                </textarea>
                            </div>                            
                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" id="status" name="status"
                                            {{ $product->status == 1 ? 'checked' : '' }} />Status
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <label class="form-check-label">
                                        <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                            {{ $product->featured == 1 ? 'checked' : '' }} />Visible only for backend orders
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="tile-footer">
                            <div class="row d-print-none mt-2">
                                <div class="col-12 text-right">
                                    <button class="btn btn-success" type="submit"><i
                                            class="fa fa-fw fa-lg fa-check-circle"></i>Update Item Food</button>&nbsp;
                                    <a class="btn btn-danger" href="{{ route('admin.products.index') }}"><i
                                            class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="tab-pane" id="images">
                <div class="tile">
                    <h3 class="tile-title">Upload Images for the food</h3>
                    <hr>
                    <div class="tile-body">
                        <div class="row">
                            <div class="col-md-12">
                                {{-- Binding a form with dropzone instance --}}
                                <form action="" class="dropzone" id="dropzone"
                                    style="border: 1px dashed rgba(0,0,0,0.3)">
                                    {{-- hidden field for product id, used to send the product id to  controller so the uploaded images can be attached to the current product. --}}
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    {{ csrf_field() }}
                                </form>
                            </div>
                        </div>
                        <div class="row d-print-none mt-2">
                            <div class="col-12 text-right">
                                <button class="btn btn-success" type="button" id="uploadButton">
                                    <i class="fa fa-fw fa-lg fa-upload"></i>Upload Images
                                </button>
                            </div>
                        </div>
                        {{-- we are looping over all product images and showing the image with a link to delete the image. --}}
                        @if ($product->images)
                        <hr>
                        <div class="row">
                            @foreach($product->images as $image)
                            <div class="col-md-3">
                                <div class="card">
                                    <div class="card-body">
                                        <img src="{{ asset('storage/'.$image->full) }}" id="brandLogo" class="img-fluid"
                                            alt="img">
                                        <a class="card-link float-right text-danger"
                                            href="{{ route('admin.products.images.delete', $image->id) }}">
                                            <i class="fa fa-fw fa-lg fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript" src="{{ asset('backend/js/plugins/select2.min.js') }}"></script>
{{-- injecting the Dropzone’s required js  --}}
<script type="text/javascript" src="{{ asset('backend/js/plugins/dropzone/dist/min/dropzone.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/plugins/bootstrap-notify.min.js') }}"></script>
<script>
    //  Disable auto discover for all elements:
    Dropzone.autoDiscover = false;

    $( document ).ready(function() {
        $('#categories').select2(
            { 
            placeholder: " Select Food Category",
            width: '100%',    
            }
        ); 
         // redirect to specific tab
         $('#tabMenu a[href="#{{ old('tab') }}"]').tab('show').removeClass('active');

        // store the currently selected tab in the hash value
        $("ul.nav-tabs > li > a").on("shown.bs.tab", function (e) {
            var id = $(e.target).attr("href").substr(1);
            window.location.hash = id;
        }); 
        // on load of the page: switch to the currently selected tab        
        var hash = window.location.hash;
        $('#tabMenu a[href="' + hash + '"]').tab('show');

        // Added a new Dropzone instance and binding it with #dropzone
        let myDropzone = new Dropzone("#dropzone", {
            paramName: "image", // The name that will be used to transfer the file
            addRemoveLinks: false,
            maxFilesize: 3,  // MB
            parallelUploads: 3,
            uploadMultiple: false,
            //the url property pointing to a route named admin.products.images.upload.
            url: "{{ route('admin.products.images.upload') }}",
            autoProcessQueue: false,
        });
        //queuecomplete event we are reloading the window and showing the success notification.
        myDropzone.on("queuecomplete", function (file) {
            window.location.reload();
            showNotification('Completed', 'All product images uploaded', 'success', 'fa-check');
        });
        $('#uploadButton').click(function(){
            if (myDropzone.files.length === 0) {
                showNotification('Error', 'Please select files to upload.', 'danger', 'fa-close');
            } else {
                myDropzone.processQueue();
            }
        });
        // showNotification() which simply trigger the bootstap notify alerts
        function showNotification(title, message, type, icon)
        {
            $.notify({
                title: title + ' : ',
                message: message,
                icon: 'fa ' + icon
            },{
                type: type,
                allow_dismiss: true,
                placement: {
                    from: "top",
                    align: "center"
                },
            });
        }

    });



    
</script>
@endpush