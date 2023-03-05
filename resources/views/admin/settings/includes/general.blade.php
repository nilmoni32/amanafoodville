<div class="tile">
    <form action="{{ route('admin.settings.update') }}" method="POST" role="form">
        {{-- CSRF: Cross-site request forgery --}}
        @csrf
        <h3 class="tile-title">General Settings</h3>
        <hr>
        <div class="tile-body">
            <div class="form-group">
                <label class="control-label" for="site_name">Site Name</label>
                <input class="form-control" type="text" placeholder="Enter Site name" name="site_name" id="site_name"
                    value="{{ config('settings.site_name') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="site_title">Site Title</label>
                <input class="form-control" type="text" placeholder="Enter Site title" name="site_title" id="site_title"
                    value="{{ config('settings.site_title') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="default_email_address">Default Email Address</label>
                <input class="form-control" type="text" placeholder="Enter email address" name="default_email_address"
                    id="default_email_address" value="{{ config('settings.default_email_address') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="currency_code">Currency Code</label>
                <input class="form-control" type="text" placeholder="Enter currency code" name="currency_code"
                    id="currency_code" value="{{ config('settings.currency_code') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="currency_symbol">Currency Symbol</label>
                <input class="form-control" type="text" placeholder="Enter currency Symbol" name="currency_symbol"
                    id="currency_symbol" value="{{ config('settings.currency_symbol') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="phone_no">Contact No</label>
                <input class="form-control" type="text" placeholder="Enter Contact No" name="phone_no" id="phone_no"
                    value="{{ config('settings.phone_no') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="contact_address">Contact Address</label>
                <input class="form-control" type="text" placeholder="Enter Contact Address" name="contact_address"
                    id="contact_address" value="{{ config('settings.contact_address') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="delivery_charge">Shipping Cost</label>
                <input class="form-control" type="text" placeholder="Enter Shipping Cost" name="delivery_charge"
                    id="delivery_charge" value="{{ config('settings.delivery_charge') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="client_lists">Top Client Lists</label>
                <input class="form-control" type="text" placeholder="Enter Client Lists" name="client_lists"
                    id="delivery_charge" value="{{ config('settings.client_lists') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="open_hours">Restaurant Opening Hours</label>
                <input class="form-control" type="text" placeholder="Enter open_hours" name="open_hours" id="open_hours"
                    value="{{ config('settings.open_hours') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="google_map">Funville Google Map</label>
                <input class="form-control" type="text" placeholder="Google Map iframe" name="google_map"
                    id="google_map" value="{{ config('settings.google_map') }}">
            </div>
            @can('super-admin')
            <div class="form-group">
                <label class="control-label" for="tax_percentage">Tax Excuded Percentage</label>
                <input class="form-control" type="text" placeholder="Tax Percentage" name="tax_percentage"
                    id="tax_percentage" value="{{ config('settings.tax_percentage') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="tax_include_percentage">Tax Include Percentage</label>
                <input class="form-control" type="text" placeholder="Tax Include Percentage" name="tax_include_percentage"
                    id="tax_include_percentage" value="{{ config('settings.tax_include_percentage') }}">
            </div>
	        <div class="form-group">
                <label class="control-label" for="due_booking_amount">Due Sale Advance Payment</label>
                <input class="form-control" type="text" placeholder="Booking Amount" name="due_booking_amount"
                    id="due_booking_amount" value="{{ config('settings.due_booking_amount') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="hide_order_amount">Hide Order Total Amount</label>
                <input class="form-control" type="text" placeholder="Hide Order Amount" name="hide_order_amount"
                    id="hide_order_amount" value="{{ config('settings.hide_order_amount') }}">
            </div>
            @endcan
            <div class="form-group">
                <label class="control-label" for="tax_percentage">Total Number of Tables in Restaurant</label>
                <input class="form-control" type="text" placeholder="Total no of tables" name="total_tbls"
                    id="total_tbls" value="{{ config('settings.total_tbls') }}">
            </div>
            {{-- <div class="form-group">
                <label class="control-label" for="card_bank_list">Card Payment Banks List</label>
                <input class="form-control" type="text" placeholder="Bank List" name="card_bank_list"
                    id="card_bank_list" value="{{ config('settings.card_bank_list') }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="mobile_bank_list">Mobile Banking Payment Bank list</label>
                <input class="form-control" type="text" placeholder="Mobile Bank List" name="mobile_bank_list"
                    id="mobile_bank_list" value="{{ config('settings.mobile_bank_list') }}">
            </div> --}}
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
