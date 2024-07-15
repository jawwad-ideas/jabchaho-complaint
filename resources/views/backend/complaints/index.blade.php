@extends('backend.layouts.app-master')

@section('content')
<div class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">Search Complains</h3>
        <small class="text-white">Manage your Complaints here.</small>

    </div>
    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
        <div class="btn-group" role="group">
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
                        <th>Id #</th>
                        <th>Order Id</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="20%" colspan="6">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $complaintTitle = ''; ?>
                    @foreach ($complaints as $key => $complaint)
                    <tr>
                        <td>{{Arr::get($complaint, 'id')}}</td>
                        <td>{{Arr::get($complaint, 'order_id')}}</td>
                        <td>{{Arr::get($complaint, 'name')}}</td>
                        <td>{{Arr::get($complaint, 'mobile_number')}}</td>
                        <td>{{Arr::get($complaint, 'email')}}</td>
                        <td>{{Arr::get($complaint->complaintStatus,'name')}}</td>
                        <td>{{ date("d,M,Y", strtotime(Arr::get($complaint, 'created_at'))) }}
                            <br />
                            {{ date("h:i A", strtotime(Arr::get($complaint, 'created_at'))) }}
                        </td>
                        @if(Auth::user()->can('complaints.show'))
                        <td>
                            <a class="btn btn-info btn-sm" href="{{ route('complaints.show', $complaint->id) }}">View</a>
                        </td>
                        @endif
                        
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