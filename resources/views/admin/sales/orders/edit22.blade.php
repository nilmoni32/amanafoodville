@extends("admin.app")
@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-bar-chart"></i>&nbsp;{{ $pageTitle }}</h1>
        <p class="h6 pt-2 pb-0">Edit Order Details</p>
    </div>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="tile">
            <h6 class="tile-title mt-2 mb-3 text-center"><span class="border-top border-bottom p-2">{{$subTitle}}</span>
            </h6>
            <p class="h6 text-center mb-2">[ Placed By: {{ $order->admin->name }} ]</p>
            <p class="h5 mt-4 mb-4 text-center border-bottom pb-3">Edit Order Details</p>
            <form action=" {{ route('admin.pos.orders.update') }} " method="POST" role="form"
                enctype="multipart/form-data">
                @csrf
                <div class="tile-body mx-3">
                    <div class="input-group col-md-8 mx-auto my-2">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="phone_number">Order Table No</span>
                        </div>
                        <input type="text" class="form-control @error('order_tableNo') is-invalid @enderror"
                            id="order_tableNo" placeholder="" name="order_tableNo" value="{{  $order->order_tableNo }}">
                        @error('order_tableNo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-group col-md-8 mx-auto my-2">
                        <label for="status">Change Order Status:<span class="text-danger">*</span></label>
                        <select class="form-control custom-select mt-15 @error('status') is-invalid @enderror"
                            id="status" name="status">
                            <option value="0" disabled>Select an Order Status</option>
                            @foreach(['receive','delivered', 'cancel'] as $order_status)
                            <option value="{{ $order_status }}" {{ $order_status==$order->status ? "selected": "" }}>
                                {{ $order_status }}</option>
                            @endforeach
                        </select>
                        @error('status') {{ $message }} @enderror
                    </div>
                    <input type="hidden" name="id" value="{{ $order->id }}">
                    <div class="tile-footer text-center">
                        <button class="btn btn-primary" type="submit"><i
                                class="fa fa-fw fa-lg fa-check-circle"></i>Update Order</button>

                        &nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="{{ route('admin.pos.orders.index') }}"><i
                                class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                    </div>
            </form>
        </div>
    </div>
</div>

@endsection