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
                <h3 class="tile-title">{{ __('Add Service') }}</h3>
                <form method="POST" action="{{ route('admin.services.store') }}">
                    @csrf
                    <div class="form-group">
                        <label class="control-label" for="title">Service Name</label>
                        <input class="form-control @error('title') is-invalid @enderror" type="text"
                            placeholder="Enter Service Name" id="title" name="title" value="{{ old('title') }}" />
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
                                checked>
                            <label class="form-check-label" for="icon1"><i class="fa fa-cutlery"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon2" value="fa-gift">
                            <label class="form-check-label" for="icon2"><i class="fa fa-gift"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon3" value="fa-yelp">
                            <label class="form-check-label" for="icon3"><i class="fa fa-yelp"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon4"
                                value="fa-birthday-cake">
                            <label class="form-check-label" for="icon4"><i class="fa fa-birthday-cake"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon5" value="fa-slack">
                            <label class="form-check-label" for="icon5"><i class="fa fa-slack"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon6" value="fa-users">
                            <label class="form-check-label" for="icon6"><i class="fa fa-users"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon7" value="fa-university">
                            <label class="form-check-label" for="icon7"><i class="fa fa-university"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon8" value="fa-telegram">
                            <label class="form-check-label" for="icon8"><i class="fa fa-telegram"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon9" value="fa-slideshare">
                            <label class="form-check-label" for="icon9"><i class="fa fa-slideshare"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon10" value="fa-delicious">
                            <label class="form-check-label" for="icon10"><i class="fa fa-delicious"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon11" value="fa-empire">
                            <label class="form-check-label" for="icon11"><i class="fa fa-empire"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon12" value="fa-pagelines">
                            <label class="form-check-label" for="icon12"><i class="fa fa-pagelines"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon13" value="fa-truck">
                            <label class="form-check-label" for="icon13"><i class="fa fa-truck"></i></label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="icon" id="icon14" value="fa-meetup">
                            <label class="form-check-label" for="icon14"><i class="fa fa-meetup"></i></label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="description">Service Description</label>
                        <textarea name="description" id="description" rows="4" class="form-control" required></textarea>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-sm-12 text-right">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-fw fa-lg fa-check-circle"></i>{{ __('Save Service') }}
                            </button>&nbsp;
                            <a class="btn btn-danger" href="{{ route('admin.services.index') }}"><i
                                    class="fa fa-fw fa-lg fa-arrow-left"></i>{{ __('Go Back') }}</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection