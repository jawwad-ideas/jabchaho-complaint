@extends('backend.layouts.app-master')

@section('content')
<div class="bg-light p-4 rounded">
    <h2>Update Candidates</h2>
    <div class="lead">
        Edit Candidates.
    </div>

    <div class="container mt-4">

        <form method="POST" action="{{ route('candidates.update', $candidate->id) }}">
            @method('patch')
            @csrf
            <div class="mb-3">
                <label for="full_name" class="form-label">Full Name</label>
                <input
                    value="@if(old('full_name')){{old('full_name')}}@elseif(empty(old('full_name')) && old('_token')) {{''}}@else{{Arr::get($candidate,'full_name')}}@endif"
                    type="text" class="form-control" name="full_name" placeholder="Full Name" readonly maxlength="50"
                    onpaste="return false;" onkeydown="return isAlphabatKey(this);">

            </div>

            <div class="mb-3">
                <label for="Email" class="form-label">Email Address</label>
                <input type="text" readonly autocomplete="off" class="form-control" id="email" placeholder="Enter email"
                    name="email" maxlength="50"
                    value="@if(old('email')){{old('email')}}@elseif(empty(old('email')) && old('_token')) {{''}}@else{{Arr::get($candidate,'email')}}@endif">
            </div>

            <div class="mb-3">
                <label for="Country" class="form-label">Country</label>
                <select class="form-control c-select" id="country" disabled name="country_code"
                    rel="{{Arr::get($candidate, 'country_code')}}">
                    @if(!empty($countries) )
                    @foreach($countries as $key =>$value)
                    @if(old('country_code') == $key)
                    <option value="{{ trim($key) }}" selected>{{trim($value)}} </option>
                    @elseif($key==(Arr::get($candidate, 'country_code')) && empty(old('country_code')) )
                    <option value="{{ trim($key) }}" selected>{{trim($value)}} </option>
                    @else
                    <option value="{{trim($key)}}"> {{trim($value)}} </option>
                    @endif
                    @endforeach
                    @endif
                </select>

            </div>

            <div class="mb-3">
                <label for="Mobile" class="form-label">Mobile Number</label>
                <input type="text" autocomplete="off" readonly class="form-control" id="mobile_number"
                    placeholder="Mobile Number" name="mobile_number" maxlength="18"
                    value="@if(old('mobile_number')){{old('mobile_number')}}@elseif(empty(old('mobile_number')) && old('_token')) {{''}}@else{{Arr::get($candidate,'mobile_number')}}@endif">
            </div>

            <div class="mb-3">
                <label for="Mobile" class="form-label">Area of Interest</label>
                <select class="form-control c-select" id="area_of_interest_option_id" disabled
                    name="area_of_interest_option_id" rel="{{old('area_of_interest_option_id')}}">
                    <option value=''>Select Your Area Of Interest</option>
                    @if(!empty($areaOfInterests) )

                    @php $groupName = ''; @endphp
                    @foreach($areaOfInterests as $object)

                    @if ($groupName != $object['group_name'])
                    <optgroup label="{{$object['group_name']}}">
                        @php $groupName = $object['group_name']; @endphp
                        @endif

                        @if (old('area_of_interest_option_id') == $object['id'])
                        <option value="{{ trim($object['id']) }}" selected>{{trim($object['name'])}} </option>
                        @elseif($object['id']==(Arr::get($candidate, 'area_of_interest_option_id')) &&
                        empty(old('area_of_interest_option_id')) )
                        <option value="{{ trim($object['id']) }}" selected>{{trim($object['name'])}} </option>
                        @else
                        <option value="{{ trim($object['id']) }}"> {{trim($object['name'])}} </option>
                        @endif
                        @endforeach
                        @endif
                </select>
            </div>

            <div class="mb-3">
                <label for="Mobile" class="form-label">Password</label>
                <div class="row">
                    <div class="col-sm-2">
                        <input type="password" autocomplete="off" class="form-control" id="password"
                            placeholder="Enter Password" name="password" maxlength="20">
                    </div>
                    <div class="col-sm-3">
                        <input type="password" autocomplete="off" class="form-control" id="confirm_password"
                            placeholder="Enter Confirm Password" name="confirm_password" maxlength="20">
                    </div>
                </div>


                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('candidates.index') }}" class="btn btn-default">Back</a>
        </form>
    </div>

</div>
@endsection