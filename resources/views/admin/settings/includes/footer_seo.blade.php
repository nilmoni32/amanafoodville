<div class="tile">
    <form action="{{ route('admin.settings.update') }}" method="POST" role = "form" >
        {{-- CSRF: Cross-site request forgery --}}
        @csrf
        <h3 class="tile-title">Footer & SEO</h3>
        <hr>
        <div class="tile-body">        
            <div class="form-group">
                <label class="control-label" for = "footer_copyright_text">Footer Copyright Text</label>
                <textarea class="form-control" rows="4" placeholder="Enter Footer Copyright Text"  name = "footer_copyright_text" id = "footer_copyright_text">{{ config('settings.footer_copyright_text') }}</textarea>
            </div>
            <div class="form-group">
                <label class="control-label" for = "seo_meta_title">SEO Meta Keywords</label>
                <input class="form-control" type="text" placeholder="Enter keywords with comma separated"  name = "seo_meta_title" id = "seo_meta_title" value = "{{ config('settings.seo_meta_title') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for = "seo_meta_description">SEO Meta Description</label>
                <textarea class="form-control" rows="4" placeholder="SEO Meta Description"  name = "seo_meta_description" id = "seo_meta_description">{{ config('settings.seo_meta_description') }}</textarea>                
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
