@extends('backend.layouts.app-master')

@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Update user</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>
    <form method="post" action="{{ route('users.update', $user->id) }}" autocomplete="off">
        @method('patch')
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <div class="form-section mb-5">
                    <div class="form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Edit User and Manage Role.</h4>
                    </div>
                </div>
                <div class="container mt-4">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input
                            value="@if (old('name')) {{ old('name') }}@elseif(empty(old('name')) && old('_token')) {{ '' }}@else{{ Arr::get($user, 'name') }} @endif"
                            type="text" class="form-control" name="name" placeholder="Name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input
                            value="@if (old('email')) {{ old('email') }}@elseif(empty(old('email')) && old('_token')) {{ '' }}@else{{ Arr::get($user, 'email') }} @endif"
                            type="email" class="form-control" name="email" placeholder="Email address">

                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input
                            value="@if (old('username')) {{ old('username') }}@elseif(empty(old('username')) && old('_token')) {{ '' }}@else{{ Arr::get($user, 'username') }} @endif"
                            type="text" class="form-control" name="username" placeholder="Username">

                    </div>
                    <div class="mb-3">
                        <label for="PassWord" class="form-label">Password</label>
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <input type="password" autocomplete="off" class="form-control" id="password"
                                    placeholder="Enter Password" name="password" maxlength="20" readonly
                                    onclick="this.removeAttribute('readonly');">
                            </div>
                            <div class="col-sm-6 mb-3">
                                <input type="password" autocomplete="off" class="form-control" id="confirm_password"
                                    placeholder="Enter Confirm Password" name="confirm_password" maxlength="20" readonly
                                    onclick="this.removeAttribute('readonly');">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" rel="{{ old('role') }}">
                                <option value="">Select role</option>
                                @foreach ($roles as $role)
                                @if (old('role') == Arr::get($role, 'id'))
                                <option value="{{ trim(Arr::get($role, 'id')) }}" selected>
                                    {{ trim(Arr::get($role, 'name')) }}</option>
                                @elseif(old('_token') == null)
                                )
                                <option value="{{ $role->id }}"
                                    {{ in_array($role->name, $userRole) ? 'selected' : '' }}>
                                    {{ $role->name }}</option>
                                @else
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endif
                                @endforeach
                            </select>

                        </div>
                       
                        

            <button type="submit" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3">Update
                user</button>
            <a href="{{ route('users.index') }}" class="btn btn-default">Cancel</a></button>
        </div>
</div>
</div>
</form>
</div>
<script>
$("#role").on('change', function() {
    const roleSelect = $('#role').val();
    //const new_area_id = $('#new_area_id_div');
    const provincial_assembly_id = $('#provincial_assembly_id_div');
    const national_assembly_id = $('#national_assembly_id_div');
    const roleArray = ['3', '4'];
    if (!roleArray.includes(roleSelect)) {
        // Code to execute if the requested role is in the array
        //new_area_id.hide();
        provincial_assembly_id.hide();
        national_assembly_id.hide();
    } else {
        // Code to execute if the requested role is not in the array
        //new_area_id.show();
        provincial_assembly_id.show();
        national_assembly_id.show();
    }
});

function generateDropDownOption(data, id) {
    if (data && Object.keys(data).length !== 0) {
        let isFirstOption = true;
        $.each(data, function(key, value) {
            let option = $('<option></option>').attr('value', value.id).text(value.name);

            if (isFirstOption) {
                option.attr('selected', 'selected');
                isFirstOption = false; // Set to false after selecting the first option
            }


            $('#' + id).append(option);
        });

        $('#' + id).trigger('change.select2');
    }
}


$(document).ready(function(e) {
    $('.mySelect').select2();
});
</script>
<!--Select 2 -->
<link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
<script src="{!! url('assets/js/select2.min.js') !!}"></script>
@endsection
