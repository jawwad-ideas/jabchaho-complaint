@extends('backend.layouts.app-master')

@section('content')
<style>
tr[data-url] {
  cursor: pointer;
  transition: background-color 0.3s;
}

tr[data-url]:hover {
  background-color: #f0f0f0;
}
</style>
<div class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Search Complains</h3>
        <small class="text-dark">Manage your Complaints here.</small>

    </div>


    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
        <div class="btn-group" role="group">
            @if (Auth::user()->can('complaints.create.form'))
                <a href="{{ route('complaints.create.form') }}" class="text-decoration-none">
                    <small id="" type="button"
                        class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-exclamation-circle"></i><span>New Complain</span></small>
                </a>
            @endif
            <small id="showFilterBox" type="button"
                class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                    class="fa fa-solid fa-filter"></i> <span>Filter</span>
            </small>

        </div>
    </div>


</div>
<div class="page-content bg-white p-lg-5 px-2">
    <div class="bg-light p-2 rounded">

        <!--Assign To Modal -->
        <div id="modalDiv"></div>

        <div class="" id="filterBox" 
                @if (request()->has('complaint_number') || request()->has('name')) 
                        style="display:block;" 
                @else 
                        style="display:none;" 
                @endif
        >
            <form class="form-inline" method="GET" action="{{ route('complaints.index') }}">
                <div class="row mb-3">
                    <div class="col-xxl-3 col-xl-3 col-lg-12 col-md-12 mb-2">
                        <input type="text" class="form-control p-2" autocomplete="off" name="complaint_number" value="{{ $complaint_number ?? '' }}" placeholder="Complaint No.">
                    </div>

                    <div class="col-xxl-3 col-xl-3 col-lg-12 col-md-12 mb-2">
                        <input type="text" class="form-control p-2" autocomplete="off" name="order_id" value="{{ $order_id ?? '' }}" placeholder="Order No.">
                    </div>

                    <div class="col-xxl-3 col-xl-3 col-lg-12 col-md-12 mb-2">
                        <input type="text" class="form-control p-2" autocomplete="off" name="mobile_number" value="{{ $mobile_number ?? '' }}" placeholder="Mobile">
                    </div>


                    <div class="col-xxl-3 col-xl-3 col-lg-12 col-md-12 mb-2">
                        <input type="text" class="form-control p-2" autocomplete="off" name="name" value="{{ $name ?? '' }}" placeholder="Name">
                    </div>

                    <div class="col-xxl-3 col-xl-3 col-lg-12 col-md-12 mb-2">
                        <input type="text" class="form-control p-2" autocomplete="off" name="email" value="{{ $email ?? '' }}" placeholder="Email">
                    </div>

                    <div class="col-xxl-3 col-xl-3 col-lg-12 col-md-12 mb-2">
                        <select class="form-select p-2" id="complaint_status_id" name="complaint_status_id">
                            <option value=''>Select Status</option>
                            @if(!empty($complaintStatuses) )
                            @foreach($complaintStatuses as $complaintStatus)
                            @if($complaint_status_id == Arr::get($complaintStatus, 'id'))
                            <option value="{{ trim(Arr::get($complaintStatus, 'id')) }}" selected>
                                {{trim(Arr::get($complaintStatus, 'name'))}}</option>
                            @else
                            <option value="{{trim(Arr::get($complaintStatus, 'id'))}}">
                                {{trim(Arr::get($complaintStatus, 'name'))}}</option>
                            @endif
                            @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-xxl-3 col-xl-3 col-lg-12 col-md-12 mb-2">
                        <select class="form-select p-2" id="complaint_priority_id" name="complaint_priority_id">
                            <option value=''>Select Priority</option>
                            @if(!empty($complaintPriorities) )
                                @foreach($complaintPriorities as $complaintPriority)
                                    @if($complaintPriorityId == Arr::get($complaintPriority, 'id'))
                                        <option value="{{ trim(Arr::get($complaintPriority, 'id')) }}" selected>
                                            {{trim(Arr::get($complaintPriority, 'name'))}}</option>
                                    @else
                                        <option value="{{trim(Arr::get($complaintPriority, 'id'))}}">
                                            {{trim(Arr::get($complaintPriority, 'name'))}}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>


                    <div class="col-xxl-3 col-xl-3 col-lg-12 col-md-12 mb-2">
                        <select class="form-select p-2" id="reported_from_id" name="reported_from_id">
                            <option value=''>Select Reported From</option>
                            @if(!empty($reportedFrom) )
                                @foreach($reportedFrom as $key=>$value)
                                    @if($reportedFromId == $key)
                                        <option value="{{ trim($key) }}" selected>{{trim($value)}}</option>
                                    @else
                                        <option value="{{trim($key)}}">{{trim($value)}}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-lg-12 text-end mt-4">
                        <button type="submit"
                            class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                            id="consult">
                            <span>Search</span>
                            <i alt="Search" class="fa fa-search"></i>
                        </button>
                        <a href="{{ route('complaints.index') }}"
                            class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                            <span>Clear</span>
                            <i class="fa fa-solid fa-arrows-rotate"></i></a>
                    </div>
                </div>
            </form>
        </div>



        <div class="d-flex my-2">
            Showing results {{ ($complaints->currentPage() - 1) * config('constants.per_page') + 1 }} to
            {{ min($complaints->currentPage() * config('constants.per_page'), $complaints->total()) }} of
            {{ $complaints->total() }}
        </div>

        <div class="table-scroll-hr">
            <table class="table table-bordered table-striped table-compact  table-sm" id="clickableTable">
                <thead>


                    <tr>
                        <th>Complaint #</th>
                        <th>Reported From</th>
                        <th>Order Id</th>
                        <th>Assigned</th>
                        <th>Priority</th>
                        <th>Mobile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th width="20%" colspan="6">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $complaintTitle = ''; ?>
                    @foreach ($complaints as $key => $complaint)
                    <tr data-url="{{ route('complaints.show', $complaint->id) }}">
                        <td>{{Arr::get($complaint, 'complaint_number')}}</td>
                        <td>{{config('constants.complaint_reported_from.'.Arr::get($complaint, 'reported_from'))}}</td>
                        <td>{{Arr::get($complaint, 'order_id')}}</td>
                        <td>@if(!empty(Arr::get($complaint->user, 'name'))){{ Arr::get($complaint->user, 'name') }} @else <span class="text-danger">Unassigned</span> @endif</td>
                        <td>{{Arr::get($complaint->complaintPriority,'name')}}</td>
                        <td>{{Arr::get($complaint, 'mobile_number')}}</td>
                        <td>{{Arr::get($complaint, 'name')}}</td>
                        <td>{{Arr::get($complaint, 'email')}}</td>
                        <td>{{Arr::get($complaint->complaintStatus,'name')}}</td>
                        <td>{{ date("d,M,Y h:i A", strtotime(Arr::get($complaint, 'created_at'))) }}</td>
                        @if(Auth::user()->can('complaints.show'))
                        <td>
                            <a class="btn bg-theme-yellow btn-sm" href="{{ route('complaints.show', $complaint->id) }}"><i class="fa fa-eye"></i></a>
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

    document.getElementById('clickableTable').addEventListener('click', function(event) {
        const row = event.target.closest('tr'); // Get the clicked <tr>
        if (row && row.dataset.url) {
            window.location.href = row.dataset.url;
        }
        });

    </script>
<!--Select 2 -->
<link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
<script src="{!! url('assets/js/select2.min.js') !!}"></script>
@endsection