<div class="user-change-password">
    <h5>Change Password</h5>
    <div class="change-password-body">
        <form action="{{ route('user.changePassword') }}" method="post" role="form">
            @csrf
            <div class="form-group">
                <input type="password" class="form-control @error('old-password') is-invalid @enderror"
                    name="old_password" placeholder="Old Password" id="old-password" required>
                @error('old-password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                    value="{{ old('password') }}" placeholder="New Password" id="password" required
                    autocomplete="new-password" />
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group">
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    name="password_confirmation" placeholder="Confirm Password" id="password_confirmation" required
                    autocomplete="new-password" />
                @error('password_confirmation')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <div class="form-group mb-0 pt-4 text-center">
                <button class="btn btn-theme btn-md" type="submit">SAVE CHANGES</button>
            </div>
        </form>
    </div>
</div>