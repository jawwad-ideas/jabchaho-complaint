<div class="modal" tabindex="-1" role="dialog" id="assignToModal">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="alert alert-danger" style="display:none"></div>
            <div class="modal-header">

                <h5 class="modal-title">Assign Complaint To</h5>

                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="assignComplaintForm" method="POST" action="{{ route('assign.complaint') }}">
                    @csrf
                    <input type="hidden" name="complaintId" id="complaintId" value="{{Arr::get($complaintData,'id')}}">
                    <p>Select who you want to assign this complaint for resolution.</p>
                    <div class="alert alert-danger" id="error" style="display:none"></div>
                    <div class="alert alert-success" id="success" style="display:none"></div>
                    <div class="row">
                        <div class="form-group mb-3">
                            <label class="control-label col-sm-4" for="userId">Complaint:</label>
                            {{Arr::get($complaintData,'complaint_num')}}
                        </div>
                        <div class="form-group mb-3">
                        <label class="control-label col-sm-4" for="userId">Assign To:</label>
                            <select class="form-control c-select" name="userId">
                                <option value="">Select...</option>
                                @if(!empty($users))
                                    @foreach($users as $user)
                                        @if(Arr::get($user, 'id') == Arr::get($complaintData,'user_id') )
                                            <option value="{{ Arr::get($user, 'id') }}" selected>{{ Arr::get($user, 'name') }}  ({{ Arr::get($user, 'email') }})</option>
                                        @else
                                        <option value="{{ Arr::get($user, 'id') }}">{{ Arr::get($user, 'name') }}  ({{ Arr::get($user, 'email') }})</option>
                                        @endif
                                        
                                    @endforeach
                                @endif
                            </select>
                            <div id="UserErrorMsg" class="text-danger validation-message" style="display:none" ></div>

                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4" for="priorityId">Priority:</label>
                            <select class="form-control c-select" name="priorityId">
                                <option value="">Select...</option>
                                @if(!empty($complaintPriorities))
                                    @foreach($complaintPriorities as $complaintPriority)
                                        @if(Arr::get($complaintPriority, 'id') == Arr::get($complaintData,'complaint_priority_id') )    
                                            <option value="{{ Arr::get($complaintPriority, 'id') }}" selected >{{ Arr::get($complaintPriority, 'name') }} ({{ Arr::get($complaintPriority, 'days') }} Days)</option>
                                        @else
                                        <option value="{{ Arr::get($complaintPriority, 'id') }}" >{{ Arr::get($complaintPriority, 'name') }} ({{ Arr::get($complaintPriority, 'days') }} Days)</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            <div id="PriorityErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button  class="btn btn-success" id="assignBtn">Assign</button>
            </div>
        </div>
    </div>

</div>