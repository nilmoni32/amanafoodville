@extends('site.app')
@section('title', 'User Dashboard')
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Your Dashboard</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{ route('index')}}">HOME</a></li>
                <li class="list-inline-item"><a href="#">Your Dashboard</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->
<!-- adding session messages -->
<div class="container">

</div>
<!-- User Profile Start -->
<div class="dashboard pb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 commontop text-center">
                <h4>Your Dashboard</h4>
                <div class="divider style-1 center">
                    <span class="hr-simple left"></span>
                    <i class="icofont icofont-ui-press hr-icon"></i>
                    <span class="hr-simple right"></span>
                </div>
            </div>

            <div class="col-md-6 col-sm-12 text-center">
                @if (session('err_msg'))
                <div class="alert alert-error alert-block bg-danger text-white">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('err_msg') }}</strong>
                </div>
                @endif
                @if (session('success_msg'))
                <div class="alert alert-success alert-block bg-success text-white">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success_msg') }}</strong>
                </div>
                @endif

            </div>

            <div class="col-lg-12 col-md-12 user-profile">
                <div class="row">
                    <div class="col-md-4 col-lg-2">
                        <div class="user-profile-tabs">
                            <!--  Menu Tabs Start  -->
                            <ul class="nav nav-tabs flex-column">
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#profile" aria-expanded="true">
                                        <i class="icofont icofont-ui-user"></i>
                                        <span>Your Profile</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#orders" aria-expanded="true">
                                        <i class="icofont icofont-shopping-cart"></i>
                                        <span>Your Orders</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#payments" aria-expanded="true">
                                        <i class="icofont icofont-pay"></i>
                                        <span>Payment History</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#password" aria-expanded="true">
                                        <i class="icofont icofont-ui-password"></i>
                                        <span>Change Password</span>
                                    </a>
                                </li>
                            </ul>
                            <!--  Menu Tabs Start  -->
                        </div>
                    </div>
                    <div class="col-md-8 col-lg-10">
                        <div class="tab-content">
                            <div id="profile" class="tab-pane fade">
                                @include('site.pages.user.includes.profile')
                            </div>
                            <div id="orders" class="tab-pane fade active show">
                                @include('site.pages.user.includes.orders')
                            </div>
                            <div id="payments" class="tab-pane fade">
                                @include('site.pages.user.includes.payment')
                            </div>
                            <div id="password" class="tab-pane fade">
                                @include('site.pages.user.includes.password')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function(){    
    // this is used for orders payment history.    
    $('select[name="year_order"]').on('change',function(){
        var orderDate = $(this).val();
        let j =1;
        if(orderDate){
            $.ajax({
                type:"GET",
                url:"{{ url('user/dashboard/payment-history')}}" +"/" + orderDate,
                dataType : "json",
                success:function(res){                                       
                    if(res){                            
                        $("table#paymentHistory tbody").empty();
                        $.each( res, function( i, val) {                                
                            markup = "<tr><td class='text-center'>" + val.order_number  + "</td>" + 
                                        "<td class='text-center'>" + val.order_date + "</td>" +                                        
                                        "<td class='text-center' style='text-transform:capitalize' >" +'Tk '+ Math.round(val.grand_total) +"</td>" +
                                        "<td class='text-center text-capitalize'>" + val.payment_method + "</td>" +
                                        "<td class='text-center text-capitalize'>" + val.bank_tran_id + "</td>" +
                                        "</tr>"
                            tableBody = $("table#paymentHistory tbody");
                            tableBody.append(markup);    
                            j++;                                    
                        });
                    }
                }    
            }); 
        } 
    }); 
});
</script>
@endpush