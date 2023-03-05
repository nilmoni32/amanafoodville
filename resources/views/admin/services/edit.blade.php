@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-paw"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
</div>
@include('admin.partials.flash')

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="tile">
            <div class="tile-body">
                <h4 class="tile-title">Edit Services</h4>
                <form method="POST" action="{{ route('admin.services.update') }}">
                    @csrf
                    <input type="hidden" name="id" value="{{ $service->id }}">
                    <div class="form-group">
                        <label class="control-label" for="title">Service Name</label>
                        <input class="form-control @error('title') is-invalid @enderror" type="text"
                            placeholder="Enter Service Name" id="title" name="title" value="{{ $service->title }}" />
                        <div class="invalid-feedback active">
                            <i class="fa fa-exclamation-circle fa-fw"></i> @error('title')
                            <span>{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="icon">Service Icon</label>
                        <br />
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon1" value="fa-cutlery"
                                {{ $service->icon == 'fa-cutlery' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon1"><i class="fa fa-cutlery"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon2" value="fa-gift"
                                {{ $service->icon == 'fa-gift' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon2"><i class="fa fa-gift"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon3" value="fa-yelp"
                                {{ $service->icon == 'fa-yelp' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon3"><i class="fa fa-yelp"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon4" value="fa-birthday-cake"
                                {{ $service->icon == 'fa-birthday-cake' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon4"><i class="fa fa-birthday-cake"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon5" value="fa-slack"
                                {{ $service->icon == 'fa-slack' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon5"><i class="fa fa-slack"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon6" value="fa-users"
                                {{ $service->icon == 'fa-users' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon6"><i class="fa fa-users"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon7" value="fa-university"
                                {{ $service->icon == 'fa-university' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon7"><i class="fa fa-university"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon8" value="fa-telegram"
                                {{ $service->icon == 'fa-telegram' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon8"><i class="fa fa-telegram"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon9" value="fa-slideshare"
                                {{ $service->icon == 'fa-slideshare' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon9"><i class="fa fa-slideshare"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon10" value="fa-delicious"
                                {{ $service->icon == 'fa-delicious' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon10"><i class="fa fa-delicious"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon11" value="fa-empire"
                                {{ $service->icon == 'fa-empire' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon11"><i class="fa fa-empire"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon12" value="fa-pagelines"
                                {{ $service->icon == 'fa-pagelines' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon12"><i class="fa fa-pagelines"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon13" value="fa-truck"
                                {{ $service->icon == 'fa-pied-piper-alt' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon13"><i class="fa fa-truck"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon14" value="fa-meetup"
                                {{ $service->icon == 'fa-beer' ? 'checked' : '' }}>
                            <label class="form-check-label" for="icon14"><i class="fa fa-meetup"></i></label>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="control-label" for="description">Service Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control"
                            required>{{ $service->description }}</textarea>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-fw fa-lg fa-check-circle"></i>
                                {{ __('Update Services') }}
                            </button>
                            <a class="btn btn-danger" href="{{ route('admin.services.index') }}"><i
                                    class="fa fa-fw fa-lg fa-arrow-left"></i>Go Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection