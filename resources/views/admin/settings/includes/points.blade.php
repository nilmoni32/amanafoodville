<div class="tile">
    <form action="{{ route('admin.settings.update') }}" method="POST" role="form">
        {{-- CSRF: Cross-site request forgery --}}
        @csrf
        <h3 class="tile-title">POS Clients Point Calculation formula</h3>
        <hr>
        <div class="tile-body">
            <div class="form-group">
                <label class="control-label" for="store_id">The Amount to Receive 1 Point</label>
                <input class="form-control" type="text" placeholder="Enter the amount (Tk) for clients to get 1 point"
                    name="money_to_point" id="money_to_point" value="{{ config('settings.money_to_point') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="store_passwd">Point to Money Conversion Rate ( 1 point = ? Tk
                    )</label>
                <input class="form-control" type="text" placeholder="Enter the amount as a conversion rate of points "
                    name="point_to_money" id="point_to_money" value="{{ config('settings.point_to_money') }}">
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