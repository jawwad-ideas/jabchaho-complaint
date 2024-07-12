@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">Roles</h3>
            <small class="text-white">Manage your Roles here.</small>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                <a href="{{ route('roles.create') }}" class="text-decoration-none">
                    <small id="showFilterBox" type="button"
                        class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-plus-circle"></i><span>New role</span></small>
                </a>

            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-4 rounded">
            <div class="d-flex my-2">
                Showing results {{ ($roles->currentPage() - 1) * config('constants.per_page') + 1 }} to
                {{ min($roles->currentPage() * config('constants.per_page'), $roles->total()) }} of {{ $roles->total() }}
            </div>
    
            <div class="table-scroll-hr">
            <table class="table table-bordered table-striped table-compact">
                <thead>
                    <th scope="col" width="1%">#</th>
                    <th scope="col">Name</th>
                    <th scope="col" width="1%" colspan="3">Action</th>
                </thead>
                @foreach ($roles as $key => $role)
                    <tr>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('roles.show', $role->id) }}">Show</a>
                        </td>
                        <td>
                            <a class="btn btn-primary btn-sm" href="{{ route('roles.edit', $role->id) }}">Edit</a>
                        </td>
                        <td>
                            {!! Form::open([
                                'method' => 'DELETE',
                                'route' => ['roles.destroy', $role->id],
                                'style' => 'display:inline',
                                'onsubmit' => 'return ConfirmDelete()',
                            ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </table>

            <div class="d-flex">
                {!! $roles->links() !!}
            </div>

        </div>
    @endsection
