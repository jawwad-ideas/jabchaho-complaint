@extends('backend.layouts.app-master')

@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">Track your Complaints here.</h3>
    </div>
    <div class="text-end">
        <div class="btn-group" role="group">
            <small id="showFilterBox" type="button"
                class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                    class="fa fa-solid fa-filter"></i> <span>Filter</span></small>

        </div>
    </div>

</div>
<div class="bg-light p-4 rounded">
    <div class="page-content bg-white p-lg-5 px-2">

        <div class="" id="filterBox" style="display:none;">
            <form class="form-inline" method="GET" action="{{ route('complaints.track') }}">
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" autocomplete="off"
                            name="complaint_number" value="{{ $filterData['complaint_number'] ?? '' }}"
                            placeholder="Complaint No.">
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="mobile_number"
                            value="{{ $filterData['mobile_number'] ?? '' }}" maxlength="11" placeholder="Mobile No.">
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="cnic"
                            value="{{ $filterData['cnic'] ?? '' }}" maxlength="15" oninput="formatCNIC(this);"
                            placeholder="CNIC">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-12 text-end">
                        <button type="submit"
                            class="btn bg-theme-green text-white p-2 d-inline-flex align-items-center gap-1">Track</button>
                        <a href="{{ route('complaints.track') }}"
                            class="btn bg-theme-dark text-white p-2 d-inline-flex align-items-center gap-1 text-decoration-none">Reset</a>
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
            <table class="table table-bordered">
                <tr>
                    <th>No</th>
                    <th>Complaint Title</th>
                    <th>Name</th>
                    <th>CNIC</th>
                    <th>Mobile Number</th>
                    <th>Assigned MNA</th>
                    <th>Assigned MPA</th>
                    <th>Status</th>
                </tr>
                @foreach ($complaints as $key => $complaint)
                <tr>
                    <td>{{Arr::get($complaint,'complaint_num')}}</td>
                    <td>{{Helper::addDotAfterWords(10,Arr::get($complaint,'title'))}}</td>
                    <td>{{Arr::get($complaint->complainant,'full_name')}}</td>
                    <td>{{Arr::get($complaint->complainant,'cnic')}}</td>
                    <td>{{Arr::get($complaint->complainant,'mobile_number')}}</td>
                    <td>{{Arr::get($complaint->user,'name')}}</td>
                    <td>{{Arr::get($complaint->userMpa,'name')}}</td>
                    <td>{{Arr::get($complaint->complaintStatus,'name')}}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="d-flex">
            {!! $complaints->appends(Request::except('page'))->render() !!}
        </div>
    </div>
</div>
<script>
$("#showFilterBox").click(function() {
    $("#filterBox").toggle();
});
</script>
@endsection
