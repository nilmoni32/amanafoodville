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
                        <form action="{{ route('admin.reports.getTop20') }}" method="post"
                            class="form-inline justify-content-center">
                            @csrf
                            <div class="form-group mb-2">
                                <label>
                                    <span class="font-weight-bold pr-1">From Date :</span>
                                    <input type="text" class="form-control datetimepicker" name="start_date"
                                        placeholder="choose date (d-m-Y)" required>
                                </label>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label class="font-bold">
                                    <span class="font-weight-bold pr-1">To Date :</span>
                                    <input type="text" class="form-control datetimepicker" name="end_date"
                                        placeholder="choose date (d-m-Y)" required>
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary mb-2" name="top20btn">
                                Preview</button>
                        </form>
                    </div>

                </div>
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Food Name </th>
                            <th class="text-center"> Unit Price </th>
                            <th class="text-center"> Total Qty </th>
                            <th class="text-center"> Subtotal </th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @foreach($zones as $area)
                        <tr>
                            <td class="text-center" style="padding: 0.3rem; vertical-align: 0 ;">{{ $area->id }}
                        </td>
                        <td class="text-center" style="padding: 0.3rem; vertical-align: 0 ;">{{ $area->name }}
                        </td>
                        <td class="text-center" style="padding: 0.3rem; vertical-align: 0 ;">
                            <input type="checkbox" data-toggle="toggle" data-on="Active" data-off="Inactive"
                                {{ $area->status ? 'checked' : ''}} data-onstyle="primary" data-offstyle="secondary"
                                data-id={{$area->id}} class="districtStatus">
                        </td>
                        </tr>
                        @endforeach --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')

<script>
    $(document).ready(function () {
      $('.datetimepicker').datetimepicker({
        timepicker:false,
        datepicker:true,        
        format: 'd-m-Y',              
      });
      $(".datetimepicker").attr("autocomplete", "off");
    });
</script>

@endpush