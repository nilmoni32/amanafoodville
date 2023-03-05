<div class="tile">
    <form action="{{ route('admin.settings.update') }}" method="POST" role="form">
        {{-- CSRF: Cross-site request forgery --}}
        @csrf
        <h3 class="tile-title">Social links</h3>
        <hr>
        <div class="tile-body">
            <div class="form-group">
                <label class="control-label" for="social_facbook">Facebook Profile</label>
                <input class="form-control" type="text" placeholder="Enter facebook profile" name="social_facbook"
                    id="social_facbook" value="{{ config('settings.social_facbook') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="social_twitter">Twitter Profile</label>
                <input class="form-control" type="text" placeholder="Enter twitter profile" name="social_twitter"
                    id="social_twitter" value="{{ config('settings.social_twitter') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="social_instagram">Instagram Profile</label>
                <input class="form-control" type="text" placeholder="Enter instagram profile" name="social_instagram"
                    id="social_instagram" value="{{ config('settings.social_instagram') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="social_linkedin">Linkedin Profile</label>
                <input class="form-control" type="text" placeholder="Enter linkedin profile" name="social_linkedin"
                    id="social_linkedin" value="{{ config('settings.social_linkedin') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="social_youtube">Youtube Profile</label>
                <input class="form-control" type="text" placeholder="Enter youtube profile" name="social_youtube"
                    id="social_youtube" value="{{ config('settings.social_youtube') }}">
            </div>
        </div>
        <div class="tile-footer">
            <div class="row d-print-none mt-2">
                <div class="col-12 text-right">
                    <button class="btn btn-success" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Update
                        Settings</button>
                </div>
            </div>
        </div>
    </form>
</div>