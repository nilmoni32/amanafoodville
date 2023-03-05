@extends("admin.app")
@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection
@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-bar-chart"></i>&nbsp;{{ $pageTitle }}</h1>
        <p class="h6 pt-2 pb-0">Edit Order Status</p>
    </div>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="tile">
            <h6 class="tile-title mb-2">{{$subTitle}}</h6>
            <span class="h6">[ Placed By: {{ $order->user->name }} ]</span>
            <form action=" {{ route('admin.orders.update') }} " method="POST" role="form" enctype="multipart/form-data">
                @csrf

                <div class="tile-body mt-4">
                    <div class="form-group">
                        <label class="control-label" for="order_date"> Order Date:</label>
                        <input class="form-control" type="text" name="order_date" id="order_date"
                            value="{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i:s') }}" readonly>

                    </div>
                    <div class="form-group">
                        <label class="control-label" for="delivery_date"> Delivery Date:<span class="text-danger">
                                *</span></label>
                        <input class="form-control" type="text" name="delivery_date" id="delivery_date"
                            value="{{  date('d-m-Y', strtotime($order->delivery_date )) }}" readonly>
                    </div>
                    <div class="form-group">
                        <label for="status">Change Order Status:<span class="text-danger">*</span></label>
                        <select class="form-control custom-select mt-15 @error('status') is-invalid @enderror"
                            id="status" name="status">
                            <option value="0">Select an Order Status</option>
                            @foreach(['pending','accept', 'cooking','packing', 'delivered', 'cancel'] as $order_status)
                            <option value="{{ $order_status }}" {{ $order_status==$order->status ? "selected": "" }}>
                                {{ $order_status }}</option>
                            @endforeach
                        </select>
                        @error('status') {{ $message }} @enderror
                    </div>
                    <input type="hidden" name="id" value="{{ $order->id }}">
                    <div class="tile-footer text-right">
                        <button class="btn btn-primary" type="submit"><i
                                class="fa fa-fw fa-lg fa-check-circle"></i>Update Order Status</button>

                        &nbsp;&nbsp;&nbsp;<a class="btn btn-danger" href="{{ route('admin.orders.index') }}"><i
                                class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                    </div>
            </form>
        </div>
    </div>
</div>

@endsection