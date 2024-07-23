@extends('backend.layouts.app-master')
@section('title', 'Complaints')
@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Search Complains</h3>
        </div>
        <div class="text-end">
            <div class="btn-group" role="group">
                <small id="showFilterBox" type="button"
                    class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                        class="fa fa-solid fa-filter"></i> <span>Filter</span></small>

            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="container">
            <div class="" id="filterBox" style="display:none;">

                <form autocomplete="off" id="complainReport" action="{{ route('report-by-complaints') }}" method="GET">
                    @csrf


                    <div class="form-row row">
                        <div class="form-group  mb-3 col-md-6">
                            <h6 class="fw-bold" for="start_date">Start Date:</h6>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date"
                                value="{{ request('start_date') }}">
                        </div>

                        <!-- End Date Field -->
                        <div class="form-group  mb-3 col-md-6">
                            <h6 class="fw-bold" for="end_date">End Date:</h6>
                            <input type="datetime-local" class="form-control" id="end_date" name="end_date"
                                value="{{ request('end_date') }}">
                        </div>
                        
                    </div>
                    <div class="form-row row">
                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="type">NIC:</h6>
                            <input type="text" class="form-control p-2" autocomplete="off" id="cnic" name="cnic"
                                value="{{ request('cnic') ?? '' }}" placeholder="CNIC #" maxlength="15" onpaste="return false;" onkeydown="return isNumberKey(this);" oninput="formatCNIC(this);">
                            <!-- <select class="mySelect form-control" id="cnic" name="cnic"
                                onchange="getAjaxData('Complainant','cnic',event,'mobile_number','mobile_number');checkFieldValidation(this);">
                                <option value="">--Select--</option>
                                @if (!empty($nic))
                                    @foreach ($nic as $row)
                                        <option value="{{ trim(Arr::get($row, 'cnic')) }}"
                                            {{ request('cnic') == $row['cnic'] ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'cnic')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select> -->
                        </div>




                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="type">Phone Number:</h6>
                            <input type="text" class="form-control p-2" autocomplete="off" name="mobile_number"
                                value="{{ request('mobile_number') ?? '' }}" onpaste="return false;" placeholder="Mobile No." maxlength="11"
                                    onkeydown="return isNumberKey(event);">
                            <!-- <select class="mySelect form-control mobile_number" id="mobile_number" name="mobile_number">
                                <option value="">--Select--</option>
                                @if (!empty($phoneNumber))
                                    @foreach ($phoneNumber as $row)
                                        @if ($row !== null && isset($row['mobile_number']) && $row['mobile_number'] !== '')
                                            <option value="{{ $row['mobile_number'] }}"
                                                {{ request('mobile_number') == $row['mobile_number'] ? 'selected' : '' }}>
                                                {{ $row['mobile_number'] }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select> -->
                        </div>

                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="type">Level One:</h6>
                            <select class="mySelect form-control" id="level_one" name="level_one"
                                onchange="getAjaxData('Category','parent_id',event,'level_two','subCategories');checkFieldValidation(this);">
                                <option value="">--Select--</option>
                                @if (!empty($levelOne))
                                    @foreach ($levelOne as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('level_one') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-4 ">
                            <h6 class="fw-bold" for="type">Level Two:</h6>
                            <select class="mySelect form-control form-control-sm subCategories" id="level_two"
                                name="level_two"
                                onchange="getAjaxData('Category','parent_id',event,'level_three');checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($levelTwo))
                                    @foreach ($levelTwo as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('level_two') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="type">Level Three:</h6>
                            <select class="mySelect form-control form-control-sm subCategories" id="level_three"
                                name="level_three"
                                onchange="getTitleData('Complaint','level_one,level_two,level_three,',event,'level_one,level_two,level_three','complains','title');checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($levelThree))
                                    @foreach ($levelThree as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('level_three') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>


                        {{-- <div class="form-group  mb-3 col-md-3">
                            <h6 class="fw-bold" for="manufacturer">Title:</h6>
                            <select class="form-control" id="title" name="title">
                                <option value="">--Select--</option>
                                @if (!empty($titles))
                                    @foreach ($titles as $title)
                                        <option value="{{ $title }}"
                                            {{ request('title') == $title ? 'selected' : '' }}>
                                            {{ Helper::addDotAfterWords(5, $title) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div> --}}

                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="model">Status:</h6>
                            <select class="mySelect form-control p-2" id="complaint_status_id" name="complaint_status_id">
                                <option value="">--Select--</option>
                                @if(!empty($statuses))
                                    @foreach($statuses as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}" {{ request('complaint_status_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="complaint_approved_id">Approval Status:</h6>
                            <select class="mySelect form-control form-control-sm" name="complaint_approved_id">
                                <option value="">--Select--</option>
                                <option value="1" {{ request('complaint_approved_id') == '1' ? 'selected' : '' }}>Approved</option>
                                <option value="0" {{ request('complaint_approved_id') == '0' ? 'selected' : '' }}>Pending</option>
                            </select>
                        </div>


                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="city_id">City</h6>
                            <select class="mySelect form-control form-control-sm" id="city_id" name="city_id"
                                onchange="getAjaxData('NewArea','city_id',event,'new_area_id','city');checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($cities))
                                    @foreach ($cities as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('city_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="new_area_id">Area</h6>
                            <select class="mySelect form-control form-control-sm city" id="new_area_id" name="new_area_id"
                                onchange="getNewAreaGridData(event);checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($newAreas))
                                    @foreach ($newAreas as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('new_area_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ Arr::get($row, 'name') }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>


                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="district_id">District</h6>
                            <select class="mySelect form-control form-control-sm new_area" id="district_id"
                                name="district_id" onchange="checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($districts))
                                    @foreach ($districts as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('district_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-3 d-none">
                            <h6 class="fw-bold" for="sub_division_id">Sub Division</h6>
                            <select class="mySelect form-control form-control-sm new_area" id="sub_division_id"
                                name="sub_division_id" onchange="checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($divisions))
                                    @foreach ($divisions as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('sub_division_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-3 d-none">
                            <h6 class="fw-bold" for="union_council_id">UC</h6>
                            <select class="mySelect form-control form-control-sm new_area" id="union_council_id"
                                name="union_council_id" onchange="checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($ucs))
                                    @foreach ($ucs as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('union_council_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-3 d-none">
                            <h6 class="fw-bold" for="charge_id">Charge</h6>
                            <select class="mySelect form-control form-control-sm new_area" id="charge_id"
                                name="charge_id" onchange="checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($charges))
                                    @foreach ($charges as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('charge_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>



                        <div class="form-group  mb-3 col-md-3 d-none">
                            <h6 class="fw-bold" for="ward_id">Ward</h6>
                            <select class="mySelect form-control form-control-sm new_area" id="ward_id" name="ward_id"
                                onchange="checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($wards))
                                    @foreach ($wards as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('ward_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="provincial_assembly_id">NA</h6>
                            <select class="mySelect form-control form-control-sm city new_area"
                                id="provincial_assembly_id" name="provincial_assembly_id"
                                onchange="checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($nas))
                                    @foreach ($nas as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('national_assembly_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="national_assembly_id">PS</h6>
                            <select class="mySelect form-control form-control-sm city new_area" id="national_assembly_id"
                                name="national_assembly_id" onchange="checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($pas))
                                    @foreach ($pas as $row)
                                        <option value="{{ trim(Arr::get($row, 'id')) }}"
                                            {{ request('provincial_assembly_id') == trim(Arr::get($row, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($row, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="mna_id">MNA</h6>
                            <select class="mySelect form-control form-control-sm" id="mna_id"
                                name="mna_id" onchange="checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if(!empty($mnaList))
                                    @foreach($mnaList as $mna)
                                        <option value="{{ trim(Arr::get($mna, 'id')) }}"
                                            {{ request('mna_id') == trim(Arr::get($mna, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($mna, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-4">
                            <h6 class="fw-bold" for="mpa_id">MPA</h6>
                            <select class="mySelect form-control form-control-sm" id="mpa_id"
                                name="mpa_id" onchange="checkFieldValidation(this);">
                                <option value=''>--Select--</option>
                                @if (!empty($mpaList))
                                    @foreach ($mpaList as $mpa)
                                        <option value="{{ trim(Arr::get($mpa, 'id')) }}"
                                            {{ request('mpa_id') == trim(Arr::get($mpa, 'id')) ? 'selected' : '' }}>
                                            {{ trim(Arr::get($mpa, 'name')) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group  mb-3 col-md-12 mt-2 gap-2 d-inline-flex justify-content-end">
                            <button type="submit"
                                class="btn bg-theme-yellow text-dark p-2 d-flex align-items-center gap-1" id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('report-by-complaints') }}"
                                class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        <div class="container mt-5">
            <h2>Results</h2>
            <div class="text-end">
                <div class="btn-group" role="group">
                    @if ($complaints->isNotEmpty())
                        <form action="{{ route('report-by-complaints') }}" method="GET">
                            <!-- Hidden fields for filters -->
                            <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                            <input type="hidden" name="cnic" value="{{ request('cnic') }}">
                            <input type="hidden" name="mobile_number" value="{{ request('mobile_number') }}">
                            <input type="hidden" name="level_one" value="{{ request('level_one') }}">
                            <input type="hidden" name="level_two" value="{{ request('level_two') }}">
                            <input type="hidden" name="level_three" value="{{ request('level_three') }}">
                            <input type="hidden" name="title" value="{{ request('title') }}">
                            <input type="hidden" name="city_id" value="{{ request('city_id') }}">
                            <input type="hidden" name="new_area_id" value="{{ request('new_area_id') }}">
                            <input type="hidden" name="district_id" value="{{ request('district_id') }}">
                            <input type="hidden" name="sub_division_id" value="{{ request('sub_division_id') }}">
                            <input type="hidden" name="union_council_id" value="{{ request('union_council_id') }}">
                            <input type="hidden" name="charge_id" value="{{ request('charge_id') }}">
                            <input type="hidden" name="ward_id" value="{{ request('ward_id') }}">
                            <input type="hidden" name="provincial_assembly_id" value="{{ request('provincial_assembly_id') }}">
                            <input type="hidden" name="national_assembly_id" value="{{ request('national_assembly_id') }}">
                            <button type="submit" name="export" value="excel" 
                                class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2">
                                <span><i class="fa fa-file-export"></i> Export CSV </span>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            
            
            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact table-sm">
                    <thead>
                        <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Description</th>
                            <th scope="col">Assigned To</th>
                            <th scope="col">City</th>
                            <th scope="col">District</th>
                            {{-- <th scope="col">Sub Division</th>
                            <th scope="col">Charge</th>
                            <th scope="col">Union Council</th>
                            <th scope="col">Ward</th> --}}
                            <th scope="col">National Assembly</th>
                            <th scope="col">Provincial Assembly</th>
                            <th scope="col">New Area</th>
                        </tr>
                    </thead>
                    @if (!empty($complaints) && count($complaints) > 0)
                        <tbody id="results-report">
                            @foreach ($complaints as $complaint)
                                <tr>
                                    <td>{{ Helper::addDotAfterWords(5, $complaint->title) }}</td>
                                    <td>{!! $complaint->description !!}</td>
                                    <td>{{ $complaint->user->name ?? 'N/A' }}</td>
                                    <td>{{ $complaint->city->name ?? 'N/A' }}</td>
                                    <td>{{ $complaint->district->name ?? 'N/A' }}</td>
                                    {{-- <td>{{ $complaint->subDivision->name ?? 'N/A' }}</td>
                                    <td>{{ $complaint->charge->name ?? 'N/A' }}</td>
                                    <td>{{ $complaint->unionCouncil->name ?? 'N/A' }}</td>
                                    <td>{{ $complaint->ward->name ?? 'N/A' }}</td> --}}
                                    <td>{{ $complaint->nationalAssembly->name ?? 'N/A' }}</td>
                                    <td>{{ $complaint->provincialAssembly->name ?? 'N/A' }}</td>
                                    <td>{{ $complaint->newArea->name ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                </table>
            </div>
            <div class="d-flex">
                {!! $complaints->links() !!}
            </div>
            @endif
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // Check if any filter values are selected
            var filtersSelected = $("form#complainReport select").filter(function() {
                return this.value !== '';
            }).length;

            // Show filter box if filters are selected or if it's initially visible
            if (filtersSelected > 0 || $("#filterBox").is(":visible")) {
                $("#filterBox").show();
            }

            // Add a click event handler to the element with ID "showFilterBox"
            $("#showFilterBox").click(function() {
                $("#filterBox").toggle();
            });

            $('.mySelect').select2();
        });
    </script>
    <script>
        function getTitleData(className, fieldName, fieldId, dependentIds, destinationClass, destinationId) {
            // Get the select element from the event object
            const selectElement = fieldId.target;
            var url = "";

            // Get the ID of the select element
            const selectId = selectElement.id;
            //console.log(selectId);
            // Get the value of the selected option


            var selectedValue = selectElement.value;
            //console.log(selectedValue);

            if (selectedValue) {
                selectedValue = '/' + selectedValue;
            }

            //console.log(selectedValue);
            $(".loader").addClass("show");

            toastr.options = {
                "closeButton": true,
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // var districtId = $('#district_id').val();
            if (dependentIds) {
                dependentIds = dependentIds.split(",");
                var dependentValues = [];
                var url = '{{ route('get.data') }}' + '/' + className + '/';
                var count = 0;
                dependentIds.forEach(element => {
                    count++;
                    url += element + '/' + $('#' + element).val();
                    if (count != dependentIds.length) {
                        url += '/';
                    }

                });
                //console.log(url);
            }
            destination = "#" + destinationId;

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url,
                method: 'get',
                success: function(result) {
                    //console.log(result);
                    $(destination).val('');
                    // $(destination).select2('destroy').val("").select2();

                    $(destination).html('');
                    $(destination).html("<option value=''>--Select--</option>");

                    $('#' + destinationId).append(result);

                    $(".loader").removeClass("show");

                },
                error: function(data, textStatus, errorThrown) {
                    $(".loader").removeClass("show");
                    // if (data) {
                    //     toastr.error('Something went wrong. Please try again.');
                    //     //console.log(JSON.stringify(data));
                    // }


                }

            });
        }

        function getAjaxData(className, fieldName, fieldId, destinationId, destinationClass) {

            // Get the select element from the event object
            const selectElement = fieldId.target;

            // Get the ID of the select element
            const selectId = selectElement.id;

            // Get the value of the selected option
            var selectedValue = selectElement.value;

            if (selectedValue) {
                selectedValue = '/' + selectedValue;
            }


            $(".loader").addClass("show");

            toastr.options = {
                "closeButton": true,
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            var districtId = $('#district_id').val();
            var url = '{{ route('get.data') }}' + '/' + className + '/' + fieldName + selectedValue;
            console.log(url);
            if (className == 'Ward' && fieldName && selectedValue) {
                var url = '{{ route('get.data') }}' + '/' + className + '/' + fieldName + selectedValue + '/' +
                    'district_id/' + districtId;
            }

            //console.log(url);


            let destination = '';
            if (destinationClass) {
                destination = "." + destinationClass;
            } else {
                destination = "#" + destinationId;
            }

            console.log(destination);
            console.log(destinationId);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: url,
                method: 'get',
                success: function(result) {
                    $(destination).val('');
                    // $(destination).select2('destroy').val("").select2();

                    $(destination).html('');
                    $(destination).html("<option value=''>--Select--</option>");

                    $('#' + destinationId).append(result);

                    $(".loader").removeClass("show");

                },
                error: function(data, textStatus, errorThrown) {
                    $(".loader").removeClass("show");
                    if (data) {
                        toastr.error('Something went wrong. Please try again.');
                        //console.log(JSON.stringify(data));
                    }


                }

            });
        }

        function getNewAreaGridData() {
            $(".loader").addClass("show");

            toastr.options = {
                "closeButton": true,
                "timeOut": "3000",
                "extendedTimeOut": "1000",
                "progressBar": true,
                "positionClass": "toast-top-right",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var newAreaId = $('#new_area_id').val();
            var url = '{{ route('get.new.area.grid.data') }}' + '/' + newAreaId;

            $.ajax({
                url: url,
                method: 'get',
                success: function(result) {

                    $('.new_area').val('');
                    // $('.new_area').select2('destroy').val("").select2();

                    $('.new_area').html('');
                    $('.new_area').html("<option value=''>--Select--</option>");

                    //District 
                    generateDropDownOption(result.district, 'district_id');

                    //Sub Division
                    generateDropDownOption(result.sub_division, 'sub_division_id');

                    //union_council_id
                    generateDropDownOption(result.union_council, 'union_council_id');

                    //Charge 
                    generateDropDownOption(result.charge, 'charge_id');

                    //Ward
                    generateDropDownOption(result.ward, 'ward_id');

                    //NA 
                    generateDropDownOption(result.national_assembly, 'national_assembly_id');

                    //PS 
                    generateDropDownOption(result.provincial_assembly, 'provincial_assembly_id');

                    $(".loader").removeClass("show");

                },
                error: function(data, textStatus, errorThrown) {
                    $(".loader").removeClass("show");
                    if (data) {
                        toastr.error('Something went wrong. Please try again.');
                        console.log(JSON.stringify(data));
                    }


                }

            });
        }

        function generateDropDownOption(data, id) {
            if (data && Object.keys(data).length !== 0) {
                let isFirstOption = true;
                $.each(data, function(key, value) {
                    let option = $('<option></option>').attr('value', value.id).text(value.name);

                    if (isFirstOption) {
                        option.attr('selected', 'selected');
                        isFirstOption = false; // Set to false after selecting the first option
                    }


                    $('#' + id).append(option);
                });

                $('#' + id).trigger('change.select2');
            }
        }
    </script>

    <!--Select 2 -->
    <link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
    <script src="{!! url('assets/js/select2.min.js') !!}"></script>

@endsection
