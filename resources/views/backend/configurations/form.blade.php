@extends('backend.layouts.app-master')

@section('content')

    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Configurations</h3>
        </div>

    </div>


    <div class="page-content bg-white p-lg-5 px-2">

        <div class="alert alert-danger" id="error" style="display:none"></div>
        <div class="alert alert-success" id="success" style="display:none"></div>
        <div class="container mt-4">
            <form method="POST" action="{{ route('configurations.save') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12">

                        <div class="form-section mb-5">
                            <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                                <h4 class="fw-bold mt-0">Save Configurations</h4>
                            </div>
                        </div>
                        <div class="container mt-4">
                            
                            <div class="mb-3">
                                <label for="inputName">Complaint SMS Api Enable:</label>
                                <select id="complaint_sms_api_enable" class="form-control" name="complaint_sms_api_enable">
                                    @if(!empty($enableDisableSmsApi) )
                                        @foreach($enableDisableSmsApi as $key =>$value)
                                            @if(old('_token') && old('complaint_sms_api_enable') === $key)
                                                <option value="{{ trim($key) }}" selected>{{trim($value)}}</option> 
                                            @elseif( old('_token') === null && Arr::get($configurations, 'complaint_sms_api_enable')==$key && array_key_exists(Arr::get($configurations, 'complaint_sms_api_enable'), config('constants.complaint_sms_api_enable')) )
                                                <option value="{{ trim($key) }}" selected>{{trim($value)}}</option> 
                                            @else
                                                <option  value="{{trim($key)}}" >{{trim($value)}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="complaint_sms_api_url" class="form-label">Complaint SMS API Url:</label>
                                <input type = 'text'  class="form-control" id="complaint_sms_api_url" name="complaint_sms_api_url" value="{{Arr::get($configurations, 'complaint_sms_api_url')}}">
                            </div>

                            <div class="mb-3">
                                <label for="complaint_sms_sender" class="form-label">Complaint SMS Sender:</label>
                                <input type = 'text' class="form-control" id="complaint_sms_sender" name="complaint_sms_sender" value="{{Arr::get($configurations, 'complaint_sms_sender')}}">
                            </div>

                            <div class="mb-3">
                                <label for="complaint_sms_username" class="form-label">Complaint SMS Username:</label>
                                <input type = 'text'  class="form-control" id="complaint_sms_username" name="complaint_sms_username" value="{{Arr::get($configurations, 'complaint_sms_username')}}">
                            </div>

                            <div class="mb-3">
                                <label for="complaint_sms_password" class="form-label">Complaint SMS Password:</label>
                                <input type = 'text'  class="form-control" id="complaint_sms_password" name="complaint_sms_password" value="{{Arr::get($configurations, 'complaint_sms_password')}}">
                            </div>

                            <div class="mb-3">
                                <label for="complaint_sms_format" class="form-label">Complaint SMS format:</label>
                                <input type = 'text'  class="form-control" id="complaint_sms_format" name="complaint_sms_format" value="{{Arr::get($configurations, 'complaint_sms_format')}}">
                            </div>

                            <div class="mb-3">
                                <label for="complaint_sms_action" class="form-label">Complaint SMS Action:</label>
                                <input type = 'text'  class="form-control" id="complaint_sms_action" name="complaint_sms_action" value="{{Arr::get($configurations, 'complaint_sms_action')}}">
                            </div>

                            <div class="mb-3">
                                <label for="complaint_sms_template" class="form-label">Complaint SMS Template:</label>
                                <textarea class="form-control" id="complaint_sms_template" name="complaint_sms_template">{{Arr::get($configurations, 'complaint_sms_template')}}</textarea>
                            
                            </div>

                            <div class="mb-3">
                                <label for="complaint_status_changed_sms_template" class="form-label">Complaint Status Changed SMS Template:</label>
                                <textarea class="form-control" id="complaint_status_changed_sms_template" name="complaint_status_changed_sms_template">{{Arr::get($configurations, 'complaint_status_changed_sms_template')}}</textarea>
                            
                            </div>

                            <div class="mb-3">
                                <label for="api_ips_whitelist" class="form-label">Api Ips Whitelist:</label>
                                <textarea class="form-control" id="api_ips_whitelist" name="api_ips_whitelist">{{Arr::get($configurations, 'api_ips_whitelist')}}</textarea>
                            
                            </div>

                            <button type="submit"
                                class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3">Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

    </div>


@endsection