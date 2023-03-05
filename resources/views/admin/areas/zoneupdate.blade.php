@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-map-marker"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
@include('admin.partials.flash')

<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <div class="row mb-4">
                    <div class="col-md-3 offset-md-3">
                        <select class="form-control" name="district" id="district">
                            <option selected='false'>Select District</option>
                            @foreach($districts as $district)
                            <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary" name="find" id="find"><i
                                class="fa fa-search align-baseline"></i>
                            Find Areas</button>
                    </div>
                </div>
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Area Name </th>
                            <th class="text-center"> Status </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1 @endphp
                        @foreach($zones as $zone)
                        <tr>
                            <td class="text-center" style="padding: 0.3rem; vertical-align: 0 ;">{{ $i }}
                            </td>
                            <td class="text-center" style="padding: 0.3rem; vertical-align: 0 ;">{{ $zone->name }}
                            </td>
                            <td class="text-center" style="padding: 0.3rem; vertical-align: 0 ;">
                                <input type="checkbox" data-toggle="toggle" data-on="Active" data-off="Inactive"
                                    {{ $zone->status ? 'checked' : ''}} data-onstyle="primary" data-offstyle="secondary"
                                    data-id={{$zone->id}} class="zoneStatus" data-height="110%">
                            </td>
                        </tr>
                        @php $i++ @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- we need to add  @stack('scripts') in the app.blade.php for the following scripts --}}
@push('scripts')
<script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#sampleTable').DataTable();
        // getting zone data
        $('#find').on('click', function(){
            var distId = $("#district").val();           
            $.ajax({
                url: window.location.href="{{ url('/admin/districts/zones') }}" + "/"+ distId
            });
        });
        
       // changing the zone status
        $('body').on('change', '.zoneStatus', function(){
            var id = $(this).attr('data-id');
            if(this.checked){
                var status = 1;
            }else{
                var status = 0;
            }   
              
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }); 

            jQuery.ajax({
                  url: "{{ url('/admin/districts/zones') }}",
                  method: 'post',
                  data: {
                    id: id,
                    status: status                    
                  },
                  success: function(result){                     
                     console.log(result.success);
                  }
            });
         

        }); 



    });
</script>
@endpush