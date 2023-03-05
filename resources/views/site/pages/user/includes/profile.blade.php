<div class="user-personal-info">
    <h5>Personal Information</h5>
    <div class="user-info-body">
        <form action="{{ route('user.updateProfile') }}" method="post" role="form">
            @csrf
            <div class="form-row">
                <div class="form-group col-12">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name"
                        value="{{ $user->name }}" placeholder="Your Name" required autocomplete="name">
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        value="{{ $user->email }}" placeholder="E-Mail Address" id="email" required autocomplete="email" {{ $user->is_token_verified ? 'disabled':
                    '' }} />
                    @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                        name="phone_number" value="{{ $user->phone_number }}" placeholder="phone_number No"
                        id="phone_number" required autocomplete="phone_number" {{ $user->is_token_verified ? 'disabled':
                        '' }} />
                    @error('phone_number')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <textarea placeholder="Your Contact Address" id="current-address" class="form-control" rows="4"
                        name="address">{{ $user->address }}</textarea>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group mb-0 pt-4 col-12 text-center">
                    <button class="btn btn-theme btn-md" type="submit">SAVE
                        CHANGES</button>
                </div>
            </div>
        </form>
    </div>
</div>