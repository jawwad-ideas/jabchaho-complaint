@extends('frontend.layouts.app-master')
@section('title', 'Complaints')
@section('content')

<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">My Complaints</h3>
    </div>
    <div class="p-actions text-center my-3">
        <a class="btn bg-white mb-3 d-lg-flex align-items-center d-inline-flex gap-3"
            href="{{ route('complaints.create') }}">
            <i class="fa fa-plus"></i> <small>New Complaint </small> </a>
    </div>
</div>


<div class="page-content bg-white p-lg-5 px-2">
    <div class="table-section">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>City</th>
                    <th>Area</th>
                    <th>Status</th>
                    <th scope="col" width="1%" colspan="3"></th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($complaints))
                @foreach($complaints as $complaint)
                <tr>
                    <td>{{Arr::get($complaint,'complaint_num')}}</td>
                    <td>{{Helper::addDotAfterWords(10,Arr::get($complaint,'title'))}}</td>
                    <td>{{Arr::get($complaint->city,'name')}}</td>
                    <td>{{Arr::get($complaint->newArea,'name')}}</td>
                    <td>{{Arr::get($complaint->complaintStatus,'name')}}</td>
                    <td><a href="{{ route('my.complaints.show', $complaint->id) }}" class="btn btn-warning btn-sm">Show</a></td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="d-flex">
        {!! $complaints->appends(Request::except('page'))->render() !!}
    </div>
</diV>

@endsection