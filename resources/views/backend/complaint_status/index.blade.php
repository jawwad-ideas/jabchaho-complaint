@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">Complaint Status</h3>
            <small class="text-white">Manage your Complaint Status here.</small>

        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                @if (Auth::user()->can('complaints.status.form'))
                    <a href="{{ route('complaints.status.form') }}" class="text-decoration-none">
                        <small type="button"
                            class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                                class="fa fa-solid fa-file-circle-plus"></i><span>New Complaint Status</span></small>
                    </a>
                @endif
            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-4 rounded">
            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact">
                    <thead>
                        <th width="1%">#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th width="20%" colspan="2">Action</th>
                    </thead>
                    @foreach ($complaintStatus as $key => $status)
                        <tr>
                            <td>{{ Arr::get($status, 'id') }}</td>
                            <td>{{ Arr::get($status, 'name') }}</td>
                            <td>{{ Arr::get($status, 'is_enabled') == 1 ? 'Yes' : 'No' }}</td>
                            @if (Auth::user()->can('complaints.show'))
                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('complaints.status.edit', $status->id) }}">Edit</a>
                                </td>
                            @endif
                            @if (Auth::user()->can('complaints.status.destroy'))
                                <td>
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['complaints.status.destroy', $status->id],
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

                <div class="d-flex">
                    {!! $complaintStatus->appends(Request::except('page'))->render() !!}
                </div>
            </div>
        @endsection
