@extends('backend.layouts.app-master')
@section('content')
    <style>
        .row {
            margin-right: 30px !important;
            margin-left: 0px !important;
        }

        body {
            font-family: Arial;
        }

        /* Style the tab */
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .col-sm-2 {
            margin-top: 7px !important;
        }
    </style>

    <div
        class="page-title-section border-bottom mb-0.5 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Complaint Details</h3>    
            <h5 class="text-dark mb-0"><b>Complaint #</b>{{ Arr::get($complaintData, 'complaint_number') }}</h5>
        </div>
        <div class="text-lg-end text-center">
            <div class="btn-group chart-filter-btns mt-lg-0 mt-4" role="group">
                @if (Auth::user()->can('complaints.follow.up'))
                    <a class="btn btn-sm rounded bg-theme-dark-300 me-2 filters border-0 text-theme-yellow-light fw-bold"
                        href="{{ route('complaints.follow.up', $complaintData->id) }}">Follow
                        Up</a>
                @endif
                @if (Auth::user()->can('assign.complaint'))
                    <a class="btn btn-sm rounded bg-theme-dark-300 me-2 assign-to-btn border-0 text-theme-yellow-light fw-bold"
                        data-complaint-id="{{ $complaintData->id }}">Assign</a>
                @endif

                @if (Auth::user()->can('complaints.destroy'))
                    {!! Form::open([
                        'method' => 'DELETE',
                        'route' => ['complaints.destroy', $complaintData->id],
                        'style' => 'display:inline',
                        'onsubmit' => 'return ConfirmDelete()',
                    ]) !!}
                    {!! Form::submit('Delete', [
                        'class' => 'btn btn-sm rounded bg-theme-dark-300 me-2 filters border-0 text-theme-yellow-light fw-bold',
                    ]) !!}
                    {!! Form::close() !!}
                @endif
            </div>
        </div>
    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="mb-4 border-0">
            <div class="card-body">

                <div class="inner-row d-flex gap-4 my-1">
                    <div class="inner-label">
                        <p class="mb-0"><b>Order Id:</b></p>
                    </div>
                    <div class="inner-value">
                        <p class="text-muted mb-0"> {{ Arr::get($complaintData, 'order_id') }}</p>
                    </div>
                </div>

                <div class="inner-row d-flex gap-4 my-1">
                    <div class="inner-label">
                        <p class="mb-0"><b>Status:</b></p>
                    </div>
                    <div class="inner-value">
                        <p class="text-muted mb-0"> {{ Arr::get($complaintData->complaintStatus, 'name') }}</p>
                    </div>
                </div>

                <div class="inner-row d-flex gap-4 my-1">
                    <div class="inner-label">
                        <p class="mb-0"><b>Priority:</b></p>
                    </div>
                    <div class="inner-value">
                        <p class="text-muted mb-0">{{ Arr::get($complaintData->complaintPriority, 'name') }}</p>
                    </div>
                </div>
            
                <div class="inner-row d-flex gap-4 mb-0.5">
                    <div class="inner-label">
                        <p class="mb-0"><b>Query Type:</b></p>
                    </div>
                    <div class="inner-value">
                        <p class="text-muted mb-0">{{ config('constants.query_type.'.Arr::get($complaintData, 'query_type'))  }}</p>
                    </div>
                </div>

                <div class="inner-row d-flex gap-4 mb-0.5">
                    <div class="inner-label">
                        <p class="mb-0"><b>Complaint Type:</b></p>
                    </div>
                    <div class="inner-value">
                        <p class="text-muted mb-0">{{ config('constants.complaint_type.'.Arr::get($complaintData, 'complaint_type'))  }}</p>
                    </div>
                </div>

                <div class="inner-row d-flex gap-4 mb-0.5">
                    <div class="inner-label">
                        <p class="mb-0"><b>Inquiry Type:</b></p>
                    </div>
                    <div class="inner-value">
                        <p class="text-muted mb-0">{{ config('constants.inquiry_type.'.Arr::get($complaintData, 'inquiry_type'))  }}</p>
                    </div>
                </div>

                <div class="inner-row d-flex gap-4 my-1">
                    <div class="inner-label">
                        <p class="mb-0"><b>Name:</b></p>
                    </div>
                    <div class="inner-value">
                        <p class="text-muted mb-0"> {{ Arr::get($complaintData, 'name') }}</p>
                    </div>
                </div>

                <div class="inner-row d-flex gap-4 my-1">
                    <div class="inner-label">
                        <p class="mb-0"><b>Email:</b></p>
                    </div>
                    <div class="inner-value">
                        <p class="text-muted mb-0"> {{ Arr::get($complaintData, 'email') }}</p>
                    </div>
                </div>

                <div class="inner-row d-flex gap-4 my-1">
                    <div class="inner-label">
                        <p class="mb-0"><b>Mobile Number:</b></p>
                    </div>
                    <div class="inner-value">
                        <p class="text-muted mb-0"> {{ Arr::get($complaintData, 'mobile_number') }}</p>
                    </div>
                </div>

                <div class="inner-row d-flex gap-4 my-1">
                    <div class="inner-label">
                        <p class="mb-0"><b>Assigned To:</b></p>
                    </div>
                    <div class="inner-value">
                        <p class="text-muted mb-0"> {{ Arr::get($complaintData->user, 'name') }}
                            @if (!empty(Arr::get($complaintData->user, 'email')))
                                ({{ Arr::get($complaintData->user, 'email') }})
                            @endif
                        </p>
                    </div>
                </div>

            </div>

            <!--Row 1-->
            <div class="row">
                <div class="col-md-12">
                    <!--card 1-->
                    <div class="card mb-3">
                        <h6 class="card-header">Additional Comments</h6>
                        <div class="card-body">
                            <div class="row">
                                {!! Arr::get($complaintData, 'comments') !!}
                            </div>
                        </div>
                    </div>
                    <!--//card 1-->
                </div>
            </div>
            <!--Row 1-->

            <!--Row 4-->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3 p-0">
                        <h6 class="card-header">Complaint Documents</h6>
                        <div class="card-body">
                            <div class="row">
                                <div class="container">
                                    <div class="row flex-wrap">
                                        @if (!empty($complaintDocument))
                                            @foreach ($complaintDocument as $row)
                                                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-4 mt-4">
                                                    <div class="py-2 px-2" style="box-shadow: 0 0 10px 0 #ddd;">
                                                        <h6>{{ config('constants.complaint_form_images.'.Arr::get($row, 'document_name'));  }}</h6>
                                                        <div class="d-flex align-items-center gap-2">
                                                            @if (Helper::isFileExtensionForIcon(Arr::get($row, 'file')))
                                                                <img class="center"
                                                                    src="{{ asset(config('constants.files.filetypes')) }}/{{ Helper::isFileExtensionForIcon(Arr::get($row, 'file')) }}">
                                                            @else
                                                                <img class="center"
                                                                    src="{{ asset(config('constants.files.complaint_documents')) }}/{{ Arr::get($row, 'file') }}">
                                                            @endif
                                                            <a class="viewFile"
                                                                data-filepath="{{ asset(config('constants.files.complaint_documents')) }}/{{ Arr::get($row, 'file') }}"
                                                                download>View</a>
                                                            <a class="downloadFile"
                                                                data-filepath="{{ asset(config('constants.files.complaint_documents')) }}/{{ Arr::get($row, 'file') }}"
                                                                download>Download</a>
                                                            </div>
                                                            
                                                        </div>
                                                </div>
                                            @endforeach
                                            @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!--Row 4-->


            <!--Row 3-->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-3 p-0">
                        <h6 class="card-header">Follow Ups</h6>
                        <div class="card-body">
                            <div class="row">
                                @if (!empty($complaintFollowUps))
                                    @foreach ($complaintFollowUps as $complaintFollowUp)
                                        <div class="py-2 px-4 border">
                                            <div class="row">
                                                <div class="p-0"><strong> Created By:
                                                        {{ ucwords(Arr::get($complaintFollowUp->user, 'name')) }}</strong>
                                                    <small>|
                                                        @if (!empty(Arr::get($complaintFollowUp, 'created_at')))
                                                            {{ date('M d, Y', strtotime(Arr::get($complaintFollowUp, 'created_at'))) }}
                                                        @endif
                                                        | {{ Arr::get($complaintFollowUp->complaintStatus, 'name') }} 
                                                    </small>
                                                </div>
                                            </div>
                                            <p class="font-weight-bold ">{!! Arr::get($complaintFollowUp, 'description') !!}
                                            </p>
                                        </div>
                                    @endforeach
                                    <div class="d-flex justify-content-start">
                                        {{ $complaintFollowUps->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <!--Row 3-->

        </div>


    </div>
    <!--Assign To Modal -->
    <div id="modalDiv"></div>

    <section style="background-color: #eee;" id="printSection" class="mb-3">





        </div>
    </section>
    <script>
        $(document).on('click', '.downloadFile', function(e) {
            e.preventDefault(); //stop the browser from following

            var filepath = $(this).attr('data-filepath');
            var link = document.createElement('a');
            link.href = filepath;
            link.download = filepath.split('/').pop(); // Extract the file name from the path
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });

        $(document).on('click', '.viewFile', function(e) {
            e.preventDefault(); //stop the browser from following

            var filepath = $(this).attr('data-filepath');
            window.open(filepath, '_blank');
        });
    </script>
    <script>
        //ReAssignComplaint
        

        //ReAssignComplaint

        $(document).ready(function() {
           
            $(document).on('click', '#assignBtn', function(event) {

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
                var formData = $('#assignComplaintForm').serialize();

                // Send an AJAX request
                $.ajax({
                    type: 'POST',
                    url: $('#assignComplaintForm').attr('action'), // Get the form action URL
                    data: formData, // Send form data
                    success: function(response) {
                        $('#UserErrorMsg').html('');
                        $('#PriorityErrorMsg').html('');

                        if (response.errors) {
                            //userId
                            if (response.errors.userId) {
                                $('#UserErrorMsg').show();
                                $('#UserErrorMsg').append(response.errors.userId);
                            }

                            if (response.errors.priorityId) {
                                $('#PriorityErrorMsg').show();
                                $('#PriorityErrorMsg').append(response.errors.priorityId);
                            }

                            $(".loader").removeClass("show");

                        } else if (response.status) {
                            toastr.success(response.message);
                            setTimeout(() => {
                                window.location.href =
                                    "{{ route('complaints.show', ['complaintId' => $complaintData->id])}}";
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

        $(document).on('click', '.assign-to-btn', function(event) {


            // Extract the complaint ID from the data attribute
            var complaintId = $(this).data('complaint-id');
            $(".loader").addClass("show");
            $('#modalDiv').html('');
            var url = '{{ route('assign.complaint.form') }}'
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url,
                method: 'post',
                data: {
                    complaintId: complaintId,
                },
                success: function(response) {
                    $('#modalDiv').html(response);
                    $('#assignToModal').modal("show");
                    $(".loader").removeClass("show");
                },
                error: function(data, textStatus, errorThrown) {
                    $(".loader").removeClass("show");
                    console.log(JSON.stringify(data));
                }

            });
        });
    </script>
    <!--Select 2 -->
    <link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
    <script src="{!! url('assets/js/select2.min.js') !!}"></script>
@endsection
