@extends('backend.layouts.app-master')

@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">New Areas</h3>
        <small class="text-white">Manage your New Areas here.</small>
    </div>
    <div class="text-end">
        <div class="btn-group" role="group">
            <a href="{{ route('new.area.form') }}" class="text-decoration-none">
                <small type="button"
                    class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                        class="fa fa-solid fa-file-circle-plus"></i><span>Add New Areas</span></small>
            </a>
            <small id="showFilterBox" type="button"
                class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                    class="fa fa-solid fa-filter"></i> <span>Filter</span>
            </small>
        </div>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">
    <div class="bg-light p-4 rounded">
        <div class="" id="filterBox" style="display:none;">
            <form class="form-inline" method="GET" action="{{ route('new.area.index') }}">
                <div class="row mb-3">
                    <div class="col-xxl-6 col-xl-3 col-lg-12 col-md-12">
                        <input type="text" class="form-control p-2" autocomplete="off" name="name"
                        value="{{ old('name', $name) }}" placeholder="Name">
                    </div>
                    <div class="col-xxl-6 col-xl-3 col-lg-12 col-md-12">
                        <select class="mySelect form-control form-control-sm" id="city_id" name="city_id">
                                    <option value=''>--Select--</option>
                                    @if (!empty($cities))
                                        @foreach ($cities as $row)
                                            <option value="{{ trim(Arr::get($row, 'id')) }}"
                                                {{ request('city_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                                {{ trim(Arr::get($row, 'name')) }}
                                            </option>
                                        @endforeach
                                    @endif
                        </select>
                    </div>
                    <div class="col-lg-12 text-end mt-4">
                        <button type="submit"
                            class="btn bg-theme-green text-white p-2 d-inline-flex align-items-center gap-1"
                            id="consult">
                            <span>Search</span>
                            <i alt="Search" class="fa fa-search"></i>
                        </button>
                        <a href="{{ route('new.area.index') }}"
                            class="btn bg-theme-dark text-white p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                            <span>Clear</span>
                            <i class="fa fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
            </form>
        </div>
        <div class="table-scroll-hr">
            <div class="d-flex my-2">
                Showing results {{ ($newAreas->currentPage() - 1) * config('constants.per_page') + 1 }} to
                {{ min($newAreas->currentPage() * config('constants.per_page'), $newAreas->total()) }} of
                {{ $newAreas->total() }}
            </div>
            <table class="table table-bordered table-striped table-compact">
                <thead>
                    <th scope="col" width="1%">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">City</th>
                    <th scope="col" width="20%" colspan="2">Action</th>
                </thead>
                @foreach ($newAreas as $key => $newArea)
                    <tr>
                        <td>{{ Arr::get($newArea, 'id') }}</td>
                        <td>{{ Arr::get($newArea, 'name') }}</td>
                        <td>{{ $newArea->city->name ?? 'N/A' }}</td>

                        @if (Auth::user()->can('new.area.edit'))
                            <td>
                                <a class="btn btn-success btn-sm"
                                    href="{{ route('new.area.edit', $newArea->id) }}">Edit</a>
                            </td>
                        @endif
                        @if (Auth::user()->can('new.area.destroy'))
                            <td>
                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['new.area.destroy', $newArea->id],
                                    'style' => 'display:inline',
                                    'onsubmit' => 'return ConfirmDelete()',
                                ]) !!}
                                {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                            </td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>

        <div class="d-flex">
            {!! $newAreas->appends(Request::except('page'))->render() !!}
        </div>

    </div>
</div>
<script>
    $(document).ready(function() {
    $("#showFilterBox").click(function() {
        $("#filterBox").toggle();
    });
    $('.mySelect').select2();
});
</script>

<!--Select 2 -->
<link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
<script src="{!! url('assets/js/select2.min.js') !!}"></script>

@endsection
