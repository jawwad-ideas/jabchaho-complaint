@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">Permissions</h3>
            <small class="text-white">Manage your Permissions here.</small>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                <a href="{{ route('permissions.create') }}" class="text-decoration-none">
                    <small id="showFilterBox" type="button"
                        class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-plus-circle"></i><span>New Permission</span></small>
                </a>

            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-4 rounded">
            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact">
                    <thead>
                        <tr>
                            <th scope="col" width="40%">Description</th>
                            <th scope="col" width="15%">Name</th>
                            <th scope="col">Guard</th>
                            <th scope="col" colspan="3" width="1%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($permissions as $permission)
                            @php
                                $routeDescription = config('constants.admin_action_with_description');
                                $description = $permission->name;
                            @endphp

                            @if (!empty($routeDescription[$permission->name]))
                                @php $description = $routeDescription[$permission->name]; @endphp
                            @endif

                            <tr>
                                <td>{{ $description }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>{{ $permission->guard_name }}</td>
                                <td><a href="{{ route('permissions.edit', $permission->id) }}"
                                        class="btn btn-info btn-sm">Edit</a></td>
                                <td>
                                    {!! Form::open([
                                        'method' => 'DELETE',
                                        'route' => ['permissions.destroy', $permission->id],
                                        'style' => 'display:inline',
                                        'onsubmit' => 'return ConfirmDelete()',
                                    ]) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        @endsection
