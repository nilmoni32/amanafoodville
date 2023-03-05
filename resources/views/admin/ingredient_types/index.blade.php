@extends('admin.app')

@section('title')
{{-- Getting $pageTitle from BaseController setPageTitle()--}}
{{ $pageTitle }}
@endsection

@section('content')
<div class="app-title">
    <div>
        <h1><i class="fa fa-ils"></i>&nbsp;{{ $pageTitle }}</h1>
        <p>{{ $subTitle }}</p>
    </div>
    <a href="{{ route('admin.ingredienttypes.create') }}" class="btn btn-primary pull-right">Add Ingredient Types</a>
</div>
@include('admin.partials.flash')
<div class="row">
    <div class="col-md-12">
        <div class="tile">
            <div class="tile-body">
                <table class="table table-hover table-bordered" id="sampleTable">
                    <thead>
                        <tr>
                            <th> # </th>
                            <th> Name </th>
                            <th> Slug </th>
                            <th> Parent </th>
                            <th style="width:100px; min-width:100px;" class="text-center text-danger"><i
                                    class="fa fa-bolt"> </i></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ingredienttypes as $ingredienttype)
                        @if ($ingredienttype->id != 1)
                        <tr>
                            <td>{{ $ingredienttype->id }}</td>
                            <td>{{ $ingredienttype->name }}</td>
                            <td>{{ $ingredienttype->slug }}</td>
                            <td>{{ $ingredienttype->parent->name }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Second group">
                                    <a href="{{ route('admin.ingredienttypes.edit', $ingredienttype->id) }}"
                                        class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                                    <a href="{{ route('admin.ingredienttypes.delete', $ingredienttype->id) }}"
                                        class="btn btn-sm btn-danger {{
                                            App\Models\Ingredient::where('typeingredient_id', $ingredienttype->id)->count()
                                            ? 'disabled' :'' }}"><i class="fa fa-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
{{-- we need to add  @stack('scripts') in the app.blade.php for the following scripts --}}
@push('scripts')
<script type="text/javascript" src="{{ asset('backend/js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('backend/js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
    $('#sampleTable').DataTable();
</script>
@endpush