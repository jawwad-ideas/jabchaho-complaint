@extends('backend.layouts.app-master')

@section('content')
    
    <div class="bg-light p-4 rounded">
        <h2>Area Grid</h2>
        <div class="lead  mb-3">
            Manage Area Grids here.
            {{-- @if(Auth::user()->can('complaints.create.form'))
                <a href="{{ route('complaints.create.form') }}" class="btn btn-primary btn-sm float-right">Add Complaint</a>
            @endif               --}}
        </div>

        {{-- <form class="form-inline" method="GET" action="{{ route('complaints.index') }}">
            <div class="row mb-3" >
                <div class="col-sm-2">
                    <input type="text" class="form-control form-control-sm" autocomplete="off" name="complaint_number" value="{{ $filterData['complaint_number'] ?? '' }}" placeholder="Complaint No.">
                </div>
                <div class="col-sm-1">
                    <input type="text" class="form-control form-control-sm" autocomplete="off" name="title" value="{{ $filterData['title'] ?? '' }}" placeholder="Title">
                </div>
                <div class="col-sm-1">
                    <select class="form-control form-control-sm c-select" name="complaint_status_id">
                        <option value="">Status</option>
                        @foreach($complaintStatusIds as $complaintStatusId)
                            <option value="{{ Arr::get($complaintStatusId,'id') }}" {{ $filterData['complaint_status_id'] == Arr::get($complaintStatusId,'id') ? 'selected' : '' }}>{{ Arr::get($complaintStatusId,'name') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control form-control-sm c-select" name="level_one">
                        <option value="">Level One</option>
                        @foreach($levelOneCategory as $levelOne)
                            <option value="{{ Arr::get($levelOne,'id') }}" {{ $filterData['level_one'] == Arr::get($levelOne,'id') ? 'selected' : '' }}>{{ Arr::get($levelOne,'name') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control form-control-sm c-select" name="level_two">
                        <option value="">Level Two</option>
                        @foreach($levelTwoCategory as $levelTwo)
                            <option value="{{ Arr::get($levelTwo,'id') }}" {{ $filterData['level_two'] == Arr::get($levelTwo,'id') ? 'selected' : '' }}>{{ Arr::get($levelTwo,'name') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-2">
                    <select class="form-control form-control-sm c-select" name="level_three">
                        <option value="">Level Three</option>
                        @foreach($levelThreeCategory as $levelThree)
                            <option value="{{ Arr::get($levelThree,'id') }}" {{ $filterData['level_three'] == Arr::get($levelThree,'id') ? 'selected' : '' }}>{{ Arr::get($levelThree,'name') }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-1">
                    <select class="form-control form-control-sm" id="city_id" name="city_id">
                        <option value="">City</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ $filterData['city'] == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-1">
                    <select class="form-control form-control-sm" id="district_id" name="district_id">
                        <option value="">District</option>
                        @foreach($districts as $district)
                            <option value="{{ $district->id }}" {{ $filterData['district'] == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-11">
                    <button type="submit" class="btn btn-primary btn-sm float-right btn-fit">Search</button>
                </div>
                <div class="col-sm-1">
                    <a href="{{ route('complaints.index') }}" class="btn btn-primary btn-sm float-left btn-fit">Reset</a>
                </div>
             </div>
        </form> --}}
        <div class="table-scroll-hr">
        <table class="table table-bordered">
            <tr>
            <th>Name</th>
            <th>Charge</th>
            <th>District</th>
            <th>Sub Division</th>
            <th>UC</th>
            <th>Ward</th>
            <th>NA</th>
            <th>PA</th>
            <th>Created By</th>
            <th>Updated By</th>
            <th width="20%" colspan="2">Action</th>
            </tr>
            @foreach ($newAreaGrid as $key => $newArea)
            <tr>
                <td>{{Arr::get($newArea->newArea,'name')}}</td>
                <td>{{Arr::get($newArea->charge,'name')}}</td>
                <td>{{Arr::get($newArea->district,'name')}}</td>
                <td>{{Arr::get($newArea->subDivision,'name')}}</td>
                <td>{{Arr::get($newArea->unionCouncil,'name')}}</td>
                <td>{{Arr::get($newArea->ward,'name')}}</td>
                <td>{{Arr::get($newArea->nationalAssembly,'name')}}</td>
                <td>{{Arr::get($newArea->provincialAssembly,'name')}}</td>
                <td>{{Arr::get($newArea->createdBy,'name')}}</td>
                <td>{{Arr::get($newArea->updatedBy,'name')}}</td>
                
                {{-- <td>{{date("l, F j, Y",strtotime(Arr::get($complaint,'created_at')))}}</td>
                <td> 
                        @if(Arr::get($complaint->complaintPriority,'id')) {{ date("l, F j, Y",strtotime(Arr::get($complaint,'created_at').'+ '.Arr::get($complaint->complaintPriority,'days').' days'  )) }} @endif

                </td> --}}
                {{-- @if(Auth::user()->can('complaints.show'))
                    <td>
                        <a class="btn btn-info btn-sm" href="{{ route('complaints.show', $complaint->id) }}">Show</a>
                    </td>
                @endif
                @if(Auth::user()->can('complaints.follow.up'))
                    <td>
                        <a class="btn btn-success btn-sm" href="{{ route('complaints.follow.up', $complaint->id) }}">Follow Up</a>
                    </td>
                @endif
                @if(Auth::user()->can('assign.complaint'))
                    <td>
                        <a class="btn btn-primary btn-sm assign-to-btn" data-complaint-id="{{ $complaint->id }}">Assign To</a>
                    </td>
                @endif --}}
                @if(Auth::user()->can('area.grid.destroy'))
                    <td>
                        {!! Form::open(['method' => 'DELETE','route' => ['area.grid.destroy', $newArea->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                @endif
            </tr>
            @endforeach
        </table>
        </div>

        <div class="d-flex">
            {!! $newAreaGrid->appends(Request::except('page'))->render() !!}
        </div>

    </div>
@endsection
