<div class="tile">
    <form action="{{ route('admin.settings.update') }}" method="POST" role = "form" >
        {{-- CSRF: Cross-site request forgery --}}
        @csrf
        <h3 class="tile-title">Analytics</h3>
        <hr>
        <div class="tile-body">        
            <div class="form-group">
                <label class="control-label" for = "google_analytics">Enter Google Analytics Code</label>
                {{-- Displaying HTML with Blade --}}
                <textarea class="form-control" row="4" placeholder="Enter Google Analytics code"  name = "google_analytics" id = "google_analytics">{!! Config::get('settings.google_analytics') !!}</textarea>                 
            </div>
            <div class="form-group">
                <label class="control-label" for = "facebook_pixels">Enter facebook pixel code</label>
                <textarea class="form-control" row="4" placeholder="Enter facebook pixel code"  name = "facebook_pixels" id = "facebook_pixels">{{ config('settings.facebook_pixels') }}</textarea>                 
            </div>
        </div>
        <div class="tile-footer">
            <div class="row d-print-none mt-2">
                <div class="col-12 text-right">
                    <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update Settings</button>
                </div>
            </div>
        </div>
    </form>
</div>