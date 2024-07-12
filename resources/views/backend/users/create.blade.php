@extends('backend.layouts.app-master')

@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">Add New User</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>

    <form method="POST" action="">
        @csrf
        <div class="row">
            <div class="col-lg-12">

                <div class="form-section mb-5">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Add New User And Assign Role.</h4>
                    </div>
                </div>

                <div class="container mt-4">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input value="{{ old('name') }}" type="text" class="form-control" name="name"
                            placeholder="Name">

                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input value="{{ old('email') }}" type="email" class="form-control" name="email"
                            placeholder="Email address">

                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input value="{{ old('username') }}" type="text" class="form-control" name="username"
                            placeholder="Username">

                    </div>

                    <div class="mb-3">
                        <label for="PassWord" class="form-label">Password</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <input type="password" autocomplete="off" class="form-control" id="password"
                                    placeholder="Enter Password" name="password" maxlength="20">
                            </div>
                            <div class="col-sm-6">
                                <input type="password" autocomplete="off" class="form-control" id="confirm_password"
                                    placeholder="Enter Confirm Password" name="confirm_password" maxlength="20">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="mySelect form-control form-control-sm" id="role" name="role">
                            <option value=''>--Select--</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->id }}">
                                {{ $role->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- <div class="mb-3" id="new_area_id_div">
                            <label for="new_area_id" class="col-sm-3 col-form-label col-form-label-sm fw-light">New
                                Area</label>
                            <select class="mySelect form-control form-control-sm" id="new_area_id"  name="new_area_id" onchange="getNewAreaGridData(event);checkFieldValidation(this);"
                                onchange="getAjaxData('NewArea', 'new_area_id', event, 'new_area_id', 'area');">
                                <option value=''>--Select--</option>
                                @foreach ($newArea as $area)
                                    <option value="{{ $area->id }}"
                                        {{ old('new_area_id') == $area->id ? 'selected' : '' }}>
                                        {{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div> -->

                    <div class="mb-3" id="provincial_assembly_id_div">
                        <label for="provincial_assembly_id"
                            class="col-sm-3 col-form-label col-form-label-sm fw-light">PS</label>
                        <select class="mySelect form-control form-control-sm" id="provincial_assembly_id"
                            name="provincial_assembly_id">
                            <option value=''>--Select--</option>
                            @foreach ($pA as $ps)
                            <option value="{{ $ps->id }}"
                                {{ old('provincial_assembly_id') == $ps->id ? 'selected' : '' }}>
                                {{ $ps->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" id="national_assembly_id_div">
                        <label for="national_assembly_id"
                            class="col-sm-3 col-form-label col-form-label-sm fw-light">NA</label>
                        <select class="mySelect form-control form-control-sm" id="national_assembly_id"
                            name="national_assembly_id">
                            <option value=''>--Select--</option>
                            @foreach ($nA as $na)
                            <option value="{{ $na->id }}"
                                {{ old('national_assembly_id') == $na->id ? 'selected' : '' }}>
                                {{ $na->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>&nbsp;</div>
                    <div class="mb-3">
                        <button type="submit"
                            class="btn bg-theme-green text-white d-inline-flex align-items-center gap-3">Save
                            user</button>
                        <a href="{{ route('users.index') }}" class="btn btn-default">Back</a>
                    </div>
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
    const roleArray = ['3','4'];
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

// function getNewAreaGridData() {
//     $(".loader").addClass("show");

//     toastr.options =
//     {
//         "closeButton": true,
//         "timeOut": "3000",
//         "extendedTimeOut": "1000",
//         "progressBar": true,
//         "positionClass": "toast-top-right",
//         "showEasing": "swing",
//         "hideEasing": "linear",
//         "showMethod": "fadeIn",
//         "hideMethod": "fadeOut"
//     };

//     $.ajaxSetup({
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         }
//     });

//     var newAreaId = $('#new_area_id').val();
//     var url = '{{ route("get.new.area.grid.data") }}'+'/'+newAreaId;

//     $.ajax({
//         url: url,
//         method: 'get',
//         success: function (result) {

//             $('.new_area').val('');
//             //$('.new_area').select2('destroy').val("").select2();

//             $('.new_area').html('');
//             $('.new_area').html("<option value=''>--Select--</option>");

//             //District
//             // generateDropDownOption(result.district,'district_id');

//             //Sub Division
//             // generateDropDownOption(result.sub_division,'sub_division_id');

//             //union_council_id
//             // generateDropDownOption(result.union_council,'union_council_id');

//             //Charge
//             // generateDropDownOption(result.charge,'charge_id');

//             //Ward
//             //generateDropDownOption(result.ward,'ward_id');

//             //NA
//             generateDropDownOption(result.national_assembly, 'national_assembly_id');

//             //PS
//             generateDropDownOption(result.provincial_assembly, 'provincial_assembly_id');

//             $(".loader").removeClass("show");

//         },
//         error: function (data, textStatus, errorThrown) {
//             $(".loader").removeClass("show");
//             if (data) {
//                 toastr.error('Something went wrong. Please try again.');
//                 console.log(JSON.stringify(data));
//             }


//         }

//     });
// }
$(document).ready(function(e) {
    $('.mySelect').select2();
});
</script>
<!--Select 2 -->
<link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
<script src="{!! url('assets/js/select2.min.js') !!}"></script>
@endsection
