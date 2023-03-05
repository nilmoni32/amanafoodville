<div class="tile">    
    <form action="{{ route('admin.settings.update') }}" method="POST" role = "form" enctype="multipart/form-data">
        {{-- enctype in the form declaration which very important for uploading files. Without this the form will not upload any file. --}}       
        @csrf  {{-- CSRF: Cross-site request forgery --}}
        <h3 class="tile-title">Site Logo</h3>
        <hr>
        <div class="tile-body">            
            <div class="row">
                <div class="col-3">
                    {{-- displaying the uploaded image with an id of 'logoImg' via event handler --}}
                    @if (config('settings.site_logo') != null)
                        <img src="{{ asset('storage/'.config('settings.site_logo')) }}" id="logoImg" style="width: 80px; height: auto;">
                    @else
                        <img src="https://via.placeholder.com/80x80?text=Placeholder+Image" id="logoImg" style="width: 80px; height: auto;">
                    @endif
                </div>
                <div class="col-9">
                    <div class="form-group">
                        <label class="control-label">Site Logo</label>
                        <input class="form-control" type="file" name="site_logo" onchange="loadFile(event,'logoImg')"/>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                {{-- displaying the uploaded image with an id of 'faviconImg' via event handler --}}
                <div class="col-3">
                    @if (config('settings.site_favicon') != null)
                        <img src="{{ asset('storage/'.config('settings.site_favicon')) }}" id="faviconImg" style="width: 80px; height: auto;">
                    @else
                        <img src="https://via.placeholder.com/80x80?text=Placeholder+Image" id="faviconImg" style="width: 80px; height: auto;">
                    @endif
                </div>
                <div class="col-9">
                    <div class="form-group">
                        <label class="control-label">Site Favicon</label>
                        <input class="form-control" type="file" name="site_favicon" onchange="loadFile(event,'faviconImg')"/>
                    </div>
                </div>
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
@push('scripts')
    <script>      
        // Changing the placeholder image of each input when a new file will be uploaded.
        // Here, our event handler will hold a value (output.src) which will be retrieved through the image file being uploaded 
        // via the input file button as an URL which we require to display the image in our webpage.
            loadFile = function(event, id) {
            var output = document.getElementById(id);
            output.src = URL.createObjectURL(event.target.files[0]);
        };
    </script>
@endpush