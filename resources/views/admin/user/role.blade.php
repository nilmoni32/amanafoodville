@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-users"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
@include('admin.partials.flash')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="tile">
            <div class="tile-body">
                <h4 class="tile-title">Edit User: {{ $admin->name }}</h4>
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.update') }}">
                            @csrf
                            <input type="hidden" name="id" value="{{ $admin->id }}">
                            <div class="form-group row">
                                <label for="name"
                                    class="col-md-4 col-form-label text-md-right">{{ __('User Name') }}</label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ $admin->name }}" required>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ $admin->email }}" required>

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="roles"
                                    class="col-md-4 col-form-label text-md-right">{{ __('Roles') }}</label>
                                <div class="col-md-6">
                                    @foreach(App\Models\Role::all() as $role)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="roles[]"
                                            value="{{ $role->id }}"
                                            {{  $admin->roles->pluck('id')->contains($role->id)  ? 'checked' : '' }}>

                                        <label class="form-check-label">
                                            {{ $role->name }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary"><i
                                            class="fa fa-fw fa-lg fa-check-circle"></i>
                                        {{ __('Update User') }}
                                    </button>
                                    <a class="btn btn-danger" href="{{ route('admin.users.index') }}"><i
                                            class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection