@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">Wards</h3>
            <small class="text-white">Manage your Wards here.</small>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                <a href="{{ route('ward.form') }}" class="text-decoration-none">
                    <small id="showFilterBox" type="button"
                        class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fas fa-plus-circle"></i><span>Add Ward</span></small>
                </a>
            </div>
        </div>
    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-4 rounded">
            <div class="table-scroll-hr">
                <div class="d-flex my-2">
                    Showing results {{ ($wards->currentPage() - 1) * config('constants.per_page') + 1 }} to
                    {{ min($wards->currentPage() * config('constants.per_page'), $wards->total()) }} of
                    {{ $wards->total() }}
                </div>
                <table class="table table-bordered table-striped table-compact">
                    <thead>
                        <th scope="col" width="1%">#</th>
                        <th scope="col">Name</th>
                        <th scope="col" width="20%" colspan="2">Action</th>
                    </thead>
                    @foreach ($wards as $key => $ward)
                        <tr>
                            <td>{{ Arr::get($ward, 'id') }}</td>
                            <td>{{ Arr::get($ward, 'name') }}</td>
                            @if (Auth::user()->can('ward.edit'))
                                <td>
                                    <a class="btn btn-success btn-sm" href="{{ route('ward.edit', $ward->id) }}">Edit</a>
                                </td>
                            @endif
                            @if (Auth::user()->can('ward.destroy'))
                                <td>
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['ward.destroy', $ward->id],
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
                {!! $wards->appends(Request::except('page'))->render() !!}
            </div>

        </div>
    @endsection
