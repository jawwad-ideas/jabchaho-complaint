@extends('backend.layouts.app-master')
@section('title', 'Complaints')
@section('content')
 

<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
    <div class="p-title">
        <h3 class="fw-bold text-dark m-0">Add New Complaint</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger" id="error" style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>

    <form method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row mb-3">
      <div class="col-md-6">
        <label for="orderNo" class="form-label">Order # <span class="red"> *</span></label>
        <input type="text" class="form-control" id="orderNo" name="order_id" value="{{ old('order_id') }}" required placeholder="Order No" oninput="validateOnlyNumber(this)">
      </div>
      <div class="col-md-6">
        <label for="name" class="form-label">Name <span class="red"> *</span></label>
        <input type="text" class="form-control" id="name" placeholder="Name" name="name" value="{{ old('name') }}" required>
      </div>
    </div>

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="email" class="form-label">Email <span class="red"> *</span></label>
        <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ old('email') }}" required>
      </div>
      <div class="col-md-6">
        <label for="mobile" class="form-label">Mobile No <span class="red"> *</span></label>
        <input type="text" class="form-control" id="mobile" placeholder="+9233xxxxxxxx"  maxlength="13"  name="mobile_number" value="{{ old('mobile_number') }}" required>
      </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <label class="form-label">
                Reported From
            </label>

              <select class="form-select form-select-sm" id="reported_from_id" name="reported_from" >
                @if(!empty($reportedFrom) )
                    @foreach($reportedFrom as $key=>$value)
                        @if(old('reported_from') == $key)
                            <option value="{{ trim($key) }}" selected>{{trim($value)}}</option>
                        @elseif(config('constants.complaint_reported_from_id.complaint_portal') == $key && !old('reported_from'))
                            <option value="{{ trim($key) }}" selected>{{trim($value)}}</option>    
                        @else
                            <option value="{{trim($key)}}">{{trim($value)}}</option>
                        @endif
                    @endforeach
                @endif
            </select>
       
            
        </div>
        <div class="col-md-6">
            <label for="complaintPhase" class="form-label">Complaint Phase<span class="red"> *</span></label>
            <select id="complaint_phase" class="form-select form-select-sm" name="complaint_phase" required>
                <option value="">Select Complaint Phase</option>
                @if(!empty($complaintPhases) )
                    @foreach($complaintPhases as $key =>$value)
                        @if(old('_token') && old('complaint_phase') == $key)
                            <option value="{{ trim($key) }}" selected>{{trim($value)}}</option>
                        @else
                            <option value="{{trim($key)}}">{{trim($value)}}</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
    </div>   

    <div class="row mb-3">
      <div class="col-md-6">
        <label for="complaintType" class="form-label">Complaint Nature <span class="red"> *</span></label>
        <select id="complain_type" class="form-select form-select-sm"
            name="complaint_type" required>
            <option value="">Select Complaint Nature</option>
            @if(!empty($complaintTypes) )
                @foreach($complaintTypes as $key =>$value)
                    @if(old('_token') && old('complaint_type') == $key)
                        <option value="{{ trim($key) }}" selected>{{trim($value)}}</option>
                    @else
                        <option value="{{trim($key)}}">{{trim($value)}}</option>
                    @endif
                @endforeach
            @endif
        </select>
      </div>
      <div class="col-md-6">
        <label for="service" class="form-label">Services <span class="red"> *</span></label>
        <select id="service_id" class="form-select form-select-sm" name="service_id" required>
            <option value="" >Select Service</option>
            @if(!empty($services) )
                @foreach($services as $service)
                    @if(old('_token') && old('service_id') == Arr::get($service, 'id'))
                        <option value="{{ trim(Arr::get($service, 'id')) }}" selected>{{trim(Arr::get($service, 'name'))}}</option>
                    @else
                        <option value="{{trim(Arr::get($service, 'id'))}}">{{trim(Arr::get($service, 'name'))}}</option>
                    @endif
                @endforeach
            @endif
        </select>
      </div>
    </div>



    <div class="row mb-3">
        <div class="col-md-6">
            <label class="control-label col-sm-4" for="userId">Assign To <span class="red"> *</span></label>
            <select class="form-control c-select" name="user_id" required>
                <option value="">Select...</option>
                @if(!empty($users))
                    @foreach($users as $user)
                        @if(old('_token') && old('user_id') ==Arr::get($user, 'id'))
                            <option value="{{ Arr::get($user, 'id') }}" selected>{{ Arr::get($user, 'name') }}  ({{ Arr::get($user, 'email') }})</option>
                        @else
                        <option value="{{ Arr::get($user, 'id') }}">{{ Arr::get($user, 'name') }}  ({{ Arr::get($user, 'email') }})</option>
                        @endif
                        
                    @endforeach
                @endif
            </select>
        </div>
        <div class="col-md-6">
            <label class="control-label col-sm-4" for="priorityId">Priority <span class="red"> *</span></label>
            <select class="form-control c-select" name="complaint_priority_id" required>
                <option value="">Select...</option>
                @if(!empty($complaintPriorities))
                    @foreach($complaintPriorities as $complaintPriority)
                        @if(old('_token') && old('complaint_priority_id') == Arr::get($complaintPriority, 'id'))    
                            <option value="{{ Arr::get($complaintPriority, 'id') }}" selected >{{ Arr::get($complaintPriority, 'name') }} @if(!empty(Arr::get($complaintPriority, 'days'))) ({{ Arr::get($complaintPriority, 'days') }} Days)   @endif</option>
                        @else
                        <option value="{{ Arr::get($complaintPriority, 'id') }}" >{{ Arr::get($complaintPriority, 'name') }} @if(!empty(Arr::get($complaintPriority, 'days'))) ({{ Arr::get($complaintPriority, 'days') }} Days)  @endif</option>
                        @endif
                    @endforeach
                @endif
            </select>
        </div>
    </div>





    <div class="mb-3">
      <label for="attachments" class="form-label">Attachments <span class="red"> *</span></label>
      <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
      
    </div>

    <div class="mb-4">
      <label for="comments" class="form-label">Comments <span class="red"> *</span></label>
      <textarea class="form-control" id="comments" rows="5" name="comments" required>{{ old('comments') }}</textarea>
    </div>

     <div class="mb-3">
      <button type="submit" class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3">Submit Complaint</button>
      <a href="{{ route('complaints.index') }}" class="btn bg-theme-dark-300 text-light">Back</a>
    </div>
  </form>
</div>

<script>
$(document).ready(function () {
    $('#orderNo').on('input', function () {
        let orderId = $(this).val();

        if (orderId.length >= 6) {
          $('#name, #email, #mobile').val('');

          $(".loader").show();

           var url = '{{ route('fetch.order.detail', ':orderId') }}';
            url = url.replace(':orderId', orderId);
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response.success) 
                    {
                        $('#name').val(response.order.customer_name || '');
                        $('#email').val(response.order.customer_email || '');
                        $('#mobile').val(response.order.telephone || '');
                    } else {
                        console.warn('Order not found.');
                        $('#name, #email, #mobile').val('');
                    }

                    $(".loader").hide();
                },
                error: function () {
                    console.error('Something went wrong with the request.');
                    $(".loader").hide();
                }
            });
        }
    });
});
</script>
@endsection