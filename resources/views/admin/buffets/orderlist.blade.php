@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-database"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <ul class="app-breadcrumb breadcrumb">
        <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.buffet.menu.index') }}">{{ __('Buffet Menu List') }}</a></li>
    </ul>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <form action="{{ route('admin.buffet.orders.search') }}" method="get">
                    @csrf
                    <div class="row mb-3 mr-4">
                        <div class="col-4 mx-auto">
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search..." name="search">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-search"
                                            aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </form>
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> Order No </th>
                            <th class="text-center"> Order Date</th>
                            <th class="text-center"> Order Table No</th>
                            <th class="text-center"> Paid Amount </th>
                            <th class="text-center"> Payment Type </th>
                            <th class="text-center"> Order Status</th>
                            <th style=" min-width:50px;" class="text-center text-danger"><i class="fa fa-bolt"></i></th>                            
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->order_number }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{-- {{ $order->order_date }} --}}
                                {{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y H:i:s') }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->order_tableNo }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ round($order->grand_total,2) }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ str_replace(',', ', ', $order->payment_method) }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                {{ $order->status }}
                            </td>
                            <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    @if($order->status == 'cancel' || $order->status == 'delivered')
                                    <a href="#" class="btn btn-sm btn-secondary"
                                        style="background-color:rgb(142, 177, 183); border-color:rgb(142, 177, 183);"
                                        disabled><i class="fa fa-edit"></i></a>
                                    @else
                                    <a href="#" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#editCartModal{{ $order->id }}"><i
                                            class="fa fa-edit"></i></a>
                                    <!-- User Cart Modal -->
                                    @include('admin.buffets.orders.includes.editCart')
                                    @endif
                                </div>
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="#" class="btn btn-sm btn-danger" data-toggle="modal"
                                        data-target="#userCartModal{{ $order->id }}"><i
                                            class="fa fa-shopping-basket"></i></a>
                                    <!-- User Cart Modal -->
                                    @include('admin.buffets.orders.includes.userCart')                               
                                </div>
                            </td>
                            {{-- <td class="text-center" style="padding: 0.5rem; vertical-align: 0 ;">
                                
                            </td>                             --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="pt-4 text-right">
    {{ $orders->links() }}
</div>
@endsection
@push('scripts')
<script type="text/javascript">    

    const CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    
    $(document).ready(function(){
        // changer order status
        $('body').on('change', '.buffetStatus', function(){
            let id = $(this).attr('data-id');
            let status;
            if(this.checked){
                 status = 'receive';
            }else{
                 status = 'cancel';
            }   
              
            $.ajaxSetup({                
                headers: {
                    'X-CSRF-TOKEN': CSRF_TOKEN
                }
            }); 

            jQuery.ajax({
                  url: "{{ url('/admin/buffet/menu/order/status') }}",
                  method: 'post',
                  data: {
                    id: id,
                    status: status                    
                  },
                  success: function(data){                    
                      if(data.status == 'success' && status == 'cancel') {                  
                        $('.buffetStatus').prop('disabled', function () {
                            return ! $(this).prop('disabled');
                        });
                      }
                  }
            });
         

        }); 

    });
</script>
@endpush

