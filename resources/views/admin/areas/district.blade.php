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
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> District Name </th>
                            <th class="text-center"> Status </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($districts as $district)
                        <tr>
                            <td class="text-center" style="padding: 0.3rem; vertical-align: 0 ;">{{ $district->id }}
                            </td>
                            <td class="text-center" style="padding: 0.3rem; vertical-align: 0 ;">{{ $district->name }}
                            </td>
                            <td class="text-center" style="padding: 0.3rem; vertical-align: 0 ;">
                                <input type="checkbox" data-toggle="toggle" data-on="Active" data-off="Inactive"
                                    {{ $district->status ? 'checked' : ''}} data-onstyle="primary"
                                    data-offstyle="secondary" data-id={{$district->id}} class="districtStatus" data-height="110%">
                            </td>
                        </tr>
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

        $('body').on('change', '.districtStatus', function(){
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
                  url: "{{ url('/admin/districts') }}",
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
        $('#sampleTable').DataTable();
    });
    
</script>
@endpush