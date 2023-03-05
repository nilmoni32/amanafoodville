@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-paw"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
@include('admin.partials.flash')

<div class="row justify-content-center">
    <div class="col-sm-12">
        <div class="tile">
            <div class="tile-body">
                <div class="row">
                    <div class="offset-md-3 col-md-6 offset-md-3 text-center py-5">
                        <form method="POST" action="{{ route('admin.due.sales.orderplace') }}">
                            @csrf
                            <div class="border px-4 rounded" style="border-color:rgb(182, 182, 182);">
                                <h4 class="text-center mt-3 mb-5">KOT Due Sells with Advance Payment Option</h4>                                
                                <div class="form-group my-2">
                                    <input type="text" class="form-control @error('booked_money') is-invalid @enderror"
                                        id="booked_money" placeholder="Advance Payment Amount" name="booked_money" required>
                                    @error('booked_money')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                {{-- <div class="form-group my-2">
                                    <input type="text" class="form-control datetimepicker" name="payment_date" placeholder="Choose Payment Date(d-m-Y)" required>
                                </div> --}}
                                {{-- <div class="form-group my-2">
                                    <select name="order_tableNo" id="order_tableNo" class="form-control text-secondary"required>
                                        <option value="" selected disabled>Please Select Order Table No.</option>
                                        @for($i=1; $i<= config('settings.total_tbls'); $i++) <option value="T-{{ $i }}" class="py-3">
                                            Table
                                            No: {{ $i }}</option>
                                        @endfor
                                    </select>
                                </div> --}}
                                <div class="form-group my-2">
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror"
                                        id="customer_name" placeholder="Customer Name" name="customer_name" required>
                                    @error('customer_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>                                
                                <div class="input-group my-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="phone_number">+880</span>
                                    </div>
                                    <input type="text" class="form-control @error('customer_mobile') is-invalid @enderror"
                                        id="customer_mobile" placeholder="Phone no(e.g 017xxxxxxxx)" name="customer_mobile" required>
                                    @error('customer_mobile')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                
                                <div class="form-group my-2">
                                    <textarea class="form-control" id="customer_address" rows="3" name="customer_address"
                                        placeholder="Customer Address"></textarea>
                                </div>

                                <div class="form-group my-2">
                                    <textarea class="form-control" id="customer_notes" rows="3" name="customer_notes"
                                        placeholder="Customer Notes"></textarea>
                                </div>
                                <div class="form-group mt-2 mb-4">
                                    <button type="submit" class="btn btn-primary text-uppercase"
                                        style="display:block; width:100%;" id="submit"
                                        >Create
                                        Order </button>
                                </div>
                                
                            </div>
                        </form>
                    </div>
                    
            </div>
        </div>
    </div>
</div>
</div>
@endsection
@push('scripts')
<script>   
    // getting CSRF Token from meta tag
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function () {
      $('.datetimepicker').datetimepicker({
        timepicker:false,
        datepicker:true,        
        format: 'd-m-Y',              
      });
      $(".datetimepicker").attr("autocomplete", "off");
    });

    $("#customer_mobile").autocomplete({
        //Using source option to send AJAX post request to route('employees.getEmployees') to fetch data
        source: function( request, response ) {
          // Fetch data
          $.ajax({
            url:"{{ route('admin.sales.customermobile') }}",
            type: 'post',
            dataType: "json",
            // passing CSRF_TOKEN along with search value in the data
            data: {
               _token: CSRF_TOKEN,
               search: request.term
            },
            //On successful callback pass response in response() function.
            success: function( data ) {
               response( data );               
            }
          });
        },
        // Using select option to display selected option label in the #product_search
        select: function (event, ui) {
           // Set selection           
           $('#customer_mobile').val(ui.item.label); // display the selected text           
           fillCustomerData(ui.item.label);
           return false;
        }
      });

      function fillCustomerData(customerMobile){
        $.post("{{ route('admin.sales.customerInfo') }}", {        
            _token: CSRF_TOKEN,
            mobile: customerMobile            
        }).done(function(data) { 
            //console.log(data)
            $('#customer_name').val(data[0].name);
            $('#customer_address').val(data[0].address);        
            $('#customer_notes').val(data[0].notes);            
        });
        }


</script>



@endpush
