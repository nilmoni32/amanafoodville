@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-tags"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="row mb-4">
                    <div class="col-12 pt-3">
                        <form action="{{ route('admin.reports.getCustomerSales') }}" method="post"
                            class="form-inline justify-content-center">
                            @csrf
                            <div class="form-group mb-2 mx-sm-3">
                                <label for="customer">
                                    <span class="font-weight-bold pr-1">Customer :</span>
                                    <select name='customer' style='width: 200px;' id="customer" class="form-control font-weight-normal" required>
                                        <option value=""></option>                                       
                                    </select>
                                </label>
                            </div>
                            <div class="form-group mb-2">
                                <label>
                                    <span class="font-weight-bold pr-1">From Date :</span>
                                    <input type="text" class="form-control datetimepicker" name="start_date"
                                        placeholder="choose date (d-m-Y)" required>
                                </label>
                            </div>
                            <div class="form-group mb-2 mx-sm-3">
                                <label class="font-bold">
                                    <span class="font-weight-bold pr-1">To Date :</span>
                                    <input type="text" class="form-control datetimepicker" name="end_date"
                                        placeholder="choose date (d-m-Y)" required>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary mb-2" name="btnProfitLoss">
                                Preview</button>
                        </form>
                    </div>

                </div>
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> ReceiptNo </th>
                            <th class="text-center"> PaidAmount </th>
                            <th class="text-center"> DiscountAmount (Reference)</th>
                            <th class="text-center"> DiscountAmount (Bonus Point)</th> 
                        </tr>
                    </thead>
                    <tbody>
                      
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script>
    // CSRF Token
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function () {   

      $('#customer').select2({
        placeholder: "Select Customer",        
        //select2-ajax
        ajax: { 
          url: "{{route('admin.reports.getClients')}}",
          type: "post",
          dataType: 'json',
          delay: 250,
          data: function (params) {
            return {
              _token: CSRF_TOKEN,
              search: params.term // search term
            };
          },
          processResults: function (response) {
            return {
              results: response
            };
          },
          cache: true
        }
                                
        });

      $('.datetimepicker').datetimepicker({
        timepicker:false,
        datepicker:true,        
        format: 'd-m-Y',              
      });

      $(".datetimepicker").attr("autocomplete", "off");

    });
</script>

@endpush