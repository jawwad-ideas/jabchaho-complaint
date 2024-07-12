<div id="modalDiv">
    <div class="modal" tabindex="-1" role="dialog" id="reAssignToModal">
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
                    <form id="reAssignComplaintForm" method="POST" action="{{ route('re-assign.complaint') }}">
                        @csrf
                        <input type="hidden" name="complaintId" id="complaintId" value="{{Arr::get($complaintData,'id')}}">
                        <p>Select who you want to assign this complaint for resolution.</p>
                        <div class="alert alert-danger" id="error" style="display:none"></div>
                        <div class="alert alert-success" id="success" style="display:none"></div>
                        <div class="row">
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="mnaId">Select MNA:</label>
                                <select class="form-control c-select select2 mna" name="mnaId">
                                    <option value="">Select...</option>
                                    @if(!empty($mnaList))
                                        @foreach($mnaList as $mna)
                                            @if(Arr::get($mna, 'id') == Arr::get($complaintData,'user_id') )
                                                <option value="{{ Arr::get($mna, 'id') }}" selected>{{ Arr::get($mna, 'name') }}</option>
                                            @else
                                            <option value="{{ Arr::get($mna, 'id') }}">{{ Arr::get($mna, 'name') }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <div id="MnaErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-4" for="mpaId">Select MPA:</label>
                                <select class="form-control c-select select2 mpa" name="mpaId">
                                    <option value="">Select...</option>
                                    @if(!empty($mpaList))
                                        @foreach($mpaList as $mpa)
                                            @if(Arr::get($mpa, 'id') == Arr::get($complaintData,'mpa_id') )
                                                <option value="{{ Arr::get($mpa, 'id') }}" selected>{{ Arr::get($mpa, 'name') }}</option>
                                            @else
                                            <option value="{{ Arr::get($mpa, 'id') }}">{{ Arr::get($mpa, 'name') }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                                <div id="MpaErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success" id="reAssignBtn">Assign</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function() {
    // Change event handler for the MNA dropdown
    $(document).on('change', '.mna', function() {
        let mnaId = $(this).val(); // Get the selected MNA ID
        
        if (!mnaId) {
            // If "Select..." option is chosen, reset MPA dropdown
            $('.mpa').html('<option value="">Select...</option>');
            return; // Exit function early
        }
        // Make AJAX request to fetch provincial_assembly_id and national_assembly_id
        $.ajax({
            url: "{{ route('get.mna.details') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                mnaId: mnaId
            },
            success: function(response) {
                let provincialAssemblyId = response[0].provincial_assembly_id;
                let nationalAssemblyId = response[0].national_assembly_id;

                // Update the MPA dropdown based on provincialAssemblyId and nationalAssemblyId
                fetchMPAs(mnaId, provincialAssemblyId, nationalAssemblyId);
            },
            error: function(xhr, status, error) {
                console.error('Error fetching MNA details:', error);
                // Handle error scenario
            }
        });
    });

    // Function to fetch MPAs based on provincialAssemblyId and nationalAssemblyId
    function fetchMPAs(mnaId, provincialAssemblyId, nationalAssemblyId) {

        $(".loader").addClass("show");
        // Make AJAX request to fetch MPA data
        $.ajax({
            url: "{{ route('get.mna.wise.mpa') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                mnaId: mnaId,
                provincialAssemblyId: provincialAssemblyId,
                nationalAssemblyId: nationalAssemblyId
            },
            success: function(response) {
                // Handle success response
                $(".loader").removeClass("show");
                // Example: Update your HTML based on the retrieved data
                if (response.mpaList && response.mpaList.length > 0) {
                    let options = '<option value="">Select...</option>';
                    $.each(response.mpaList, function(index, mpa) {
                        options += '<option value="' + mpa.user_id + '">' + mpa.user_name + '</option>';
                    });
                    $('.mpa').html(options); // Assuming .mpa is the class of your MPA select element
                } else {
                    $('.mpa').html('<option value="">No MPAs found</option>');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching MPA data:', error);
                // Handle error scenario
            }
        });
    }
});


</script>
