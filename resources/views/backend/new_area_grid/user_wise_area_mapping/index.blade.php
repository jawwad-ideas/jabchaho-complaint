@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">User Wise Area Mapping</h3>
            <small class="text-white">Manage your Area Mappings here.</small>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                @if (Auth::user()->can('area.mapping.form'))
                <a href="{{ route('area.mapping.form') }}" class="text-decoration-none">
                    <small type="button"
                        class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-file-circle-plus"></i><span>Add Area Mapping</span></small>
                </a>
                @endif
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
                <form class="form-inline" method="GET" action="{{ route('area.mapping.index') }}">
                    <div class="row mb-3">
                        <div class="col-xxl-6 col-xl-3 col-lg-12 col-md-12">
                            <label class="fw-bold" for="user_id">User:</label>
                            <select class="mySelect form-control form-control-sm" id="user_id" name="user_id">
                                <option value=''>--Select--</option>
                                @if (!empty($users))
                                    @foreach ($users as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('user_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-xxl-6 col-xl-3 col-lg-12 col-md-12">
                            <label class="fw-bold" for="new_area_id">New Area:</label>
                            <select class="mySelect form-control form-control-sm" id="new_area_id" name="new_area_id">
                                <option value=''>--Select--</option>
                                @if (!empty($newAreas))
                                    @foreach ($newAreas as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('new_area_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-xxl-6 col-xl-3 col-lg-12 col-md-12">
                            <label class="fw-bold" for="national_assembly_id">National Assembly:</label>
                            <select class="mySelect form-control form-control-sm" id="national_assembly_id" name="national_assembly_id">
                                <option value=''>--Select--</option>
                                @if (!empty($nAs))
                                    @foreach ($nAs as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('national_assembly_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-xxl-6 col-xl-3 col-lg-12 col-md-12">
                            <label class="fw-bold" for="provincial_assembly_id">Provincial Assembly:</label>
                            <select class="mySelect form-control form-control-sm" id="provincial_assembly_id" name="provincial_assembly_id">
                                <option value=''>--Select--</option>
                                @if (!empty($pAs))
                                    @foreach ($pAs as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('provincial_assembly_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
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
                            <a href="{{ route('area.mapping.index') }}"
                                class="btn bg-theme-dark text-white p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-scroll-hr">
                <div class="d-flex my-2">
                    Showing results {{ ($areas->currentPage() - 1) * config('constants.per_page') + 1 }} to
                    {{ min($areas->currentPage() * config('constants.per_page'), $areas->total()) }} of
                    {{ $areas->total() }}
                </div>
                <table class="table table-bordered table-striped table-compact">
                    <thead>
                        <th scope="col" width="1%">#</th>
                        <th scope="col">User</th>
                        <th scope="col">Areas</th>
                        <th scope="col">NA</th>
                        <th scope="col">PS</th>
                        <th scope="col" width="20%" colspan="2">Action</th>
                    </thead>
                    @foreach ($areas as $key => $area)
                        <tr>
                            <td>{{ Arr::get($area, 'id') }}</td>
                            <td>{{ Arr::get($area['user'], 'name') }}</td>
                            <td>{!! $UserWiseAreaMapping->getAreas(Arr::get($area, 'user_id')) !!}</td>
                            <td>{{ Arr::get($area['nationalAssembly'], 'name') }}</td>
                            <td>{{ Arr::get($area['provincialAssembly'], 'name') }}</td>
                            @if (Auth::user()->can('area.mapping.edit'))
                                <td>
                                    <a class="btn btn-success btn-sm"
                                        href="{{ route('area.mapping.edit', $area->user_id) }}">Edit</a>
                                </td>
                            @endif
                            @if (Auth::user()->can('area.mapping.destroy'))
                                <td>
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['area.mapping.destroy', $area->user_id],
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
                {!! $areas->appends(Request::except('page'))->render() !!}
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
