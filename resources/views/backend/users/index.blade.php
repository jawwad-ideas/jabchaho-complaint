@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Users</h3>
            <small class="text-dark">Manage your users here.</small>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                <a href="{{ route('users.create') }}" class="text-decoration-none">
                    <small id="" type="button"
                        class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-user-plus"></i><span>New User</span></small>
                </a>
                <small id="showFilterBox" type="button"
                    class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                        class="fa fa-solid fa-filter"></i> <span>Filter</span>
                </small>

            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-2 rounded">


            {{-- <form class="form-inline" method="GET">
            <input type="hidden" id="dashboardFilter" placeholder="dashboardFilter" name="dashboard_filter"
                maxlength="50" value="{{ $dashboardFilter}}">
        </form> --}}
            <!--Assign To Modal -->
            <div id="modalDiv"></div>

            <div class="" id="filterBox" style="display:none;">
                <form class="form-inline" method="GET" action="{{ route('users.index') }}">
                    <div class="row mb-3">
                        <div class="col-lg-8 d-flex flex-wrap">

                            <div class="col-sm-6 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="full_name"
                                    value="{{ $filterData['full_name'] ?? '' }}" placeholder="Full Name">
                            </div>
                            <div class="col-sm-6 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="user_email"
                                    value="{{ $filterData['user_email'] ?? '' }}" placeholder="Email">
                            </div>
                            <div class="col-sm-6 px-2 mt-4">
                                <input type="text" class="form-control p-2" autocomplete="off" name="user_name"
                                    value="{{ $filterData['user_name'] ?? '' }}" placeholder="Username">
                            </div>
                        </div>

                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                                id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('users.index') }}"
                                class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-flex my-2">
                Showing results {{ ($users->currentPage() - 1) * config('constants.per_page') + 1 }} to
                {{ min($users->currentPage() * config('constants.per_page'), $users->total()) }} of {{ $users->total() }}
            </div>

            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact ">
                    <thead>
                        <tr>
                            <th scope="col" width="1%">#</th>
                            <th scope="col" width="15%">Name</th>
                            <th scope="col" width="15%">Email</th>
                            <th scope="col" width="10%">Username</th>
                            <th scope="col" width="10%">Roles</th>
                            <th scope="col" width="1%" colspan="3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            @if ($user->email == 'admin@gmail.com')
                                @php continue; @endphp
                            @endif

                            <tr>
                                <th scope="row">{{ $user->id }}</th>
                                <td width="15%">{{ $user->name }}</td>
                                <td width="15%">{{ $user->email }}</td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    @foreach ($user->roles as $role)
                                        <span class="badge bg-theme-dark-300">{{ $role->name }}</span>
                                    @endforeach
                                </td>
                                <td><a href="{{ route('users.show', $user->id) }}" class="btn bg-theme-yellow btn-sm"><i class="fa fa-eye"></i></a>
                                </td>
                                <td><a href="{{ route('users.edit', $user->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                                </td>
                                <td>
                                {!! Form::open([
                                    'method' => 'DELETE',
                                    'route' => ['users.destroy', $user->id],
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
                    </tbody>
                </table>
            </div>

            <div class="d-flex">
                {!! $users->links() !!}
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

        $(document).on('click', '.assign-to-btn', function(event) {


            // Extract the complaint ID from the data attribute
            var complaintId = $(this).data('user-id');
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


        // Bind close button of modal to actually close it
        $('.modal .close, .modal button[data-dismiss="modal"]').click(function() {
            $(this).closest('.modal').modal('hide');
        });
    </script>

    <script>
        $(document).ready(function() {
            // When the "Assign" button is clicked
            // $('#assignBtn').click(function() {
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
@endsection
