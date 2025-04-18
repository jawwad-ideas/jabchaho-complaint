@extends('backend.layouts.app-master')

@section('content')

<!--Summer note -->
<link href="{!! url('assets/css/summernote/summernote-bs4.min.css') !!}" rel="stylesheet">
<script src="{!! url('assets/js/summernote/summernote-bs4.min.js') !!}"></script>
<!--Summer note -->


<div class="card">
    <div class="card-header">
        <span><strong>Follow up #{{ Arr::get($complaint,'complaint_number') }}</strong></span>
        <span class="float-right"><strong>{{ Arr::get($complaint->complaintStatus,'name') }}</strong></span>
    </div>
    <div class="card-body">

        @if(!empty($complaint))


        <!--Row 1-->
        <div class="row">
            <div class="col-md-12">
                <!--card 0-->
                <div class="card mb-3">
                    <h6 class="card-header">Additional Comments</h6>
                    <div class="card-body">
                        <div class="row">
                            {!! Arr::get($complaint,'comments') !!}
                        </div>
                    </div>

                    <div class="card-footer text-muted">
                        <strong>{{ ucwords(Arr::get($complaint->complainant,'full_name'))}}</strong> <span
                            class="float-right"><small>@if(!empty(Arr::get($complaint,
                                'created_at'))){{ date(config('constants.date_time_format'), strtotime(Arr::get($complaint, 'created_at'))) }}@endif
                            </small></span>
                    </div>
                </div>
                <!--//card 0-->
            </div>
        </div>
        <!--Row 1-->

        @endif

        @if(Auth::user()->can('complaints.follow.up.saved'))
        <div class="col mt-4">
            <form class="py-2 px-4" action="{{ route('complaints.follow.up.saved', $complaint->id) }}"
                style="box-shadow: 0 0 10px 0 #ddd;" method="POST" autocomplete="off">
                @csrf
                <input type="hidden" name="complaint_id" value="{{ Arr::get($complaint,'id')}}">

                <div class="row mb-3">
                    <label for="job_status_id" class="form-label">Status</label>

                    <select class="form-control form-control-sm" id="complaint_status_id" name="complaint_status_id">
                        <option value=''>Select Status</option>
                        @if(!empty($complaintStatuses) )
                        @foreach($complaintStatuses as $complaintStatus)
                        @if(old('complaint_status_id') == Arr::get($complaintStatus, 'id'))
                        <option value="{{ trim(Arr::get($complaintStatus, 'id')) }}" selected>
                            {{trim(Arr::get($complaintStatus, 'name'))}}</option>
                        @elseif(old('_token') == null && !empty(Arr::get($complaint, 'complaint_status_id')) &&
                        (Arr::get($complaint, 'complaint_status_id')== Arr::get($complaintStatus, 'id')) )
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

                <div class="form-group row mt-4">
                    <label for="job_status_id" class="form-label">Description</label>
                    <textarea class="form-control" name="description" rows="3"
                        cols="50">@if(old('description')){{old('description')}}@elseif(empty(old('description')) && old('_token')) {{''}} @endif</textarea>
                    
                </div>

                <div class="mt-3 text-end">
                    <button class="btn btn-sm py-2 px-3 btn-info">Submit</button>

                </div>
            </form>
        </div>

        @endif


    </div>
    <!--//card body-->
</div>
<!--//card-->


@if(!empty($complaintFollowUps))

@foreach($complaintFollowUps as $complaintFollowUp)
<div class="container">
    <div class="row">
        <div class="col mt-4">
            <div class="py-2 px-4" style="box-shadow: 0 0 10px 0 #ddd;">
                <div class="row">
                    <div class="col-sm-12"><strong> Created By:
                            {{ ucwords(Arr::get($complaintFollowUp->user,'name'))}}</strong> <small>|
                            @if(!empty(Arr::get($complaintFollowUp,
                            'created_at'))){{ date(config('constants.date_time_format'), strtotime(Arr::get($complaintFollowUp, 'created_at'))) }}@endif
                            | {{ Arr::get($complaintFollowUp->complaintStatus,'name') }} </small></div>
                </div>
                <p class="font-weight-bold ">{!! Arr::get($complaintFollowUp,'description') !!}</p>
                @if(Auth::user()->hasRole('admin') && Auth::user()->can('follow.up.Destroy'))

                {!! Form::open(['method' => 'DELETE','route' => ['follow.up.Destroy',
                $complaintFollowUp->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                {!! Form::submit('Remove', ['class' => 'btn btn-danger btn-sm']) !!}
                {!! Form::close() !!}

                @endIf
            </div>
        </div>
    </div>
</div>
@endforeach
@endif





<script>
$(document).ready(function(e) {
    $('.summernote').summernote();
});
</script>
@endsection