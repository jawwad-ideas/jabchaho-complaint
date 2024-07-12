@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">Update role</h3>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">

        <div class="alert alert-danger" id="error" style="display:none"></div>
        <div class="alert alert-success" id="success" style="display:none"></div>
        <form method="POST" action="{{ route('roles.update', $role->id) }}">
            @method('patch')
            @csrf
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-section mb-5">
                        <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                            <h4 class="fw-bold mt-0">Edit Role and Manage Permission.</h4>
                        </div>
                    </div>
                    <div class="container mt-4">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input value="{{ $role->name }}" type="text" class="form-control" name="name"
                                placeholder="Name">
                        </div>

                        <label for="permissions" class="form-label">Assign Permissions</label>

                        <table class="table table-striped">
                            <thead>
                                <th scope="col" width="1%"><input type="checkbox" name="all_permission"></th>
                                <th scope="col" width="40%">Description</th>
                                <th scope="col" width="20%">Name</th>
                                <th scope="col" width="1%">Guard</th>
                            </thead>

                            @foreach ($permissions as $permission)
                                @if (in_array($permission->name, config('constants.admin_user_default_action')))
                                    @php continue; @endphp
                                @endif

                                @php
                                    $routeDescription = config('constants.admin_action_with_description');
                                    $description = $permission->name;
                                @endphp

                                @if (!empty($routeDescription[$permission->name]))
                                    @php $description = $routeDescription[$permission->name]; @endphp
                                @endif

                                <tr>
                                    <td>
                                        <input type="checkbox" name="permission[{{ $permission->name }}]"
                                            value="{{ $permission->name }}" class='permission'
                                            {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                    </td>
                                    <td>{{ $description }}</td>
                                    <td>{{ $permission->name }}</td>
                                    <td>{{ $permission->guard_name }}</td>
                                </tr>
                            @endforeach
                        </table>

                        <button type="submit"
                            class="btn bg-theme-green text-white d-inline-flex align-items-center gap-3">Save
                            changes</button>
                        <a href="{{ route('roles.index') }}" class="btn btn-default">Back</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('[name="all_permission"]').on('click', function() {

                if ($(this).is(':checked')) {
                    $.each($('.permission'), function() {
                        $(this).prop('checked', true);
                    });
                } else {
                    $.each($('.permission'), function() {
                        $(this).prop('checked', false);
                    });
                }

            });
        });
    </script>
@endsection
