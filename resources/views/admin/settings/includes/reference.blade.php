<div class="tile">
    <form action="{{ route('admin.settings.update') }}" method="POST" role = "form" >
        {{-- CSRF: Cross-site request forgery --}}
        @csrf
        <h3 class="tile-title">{{ __('Reference Discount Mail Lists') }}</h3>
        <hr>
        <div class="tile-body">        
            <div class="form-group">
                <label class="control-label" for="ref_email_recipient">Email Recipients</label>
                <input class="form-control" type="text"
                    placeholder="Enter email Recipients such as xyz@funville.com, abc@funville.com"
                    name="ref_email_recipient" id="ref_email_recipient" value="{{ config('settings.ref_email_recipient') }}">
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