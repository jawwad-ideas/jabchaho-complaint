@extends('backend.layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Update Complainant</h1>
        <div class="lead">
            
        </div>
        
        <div class="container mt-4">
            <form method="post" action="{{ route('complainants.update', $complainant->id) }}" autocomplete="off">
                @method('patch')
                @csrf
                <input type="hidden" name="complainant_id" id="complainant_id" value="{{ Arr::get($complainant, 'id') }}" />
                <div class="mb-3">
                    <label for="full_name" class="form-label">Name<span style="color: red"> * </span></label>
                    <input value="@if(old('full_name')){{old('full_name')}}@elseif(empty(old('full_name')) && old('_token')) {{''}}@else{{Arr::get($complainant,'full_name')}}@endif" 
                        type="text" class="form-control" name="full_name" placeholder="Name" >
                        @if($errors->has('full_name'))
                            <div class="text-danger">{{ $errors->first('full_name') }}</div>
                        @endif
                </div>
                <div class="mb-3">
                    <label for="mobile_number" class="form-label">Mobile Number<span style="color: red"> * </span></label>
                    <input value="@if(old('mobile_number')){{old('mobile_number')}}@elseif(empty(old('mobile_number')) && old('_token')) {{''}}@else{{Arr::get($complainant,'mobile_number')}}@endif" 
                        type="text" class="form-control" name="mobile_number" placeholder="Mobile Number" maxlength="11" minlength="11" onkeydown="return isNumberKey(event);">
                        @if($errors->has('mobile_number'))
                            <div class="text-danger">{{ $errors->first('mobile_number') }}</div>
                        @endif
                </div>
                <div class="mb-3">
                    <label for="gender" class="col-sm-3 col-form-label col-form-label-sm">Gender<span style="color: red"> * </span></label>
                    <div class="col-sm-9 col-form-label-sm text-left">
                        @foreach(config('constants.gender_options') as $key => $value)
                            <div class="form-check form-check-inline">
                                <label class="form-check-label" for="gender-{{$key}}">{{$value}}</label>
                                <input class="form-check-input" type="radio" name="gender" id="gender-{{$key}}" value="{{$key}}"
                                @if((old('gender')!==null && (old('gender') == $key)) || (empty(old('gender')) && (isset($complainant->gender) && $complainant->gender === $key)))
                                    {{'checked'}}
                                @endif>
                            </div>
                        @endforeach
                        @if($errors->has('gender'))
                            <div class="text-danger">{{ $errors->first('gender') }}</div>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email<span style="color: red"> * </span></label>
                    <input value="@if(old('email')){{old('email')}}@elseif(empty(old('email')) && old('_token')) {{''}}@else{{Arr::get($complainant,'email')}}@endif"
                        type="email" 
                        class="form-control" 
                        name="email" 
                        placeholder="Email address" >
                        @if($errors->has('email'))
                            <div class="text-danger">{{ $errors->first('email') }}</div>
                        @endif
                   
                </div>
                <div class="mb-3">
                    <label for="cnic" class="form-label">cnic<span style="color: red"> * </span></label>
                    <input value="@if(old('cnic')){{old('cnic')}}@elseif(empty(old('cnic')) && old('_token')) {{''}}@else{{Arr::get($complainant,'cnic')}}@endif" 
                        onpaste="return false;" onkeydown="return isNumberKey(event);" oninput="formatCNIC(this);"
                        maxlength="15"
                        type="text" 
                        class="form-control" 
                        name="cnic" 
                        placeholder="CNIC" > 
                        @if($errors->has('cnic'))
                            <div class="text-danger">{{ $errors->first('cnic') }}</div>
                        @endif 
                </div>

                <button type="submit" class="btn btn-primary">Update Complainant</button>
                <a href="{{ route('complainants.index') }}" class="btn btn-default">Cancel</button>
            </form>
        </div>
    </div>

@endsection
