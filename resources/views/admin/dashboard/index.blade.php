@extends('admin.app')
@section('title','Dashboard')
@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-dashboard"></i>&nbsp;Dashboard</h1>
  </div>
</div>
<div class="row"> 
  <div class="col-md-6 col-lg-3">
    <div class="widget-small warning coloured-icon">
      <i class="icon fa fa-users fa-3x"></i>
      <div class="info">
        <h5>Admin Users</h5>
        <p><b>( {{ App\Models\Admin::count()}} )</b></p>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="widget-small bg-success coloured-icon">
      <i class="icon fa fa-users fa-3x"></i>
      <div class="info">
        <h5>Online Customers</h5>
        <p><b>( {{ App\Models\User::count()}} )</b></p>
      </div>
    </div>
  </div>  
  <div class="col-md-6 col-lg-3">
    <div class="widget-small danger coloured-icon">
      <i class="icon fa fa-cutlery fa-3x"></i>
      <div class="info">
        <h5>Foods</h5>
        <p><b>({{ App\Models\Product::count() }})</b></p>
      </div>
    </div>
  </div> 
  <div class="col-md-6 col-lg-3">
    <div class="widget-small bg-danger coloured-icon">
      <i class="icon fa fa-star fa-3x"></i>
      <div class="info">
        <h5>Ingredients</h5>
        <p><b>({{ App\Models\Ingredient::count() }})</b></p>
      </div>
    </div>
  </div>
</div>
<div class="row">
  @can('all-admin-features')
  <div class="col-md-6 col-lg-3">
    <div class="widget-small primary coloured-icon">
      <i class="icon fa fa-delicious fa-3x"></i>      
      <div class="info">
        <h5>Today KOT Sales</h5>
        <p><b>( {{ round(App\Models\Ordersale::whereDate('created_at', '=', Carbon\Carbon::today()->toDateString())->sum('grand_total'), 2)}} {{ config('settings.currency_symbol') }} )</b></p>
      </div>      
    </div>
  </div>  
  <div class="col-md-6 col-lg-3">
    <div class="widget-small info coloured-icon">
      <i class="icon fa fa-shopping-basket fa-3x"></i>
      <div class="info">
        <h5>Today KOT Orders</h5>
        <p><b>( {{ App\Models\Ordersale::whereDate('created_at', '=', Carbon\Carbon::today()->toDateString())->count() }} )</b></p>
      </div>
    </div>
  </div>
  <div class="col-md-6 col-lg-3">
    <div class="widget-small primary coloured-icon">
      <i class="icon fa fa-bar-chart fa-3x"></i>
      <div class="info">
        <h5>Today Online Sales</h5>
        <p><b>( {{ round(App\Models\Order::where('status', 'delivered')->whereDate('created_at', '=', Carbon\Carbon::today()->toDateString())->sum('grand_total'), 2)}} {{ config('settings.currency_symbol') }} )</b></p>
      </div>
    </div>
  </div>    
  <div class="col-md-6 col-lg-3">
    <div class="widget-small bg-info coloured-icon">
      <i class="icon fa fa-shopping-basket fa-3x"></i>
      <div class="info">
        <h5>Online Orders</h5>
        <p><b>( {{ App\Models\Order::count()  }} )</b></p>
      </div>
    </div>
  </div>
  @endcan 
  
</div>
<!-- User Activity Log -->
<div class="row mt-5">
  <div class="col-md-12">
      <div class="tile">
          <div class="tile-body">
            <h4 class="tile-title text-center mb-4">{{ __(': User Activity Log :') }}</h4>
              <table class="table table-hover table-bordered" id="sampleTable">
                  <thead>
                      <tr>
                          <th> # </th>
                          <th class="text-center"> Date </th>
                          <th class="text-center"> Log Type </th>
                          <th class="text-center"> Done By </th>
                          <th class="text-center"> Description </th>                          
                          {{-- <th style="min-width:70px;" class="text-center text-danger"><i class="fa fa-bolt"> </i></th> --}}
                      </tr>
                  </thead>
                  <tbody>
                    @php $userlogs = App\Models\Userlog::latest()->take(150)->get(); @endphp
                      @foreach($userlogs as $userlog)
                      <tr>
                          <td class="text-center">{{ $loop->index + 1 }}</td>
                          <td class="text-center">{{ $userlog->log_date }}</td>
                          <td class="text-center">{{ $userlog->log_type }}</td>
                          <td class="text-center">{{ $userlog->done_by }}</td>
                          <td class="text-left">{{ $userlog->description }}</td> 
                          {{-- <td class="text-center">
                            <div class="btn-group" role="group" aria-label="Second group">
                                <a href="#"
                                    class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                            </div>
                        </td>                          --}}
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
    $('#sampleTable').DataTable();
</script>
@endpush