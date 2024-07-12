@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">Provincial Assemblies</h3>
            <small class="text-white">Manage your Provincial Assemblies here.</small>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                <a href="{{ route('pa.form') }}" class="text-decoration-none">
                    <small type="button"
                        class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-file-circle-plus"></i><span>Add Provincial Assembly</span></small>
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
                <form class="form-inline" method="GET" action="{{ route('pa.index') }}">
                    <div class="row mb-3">
                        <div class="col-xxl-12 col-xl-3 col-lg-12 col-md-12">
                            <input type="text" class="form-control p-2" autocomplete="off" name="name"
                            value="{{ old('name', $name) }}" placeholder="Name">
                        </div>
                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                class="btn bg-theme-green text-white p-2 d-inline-flex align-items-center gap-1"
                                id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('pa.index') }}"
                                class="btn bg-theme-dark text-white p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-scroll-hr">
                <div class="d-flex my-2">
                    Showing results {{ ($pAs->currentPage() - 1) * config('constants.per_page') + 1 }} to
                    {{ min($pAs->currentPage() * config('constants.per_page'), $pAs->total()) }} of {{ $pAs->total() }}
                </div>
                <table class="table table-bordered table-striped table-compact">
                    <thead>
                        <th width="1%">#</th>
                        <th>Name</th>
                        <th width="20%" colspan="2">Action</th>
                    </thead>
                    @foreach ($pAs as $key => $pA)
                        <tr>
                            <td>{{ Arr::get($pA, 'id') }}</td>
                            <td>{{ Arr::get($pA, 'name') }}</td>
                            @if (Auth::user()->can('pa.edit'))
                                <td>
                                    <a class="btn btn-success btn-sm" href="{{ route('pa.edit', $pA->id) }}">Edit</a>
                                </td>
                            @endif
                            @if (Auth::user()->can('pa.destroy'))
                                <td>
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['pa.destroy', $pA->id],
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
                {!! $pAs->appends(Request::except('page'))->render() !!}
            </div>

        </div>
        <script>
            $("#showFilterBox").click(function() {
                $("#filterBox").toggle();
            });
        </script>
    @endsection
