<div class="tile">
    <form action="{{ route('admin.settings.update') }}" method="POST" role="form">
        {{-- CSRF: Cross-site request forgery --}}
        @csrf
        <h3 class="tile-title">Ingredients Update Scheduler Settings</h3>
        <hr>
        <div class="tile-body">
            <div class="form-group">
                <label class="control-label" for="email_recipient">Email Recipients</label>
                <input class="form-control" type="text"
                    placeholder="Enter email Recipients such as xyz@funville.com, abc@funville.com"
                    name="email_recipient" id="email_recipient" value="{{ config('settings.email_recipient') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="scheduler_timings">Daily Scheduler Timings</label>
                <input class="form-control" type="text" placeholder="Enter Scheduler Time" name="scheduler_timings"
                    id="scheduler_timings" value="{{ config('settings.scheduler_timings') }}">
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