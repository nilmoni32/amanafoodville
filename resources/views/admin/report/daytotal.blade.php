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
    <div class="pull-right">
        <a href="{{ route('admin.reports.pdfdailytotal') }}" class="btn btn-sm btn-dark" target="_blank"><i
                class="fa fa-file-pdf-o" style="font-size:16px;"></i></a>
        <a href="{{ route('admin.reports.exceldailytotal') }}" class="btn btn-sm btn-info"><i class="fa fa-file-excel-o"
                style="font-size:17px;"></i></a>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="tile">
            <div class="tile-body">
                <h4 class="tile-title">{{ __('Daily Sale:') }}
                </h4>
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center"> # </th>
                            <th class="text-center"> Date </th>
                            <th class="text-center"> Total </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daily_totals as $daysale)
                        <tr>
                            <td class="text-center">{{ $loop->index + 1  }}</td>
                            <td class="text-center">{{ $daysale->date }}</td>
                            <td class="text-center">{{ round( $daysale->subtotal,0) }}
                                {{ config('settings.currency_symbol') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="pt-4 text-right">
                    {{ $daily_totals->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection