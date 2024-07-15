@extends('backend.layouts.app-master')

@section('content')
<div class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">Search Complains</h3>
        <small class="text-white">Manage your Complaints here.</small>

    </div>
    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
        <div class="btn-group" role="group">
            @if(Auth::user()->can('complaints.create.form'))
            <a href="{{ route('complaints.create.form') }}" class="text-decoration-none">
                <small type="button" class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i class="fa fa-solid fa-file-circle-plus"></i><span>New Complaint</span></small>
            </a>
            @endif
            <small id="showFilterBox" type="button" class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i class="fa fa-solid fa-filter"></i> <span>Filter</span>
            </small>
        </div>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">
    <div class="bg-light p-4 rounded">

        <!--Assign To Modal -->
        <div id="modalDiv"></div>

        <div class="" id="filterBox" style="display:none;">
            <form class="form-inline" method="GET" action="{{ route('complaints.index') }}">
                <div class="row mb-3">
                    <div class="col-xxl-3 col-xl-3 col-lg-12 col-md-12">
                        <input type="text" class="form-control p-2" autocomplete="off" name="complaint_number" value="" placeholder="Complaint No.">
                    </div>

                </div>
                <div class="row mb-3">

                    
                </div>
            </form>
        </div>



        <div class="d-flex my-2">
            Showing results {{ ($complaints->currentPage() - 1) * config('constants.per_page') + 1 }} to
            {{ min($complaints->currentPage() * config('constants.per_page'), $complaints->total()) }} of
            {{ $complaints->total() }}
        </div>

        <div class="table-scroll-hr">
            <table class="table table-bordered table-striped table-compact table-sm">
                <thead>


                    <tr>
                        <th>Complaint #</th>
                        <th>Title</th>
                        <th>Mobile</th>
                        <th>CNIC</th>
                        @if(!Auth::user()->hasRole('Manager'))
                        <th>Approval</th>
                        <th>Approved By</th>
                        @endif
                        <!-- <th>Level One</th>
                    <th>Level Two</th>
                    <th>Level Three</th>
                    <th>City</th>
                    <th>District</th>
                    <th>UC</th>
                    <th>Area</th> -->
                        <th>Created By</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Due</th>
                        <th width="20%" colspan="6">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $complaintTitle = ''; ?>
                    @foreach ($complaints as $key => $complaint)
                    <?php
                    $complaintTitle .=  "<span class='badge bg-theme-green me-1'>" . Arr::get($complaint->levelOneCategory, 'name') . "</span><span class='badge bg-theme-green me-1'>" . Arr::get($complaint->levelTwoCategory, 'name') . "</span><br/><span class='badge bg-theme-green me-1'>" . Arr::get($complaint->levelThreeCategory, 'name') . "</span>"; ?>

                    <tr>
                        <td>{{Arr::get($complaint, 'complaint_num')}}</td>
                        <!-- <td>{{$complaintTitle}}</td> -->
                        <!-- <td>{{(strlen($complaintTitle) > 15) ? substr($complaintTitle, 0, 15) . "<br>" : $complaintTitle}}
                        </td>
                        -->
                        <td>
                            <?php echo $complaintTitle; ?>
                        </td>


                        <td>{{Arr::get($complaint, 'mobile_number')}}</td>
                        <td>{{Arr::get($complaint, 'cnic')}}</td>
                        @if(!Auth::user()->hasRole('Manager'))
                        <td>{{Arr::get($complaint, 'is_approved') ? 'Approved' : 'Pending'}}</td>
                        <td>{{Arr::get($complaint, 'approved_by')}}</td>
                        @endif
                        <!-- <td>{{Arr::get($complaint->levelOneCategory,'name')}}</td>
                    <td>{{Arr::get($complaint->levelTwoCategory,'name')}}</td>
                    <td>{{Arr::get($complaint->levelThreeCategory,'name')}}</td>
                    <td>{{Arr::get($complaint->city,'name')}}</td>
                    <td>{{Arr::get($complaint->district,'name')}}</td>
                    <td>{{Arr::get($complaint->unionCouncil,'name')}}</td>
                    <td>{{Arr::get($complaint->newArea,'name')}}</td> -->
                        @if($complaint->complainant_id === $complaint->created_by)
                        <td>{{Arr::get($complaint->complainant,'full_name')}}</td>
                        @else
                        <td>{{Arr::get($complaint->userBy,'name')}}</td>
                        @endif
                        <td>{{Arr::get($complaint->complaintStatus,'name')}}</td>
                        <td>{{ date("d,M,Y", strtotime(Arr::get($complaint, 'created_at'))) }}
                            <br />
                            {{ date("h:i A", strtotime(Arr::get($complaint, 'created_at'))) }}
                        </td>
                        <td>
                            @if(Arr::get($complaint->complaintPriority, 'id'))
                            {{ date("d,M,Y", strtotime(Arr::get($complaint, 'created_at') . '+ ' . Arr::get($complaint->complaintPriority, 'days') . ' days')) }}
                            @endif

                        </td>
                        @if(Auth::user()->can('complaints.show'))
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('complaints.show', $complaint->id) }}">View</a>
                        </td>
                        @endif
                        <!-- @if(Auth::user()->can('complaints.follow.up'))
                        <td>
                            <a class="btn btn-success btn-sm"
                                href="{{ route('complaints.follow.up', $complaint->id) }}">Follow
                                Up</a>
                        </td>
                        @endif
                        @if(Auth::user()->can('assign.complaint'))
                        <td>
                            <a class="btn btn-primary btn-sm assign-to-btn"
                                data-complaint-id="{{ $complaint->id }}">Priority</a>
                        </td>
                        @endif
                        @if(Auth::user()->can('re-assign.complaint'))
                        <td>
                            <a class="btn btn-warning btn-sm re-assign-btn"
                                data-complaint-id="{{ $complaint->id }}">Re-Assign
                            </a>
                        </td>
                        @endif
                        @if(Auth::user()->can('complaints.destroy'))
                        <td>
                            {!! Form::open([
                            'method' => 'DELETE',
                            'route' => [
                            'complaints.destroy',
                            $complaint->id
                            ],
                            'style' => 'display:inline',
                            'onsubmit' => 'return ConfirmDelete()'
                            ]) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                            {!! Form::close() !!}
                        </td>
                        @endif
                        @if(Auth::user()->can('can.approve.complaints'))
                        <td>
                            {!! Form::open([
                            'method' => 'POST',
                            'route' => [
                            'can.approve.complaints',
                            $complaint->id
                            ],
                            'style' => 'display:inline',
                            'onsubmit' => 'return ConfirmApprove()'
                            ]) !!}
                            {!! Form::submit('Approve',['class' => 'btn btn-success btn-sm']) !!}
                            {!! Form::close() !!}
                        </td>
                        @endif -->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex">
            {!! $complaints->appends(Request::except('page'))->render() !!}
        </div>

    </div>
</div>
<!--Assign To Modal -->
<div id="modalDiv"></div>


<script type="text/javascript">
    function ConfirmDelete() {
        var x = confirm("Are you sure you want to delete?");
        if (x) {
            return true;
        } else {

            event.preventDefault();
            return false;
        }
    }

    function ConfirmApprove() {
        var x = confirm("Are you sure you want to approve this complaint?");
        if (x) {
            return true;
        } else {

            event.preventDefault();
            return false;
        }
    }
</script>
<script>
    $("#showFilterBox").click(function() {
        $("#filterBox").toggle();
    });


    // $(document).on('click', '.assign-to-btn', function(event) {


    //     // Extract the complaint ID from the data attribute
    //     var complaintId = $(this).data('complaint-id');
    //     $(".loader").addClass("show");
    //     $('#modalDiv').html('');
    //     var url = '{{ route("assign.complaint.form") }}'
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });

    //     $.ajax({
    //         url: url,
    //         method: 'post',
    //         data: {
    //             complaintId: complaintId,
    //         },
    //         success: function(response) {
    //             $('#modalDiv').html(response);
    //             $('#assignToModal').modal("show");
    //             $(".loader").removeClass("show");
    //         },
    //         error: function(data, textStatus, errorThrown) {
    //             $(".loader").removeClass("show");
    //             console.log(JSON.stringify(data));
    //         }

    //     });
    // });

    //ReAssign
    // $(document).on('click', '.re-assign-btn', function(event) {
    // // Extract the complaint ID from the data attribute
    //     var complaintId = $(this).data('complaint-id');
    //     $(".loader").addClass("show");
    //     $('#modalDiv').html('');
    //     var url = '{{ route("re-assign.complaint.form") }}'
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });

    //     $.ajax({
    //         url: url,
    //         method: 'post',
    //         data: {
    //             complaintId: complaintId,
    //         },
    //         success: function(response) {
    //             $('#modalDiv').html(response);
    //             $('#reAssignToModal').modal({
    //                 backdrop: 'static', // Prevents modal from closing on backdrop click
    //                 keyboard: false // Optionally prevent modal closing on Esc key
    //             });
    //             $('#reAssignToModal').modal("show");
    //             $(".loader").removeClass("show");
    //             $('.select2').select2({
    //                     dropdownParent: $('#reAssignToModal'), // Ensure the dropdown is appended to the modal
    //                 });
    //         },
    //         error: function(data, textStatus, errorThrown) {
    //             $(".loader").removeClass("show");
    //             console.log(JSON.stringify(data));
    //         }

    //     });
    // });


    // // Bind close button of modal to actually close it
    // $('.modal .close, .modal button[data-dismiss="modal"]').click(function() {
    //     $(this).closest('.modal').modal('hide');
    // });
</script>

<script>
    // $(document).ready(function() {
    //     // When the "Assign" button is clicked
    //     // $('#assignBtn').click(function() {
    //     $(document).on('click', '#assignBtn', function(event) {

    //         $(".loader").addClass("show");

    //         toastr.options = {
    //             "closeButton": true,
    //             "timeOut": "3000",
    //             "extendedTimeOut": "1000",
    //             "progressBar": true,
    //             "positionClass": "toast-top-right",
    //             "showEasing": "swing",
    //             "hideEasing": "linear",
    //             "showMethod": "fadeIn",
    //             "hideMethod": "fadeOut"
    //         };

    //         // Get the form data
    //         var formData = $('#assignComplaintForm').serialize();

    //         // Send an AJAX request
    //         $.ajax({
    //             type: 'POST',
    //             url: $('#assignComplaintForm').attr('action'), // Get the form action URL
    //             data: formData, // Send form data
    //             success: function(response) {
    //                 $('#UserErrorMsg').html('');
    //                 $('#PriorityErrorMsg').html('');

    //                 if (response.errors) {
    //                     //userId
    //                     if (response.errors.userId) {
    //                         $('#UserErrorMsg').show();
    //                         $('#UserErrorMsg').append(response.errors.userId);
    //                     }

    //                     if (response.errors.priorityId) {
    //                         $('#PriorityErrorMsg').show();
    //                         $('#PriorityErrorMsg').append(response.errors.priorityId);
    //                     }

    //                 } else if (response.status) {
    //                     toastr.success(response.message);
    //                     setTimeout(() => {
    //                         window.location.href =
    //                             "{{ route('complaints.index') }}";
    //                     }, 3000);
    //                 } else {
    //                     toastr.error(response.message);
    //                     $(".loader").removeClass("show");
    //                 }

    //             },
    //             error: function(xhr, status, error) {
    //                 toastr.error(response.message);
    //                 $(".loader").removeClass("show");
    //             }
    //         });
    //     });
    // });


    //ReAssignComplaint

    $(document).ready(function() {
        // When the "Re Assign" button is clicked
        // $('#reAssignBtn').click(function() {
        $(document).on('click', '#reAssignBtn', function(event) {

            $(".loader").addClass("show");

            toastr.options = {
                "closeButton": true,
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Get the form data
            var formData = $('#reAssignComplaintForm').serialize();

            // Send an AJAX request
            $.ajax({
                type: 'POST',
                url: $('#reAssignComplaintForm').attr('action'), // Get the form action URL
                data: formData, // Send form data
                success: function(response) {
                    $('#MnaErrorMsg').html('');
                    $('#MpaErrorMsg').html('');


                    if (response.errors) {
                        //mnaId
                        if (response.errors.mnaId) {
                            $('#MnaErrorMsg').show();
                            $('#MnaErrorMsg').append(response.errors.mnaId);
                            $(".loader").removeClass("show");
                        }

                        if (response.errors.mpaId) {
                            $('#MpaErrorMsg').show();
                            $('#MpaErrorMsg').append(response.errors.mpaId);
                            $(".loader").removeClass("show");
                        }

                    } else if (response.status) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            window.location.href =
                                "{{ route('complaints.index') }}";
                        }, 3000);
                    } else {
                        toastr.error(response.message);
                        $(".loader").removeClass("show");
                    }

                },
                error: function(xhr, status, error) {
                    toastr.error(response.message);
                    $(".loader").removeClass("show");
                }
            });
        });
    });
</script>
<!--Select 2 -->
<link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
<script src="{!! url('assets/js/select2.min.js') !!}"></script>
@endsection