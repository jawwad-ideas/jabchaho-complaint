@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Roles</h3>
            <small class="text-dark">Manage your Roles here.</small>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                <a href="{{ route('roles.create') }}" class="text-decoration-none">
                    <small id="showFilterBox" type="button"
                        class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-plus-circle"></i><span>New role</span></small>
                </a>

            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-2 rounded">
            <div class="d-flex my-2">
                Showing results {{ ($roles->currentPage() - 1) * config('constants.per_page') + 1 }} to
                {{ min($roles->currentPage() * config('constants.per_page'), $roles->total()) }} of {{ $roles->total() }}
            </div>
    
            <div class="table-scroll-hr">
            <table class="table table-bordered table-striped table-compact ">
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
                            <a class="btn bg-theme-yellow btn-sm" href="{{ route('roles.show', $role->id) }}"><i class="fa fa-eye"></i></a>
                        </td>
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('roles.edit', $role->id) }}"><i class="fa fa-pencil"></i></a>
                        </td>
                        <td>
                            {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['roles.destroy', $role->id],
                                    'style' => 'display:inline',
                                    'onsubmit' => 'return ConfirmDelete()',
                                ]) !!}
                                    {!! Form::button('<i class="fa fa-trash"></i>', [
                                        'type' => 'submit',
                                        'class' => 'btn btn-danger btn-sm',
                                        'title' => 'Delete'
                                    ]) !!}
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
