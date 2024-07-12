<style>
    .document-boxes {
            position: relative;
            cursor: pointer;
        }

        .savedFiles {
            display: flex;
            gap: 15px;
            /* justify-content: space-between; */
            flex-wrap: wrap;
            margin: 0 0 15px 0;
            max-height: 300px;
            overflow-y: auto;
            padding: 15px 29px;
        }

        .savedFiles::-webkit-scrollbar {
            width: 5px;
            -webkit-box-shadow: inset 0 0 10px rgb(100 197 186);
            border-radius: 10px;
            /* background-color: #F5F5F5; */
        }

        /* Track */
        .savedFiles:-webkit-scrollbar-track {
            /* background: #f1f1f1; */
        }

        /* Handle */
        .savedFiles::-webkit-scrollbar-thumb {
            /* background: #fff; */
            background: #57a79e;
            border-radius: 10px;
        }

        /* Handle on hover */
        .savedFiles::-webkit-scrollbar-thumb:hover {
            background: rgb(100 197 186);
        }

        .document-boxes .action-btns {
            display: flex;
            justify-content: end;
            gap: 5px;
            align-self: end;
            margin-top: -20px;
            margin-right: -10px;
            position: absolute;
            top: 0;
            z-index: 1;
            right: 0;
        }

        .document-boxes .action-btns a i {
            color: #6c757d;
            background: #fff;
            width: 25px;
            height: 25px;
            display: flex;
            align-items: center;
            border: 2px solid #6c757d;
            border-radius: 100%;
            text-align: center;
            justify-content: center;
            font-size: 12px;
        }
        .doc-icon {
            display: flex;
            align-items: center;
        }

    @media only screen and (max-width: 600px) {
    .page-content {
        margin: -60px 20px 0 20px;
    }
}

    </style>

    <!--Summer note -->
    <link href="{!! url('assets/css/summernote/summernote-bs4.min.css') !!}" rel="stylesheet">
    <script src="{!! url('assets/js/summernote/summernote-bs4.min.js') !!}"></script>
    <script src="{!! url('assets/js/summernote/summernote-ext-rtl.js') !!}"></script>
    <!--Summer note -->

    <div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">Complaint Information</h3>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="alert alert-danger"  id="error"   style="display:none"></div>
    <div class="alert alert-success" id="success" style="display:none"></div>

    <form class="form-horizontal p-4" autocomplete="off" id="complaintForm">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" id="selected" name="selected" value="complaint" />
        <div class="row">
            <input type="hidden" id="roleHaveAccess" name="roleHaveAccess" value="{{$roleHaveAccess}}" />
            @if(!empty($roleHaveAccess))

                <div class="col-lg-12">

                    <div class="form-section mb-5">
                        <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                            <h4 class="fw-bold mt-0">Complainant</h4>
                        </div>
                        <div class="form-section-fields ms-4">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="row mb-3 flex-column">
                                        <label for="title" class="col-form-label col-form-label-sm fw-bold">Name<span style="color: red"> * </span></label>
                                        <div class="">
                                        <input type="text" autocomplete="off" class="form-control form-control-sm" id="full-name" name="full_name" maxlength="50" onpaste="return false;" onkeydown="return isAlphabatKey(this);">
                                            <div id="NameErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="row mb-3 flex-column">
                                        <label for="title" class="col-form-label col-form-label-sm fw-bold">Email<span style="color: red"> * </span></label>
                                        <div class="">
                                        <input type="text" autocomplete="off" class="form-control form-control-sm" id="email" name="email" maxlength="50" >
                                            <div id="EmaileErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="row mb-3 flex-column">
                                        <label for="title" class="col-form-label col-form-label-sm fw-bold">CNIC <small class="">(Without dashes)</small> <span style="color: red"> * </span></label>
                                        <div class="">
                                        <input type="text" autocomplete="off" class="form-control form-control-sm" id="cnic" name="cnic" maxlength="15" onpaste="return false;" onkeydown="return isNumberKey(this);" oninput="formatCNIC(this);">
                                            <div id="CnicErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">


                                <div class="col-lg-4">
                                    <div class="row mb-3 flex-column">
                                        <label for="title" class="col-form-label col-form-label-sm fw-bold">Mobile Number <span style="color: red"> * </span></label>
                                        <div class="">
                                            <input value="" type="text" class="form-control form-control-sm"
                                                name="mobile_number" onpaste="return false;" placeholder="Mobile Number" maxlength="11" minlength="11"
                                                onkeydown="return isNumberKey(event);">
                                                <div id="MobileNumberErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-lg-4">
                                    <div class="row mb-3 flex-column">
                                        <label for="title" class="col-form-label col-form-label-sm fw-bold">Gender<span style="color: red"> * </span></label>
                                        <div class="">
                                            @if(!empty(config('constants.gender_options')))

                                                @foreach(config('constants.gender_options') as $key=>$value)
                                                    <input class="form-check-input" type="radio" name="gender" id="gender-{{$key}}" value="{{$key}}" >
                                                    <label class="form-check-label" for="gender-{{$key}}">{{$value}}</label>
                                                @endforeach

                                            @endif
                                            <div id="GenderErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-xxl-6 col-xl-12 col-lg-12 col-md-12">
                <div class="form-section mb-5">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Levels</h4>
                    </div>
                    <div class="form-section-fields ms-4">
                        <div class="row mb-3 flex-column">
                            <label for="level_one" class="col-form-label col-form-label-sm fw-bold">Complaint Category<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm" id="level_one"  name="level_one" onchange="getAjaxData('Category','parent_id',event,'level_two','subCategories');checkFieldValidation(this);" >
                                    <option value=''>--Select--</option>
                                    @if(!empty($leveOne) )
                                        @foreach($leveOne as $row)
                                            <option value="{{ trim(Arr::get($row,'id')) }}" >{{trim(Arr::get($row,'name'))}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="LevelOneErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>

                        <div class="row mb-3 flex-column">
                            <label for="level_two" class="col-form-label col-form-label-sm fw-bold">Level Two<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm subCategories" id="level_two"  name="level_two" onchange="getAjaxData('Category','parent_id',event,'level_three');checkFieldValidation(this);" >
                                    <option value=''>--Select--</option>
                                </select>
                                <div id="LevelTwoErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>

                        <div class="row mb-3 flex-column">
                            <label for="level_three" class="col-form-label col-form-label-sm fw-bold">Level Three<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm subCategories" id="level_three"  name="level_three"  onchange="checkFieldValidation(this);">
                                    <option value=''>--Select--</option>
                                </select>
                                <div id="LevelThreeErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>

                        @if(!empty($roleHaveAccess))

                        <div class="row mb-3 flex-column">
                            <label for="level_three" class="col-form-label col-form-label-sm fw-bold">Priority<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control c-select" name="priorityId">
                                    <option value="">Select...</option>
                                    @if(!empty($complaintPriorities))
                                        @foreach($complaintPriorities as $complaintPriority)
                                            <option value="{{ Arr::get($complaintPriority, 'id') }}" >{{ Arr::get($complaintPriority, 'name') }} </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="PriorityErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>
                        @endif

                        <div class="row mb-3 flex-column">
                            <label for="title" class="col-form-label col-form-label-sm fw-bold">Title<span style="color: red"> * </span></label>
                            <div class="">
                                <input type="text" class="form-control form-control-sm" maxlength="100" id="title" name="title" value=""  onpaste="return false;" onkeydown="return isAlphabatKey(this);" onchange="checkFieldValidation(this);">
                                <div id="TitleErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(empty($roleHaveAccess))
                    <div class="form-section mb-5">
                        <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                            <h4 class="fw-bold mt-0">Attachments</h4>
                        </div>
                        <div class="form-section-fields ms-4">
                        <div class="row mb-3 flex-column">
                            <label for="complaint" class="col-form-label col-form-label-sm fw-bold">File <small>
                                <!-- <span class="text-danger">(Maximum {{ config('constants.max_files')}})</span>-->
                            </small></label>
                                <div class="" id='complaintFileDiv'>
                                    <input class="form-control form-control-sm" name="attachment[]" type="file" id="complaint" multiple />
                                    <small><span class="text-danger">Supported Files ({{ config('constants.files_supported')}})</span></small>
                                    <div class="text-danger"  id="complaintForm-error"   style="display:block"></div>
                                </div>
                        </div>
                        </div>
                    </div>
                @endif
                <div class="row mb-3 flex-column file-attatchment-section mx-auto">
                    <label for="first_name" class="col-form-label col-form-label-sm fw-bold"></label>
                    <div class="savedFiles">
                    </div>
                 </div>

            </div>

            <div class="col-xxl-6 col-xl-12 col-lg-12 col-md-12">

                <div class="form-section mb-5">
                    <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                        <h4 class="fw-bold mt-0">Address Details</h4>
                    </div>
                    <div class="form-section-fields ms-4">

                        <div class="row mb-3 flex-column">
                            <label for="city_id" class="col-form-label col-form-label-sm fw-bold">City<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm" id="city_id"  name="city_id" onchange="getAjaxData('NewArea','city_id',event,'new_area_id','city');checkFieldValidation(this);" >
                                    <option value=''>--Select--</option>
                                    @if(!empty($cities) )
                                        @foreach($cities as $row)
                                            <option value="{{ trim(Arr::get($row,'id')) }}" >{{trim(Arr::get($row,'name'))}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <div id="CityErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>

                        <div class="row mb-3 flex-column">
                            <label for="new_area_id" class="col-form-label col-form-label-sm fw-bold">Area<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm  city" id="new_area_id"  name="new_area_id" onchange="getNewAreaGridData(event);checkFieldValidation(this);">
                                    <option value=''>--Select--</option>
                                </select>
                                <div id="NewAreaErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>


                        <div class="row mb-3 district_id_row">
                            <label for="district_id" class="col-form-label col-form-label-sm fw-bold">District<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm city new_area" id="district_id"  name="district_id" onchange="checkFieldValidation(this);" >
                                    <option value=''>--Select--</option>
                                </select>
                                <div id="DistrictErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>

                        <!-- flag for request validation -->
                        <input type="hidden" value="0" name="area_flag" id="area_flag">
                        <!-- flag for request validation ends -->

                        
                        <!-- input field if no options -->
                        <div class="row mb-3 district_id_input_row d-none">
                            <label for="district_input" class="col-form-label col-form-label-sm fw-bold">District<span style="color: red"> * </span></label>
                            <div class="">
                                <input class="form-control form-control-sm DistrictInput" id="district_input"  name="district_input" value="district-Others" readonly>
                                <div id="DistrictInputErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>

                        <div class="row mb-3 d-none">
                            <label for="sub_division_id" class="col-form-label col-form-label-sm fw-bold">Sub Division<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm city new_area" id="sub_division_id"  name="sub_division_id" onchange="checkFieldValidation(this);"  >
                                    <option value=''>--Select--</option>
                                </select>
                                <div id="SubDivisionErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>
                        

                        <div class="row mb-3 d-none">
                            <label for="union_council_id" class="col-form-label col-form-label-sm fw-bold">UC<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm city new_area" id="union_council_id"  name="union_council_id" onchange="checkFieldValidation(this);"  > {{--  getAjaxData('NationalAssembly','union_council_id',event,'national_assembly_id','uc2'); --}}
                                    <option value=''>--Select--</option>
                                </select>
                                <div id="UCErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>


                        <div class="row mb-3 d-none">
                            <label for="charge_id" class="col-form-label col-form-label-sm fw-bold">Charge<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm city new_area" id="charge_id"  name="charge_id" onchange="checkFieldValidation(this);" >
                                    <option value=''>--Select--</option>
                                </select>
                                <div id="ChargeErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>



                        <div class="row mb-3 d-none">
                            <label for="ward_id" class="col-form-label col-form-label-sm fw-bold">Ward<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm city new_area" id="ward_id"  name="ward_id" onchange="checkFieldValidation(this);" >
                                    <option value=''>--Select--</option>
                                </select>
                                <div id="WardErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>


                        <div class="row mb-3 flex-column na_row">
                            <label for="national_assembly_id" class="col-form-label col-form-label-sm fw-bold">NA<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm city new_area" id="national_assembly_id"  name="national_assembly_id" onchange="checkFieldValidation(this);" >
                                    <option value=''>--Select--</option>
                                </select>
                                <div id="NAErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>

                        <!-- input field if no options -->
                        <div class="row mb-3 d-none national_assembly_input_row">
                            <label for="national_assembly_input" class="col-form-label col-form-label-sm fw-bold">NA<span style="color: red"> * </span></label>
                            <div class="">
                                <input class="form-control form-control-sm NationalAssemblyInput" id="national_assembly_input"  name="national_assembly_input" value="NA-Others" readonly>
                                <div id="NAInputErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>


                        <div class="row mb-3 flex-column ps_row">
                            <label for="provincial_assembly_id" class="col-form-label col-form-label-sm fw-bold">PS<span style="color: red"> * </span></label>
                            <div class="">
                                <select class="mySelect form-select form-control form-control-sm city new_area" id="provincial_assembly_id"  name="provincial_assembly_id"  onchange="checkFieldValidation(this);">
                                    <option value=''>--Select--</option>
                                </select>
                                <div id="PSErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>

                        <!-- input field if no options -->
                        <div class="row mb-3 d-none provincial_assembly_input_row">
                            <label for="provincial_assembly_input" class="col-form-label col-form-label-sm fw-bold">PS<span style="color: red"> * </span></label>
                            <div class="">
                                <input class="form-control form-control-sm ProvisionalAssemblyInput" id="provincial_assembly_input"  name="provincial_assembly_input" value="PS-Others" readonly>
                                <div id="PSInputErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>


                        <div class="row mb-3 flex-column">
                            <label for="near-by" class="col-form-label col-form-label-sm fw-bold">Nearby<span style="color: red"> * </span></label>
                            <div class="">
                            <input type="text" class="form-control form-control-sm" maxlength="100" id="nearby" name="nearby" value=""  maxlength="200" >
                                <div id="NearbyErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>

                        <div class="row mb-3 flex-column">
                            <label for="address" class="col-form-label col-form-label-sm fw-bold">Address<span style="color: red"> * </span></label>
                            <div class="">
                                <textarea  name="address" id="address" rows="2" class="form-control"></textarea>
                                <div id="AddressErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-12">
                <div class="form-section mb-5">
                        <div class="form-section-fields ms-4">
                            <div class="row mb-3 flex-column">
                                <label for="description" class="col-form-label col-form-label-sm fw-bold mb-3">Complaint Details<span style="color: red"> * </span></label>
                                <div class="col-sm-12 text-left">
                                <!-- <textarea class="summernote" name="description" id="description"  onchange="checkFieldValidation(this);"></textarea>    -->
                                <textarea rows="6" class="form-control"  name="description" id="description"  onchange="checkFieldValidation(this);"></textarea>
                                    <div id="DescriptionErrorMsg" class="text-danger validation-message" style="display:none" ></div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>


            <div class="row mb-3 border-top py-4">
                <div class="form-btn text-end">
                    <button type="submit" id="submitComplaint" class="btn bg-theme-green text-white d-inline-flex align-items-center gap-3" value="Submit"> <i class="fa fa-save"></i> Submit</button>
                </div>
            </div>
        <div>


    </form>
</div>



    <script>

    function generateDropDownOption(data, id)
    {
        if (data && Object.keys(data).length !== 0)
        {
            $('.district_id_row').show();
            $('.na_row').show();
            $('.ps_row').show();
            $('.district_id_input_row').addClass('d-none');
            $('.national_assembly_input_row').addClass('d-none');
            $('.provincial_assembly_input_row').addClass('d-none');
            $('#area_flag').val(0);

            let isFirstOption = true;
            $.each(data, function(key, value)
            {
                let option = $('<option></option>').attr('value', value.id).text(value.name);

                if (isFirstOption)
                {
                    option.attr('selected', 'selected');
                    isFirstOption = false; // Set to false after selecting the first option
                }


                $('#'+id).append(option);
            });

            $('#'+id).trigger('change.select2');
        }else{

            $('.district_id_row').hide();
            $('.na_row').hide();
            $('.ps_row').hide();
            $('.district_id_input_row').removeClass('d-none');
            $('.national_assembly_input_row').removeClass('d-none');
            $('.provincial_assembly_input_row').removeClass('d-none');
            $('#area_flag').val(1);
        }
    }


    function getNewAreaGridData()
    {
        $(".loader").addClass("show");

        toastr.options =
        {
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
        var url = '{{ route("get.new.area.grid.data") }}'+'/'+newAreaId;

        $.ajax({
                url: url,
                method: 'get',
                success: function(result)
                {

                    $('.new_area').val('');
                    $('.new_area').select2('destroy').val("").select2();

                    $('.new_area').html('');
                    $('.new_area').html("<option value=''>--Select--</option>");

                    //District
                    generateDropDownOption(result.district,'district_id');

                    //Sub Division
                    generateDropDownOption(result.sub_division,'sub_division_id');

                    //union_council_id
                    generateDropDownOption(result.union_council,'union_council_id');

                    //Charge
                    generateDropDownOption(result.charge,'charge_id');

                    //Ward
                    generateDropDownOption(result.ward,'ward_id');

                    //NA
                    generateDropDownOption(result.national_assembly,'national_assembly_id');

                    //PS
                    generateDropDownOption(result.provincial_assembly,'provincial_assembly_id');

                    $(".loader").removeClass("show");

                },
                error: function (data, textStatus, errorThrown)
                {
                    $(".loader").removeClass("show");
                    if(data)
                    {
                        toastr.error('Something went wrong. Please try again.');
                        console.log(JSON.stringify(data));
                    }


                }

        });
    }



    function getAjaxData(className,fieldName,fieldId,destinationId,destinationClass)
    {

        // Get the select element from the event object
        const selectElement = fieldId.target;

        // Get the ID of the select element
        const selectId = selectElement.id;

        // Get the value of the selected option
        var selectedValue = selectElement.value;

        if(selectedValue)
		{
			selectedValue = '/'+selectedValue;
		}


        $(".loader").addClass("show");

        toastr.options =
        {
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
        var url = '{{ route("get.data") }}'+'/'+className+'/'+fieldName+selectedValue;
        if(className == 'Ward' && fieldName && selectedValue)
        {
            var url = '{{ route("get.data") }}'+'/'+className+'/'+fieldName+selectedValue+'/'+'district_id/'+districtId;
        }



        let destination ='';
        if(destinationClass)
        {
            destination = "."+destinationClass;
        }
        else
        {
            destination ="#"+destinationId;
        }

       $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        $.ajax({
                url: url,
                method: 'get',
                success: function(result)
                {
                    $(destination).val('');
                    $(destination).select2('destroy').val("").select2();

                    $(destination).html('');
                    $(destination).html("<option value=''>--Select--</option>");

                    $('#'+destinationId).append(result);

                    $(".loader").removeClass("show");

                },
                error: function (data, textStatus, errorThrown)
                {
                    $(".loader").removeClass("show");
                    if(data)
                    {
                        toastr.error('Something went wrong. Please try again.');
                        console.log(JSON.stringify(data));
                    }


                }

            });
    }

    /////////////////////////////////////////////////////////FILE Upload\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
        var maxFiles = "{{config('constants.max_files')}}";

        $(document).ready(function (e) {
            var options =  {
                height: 160,
                placeholder: 'Start typing your text...',
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert',['ltr','rtl']],
                    ['insert', ['link','picture', 'video', 'hr']],
                    ['view', ['fullscreen', 'codeview']],
                ]
            };

            $('.summernote').summernote(options);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
            });

            var isFileUploadOnly = false;
            $("#complaint").on("change", function() {
                isFileUploadOnly = true;
                $("#complaintForm").submit();
            });

            //Complaint Form Submit via ajax
            $("#submitComplaint").on("click", function(e) {
                isFileUploadOnly = false;
            });

            //Upload file via ajax
            $('#complaintForm').on('submit',(function(e) {

                if(isFileUploadOnly)
                {
                    $(".loader").addClass("show");
                    e.preventDefault();
                        var selectedFile = $('#selected').val();

                        var formData = new FormData(this);
                            let TotalFiles = $("#"+selectedFile)[0].files.length; //Total files
                            let files = $("#"+selectedFile)[0];
                            for (let i = 0; i < TotalFiles; i++) {
                                formData.append('files' + i, files.files[i]);
                            }
                            formData.append('TotalFiles', TotalFiles);

                        formData.append('_token', "{{ csrf_token() }}");
                        formData.append('selected', selectedFile);

                    $('#complaintForm-error').hide();
                    $('#complaintForm-error').html('');

                    $.ajax({
                        type:'POST',
                        url: "{{ route('upload.compalint.files') }}",//$(this).attr('action'),
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success:function(data)
                        {
                            $(".loader").removeClass("show");

                        if(data.errors)
                        {
                            jQuery.each(data.errors, function(key, value)
                            {
                                $('#complaintForm-error').show();
                                $('#complaintForm-error').append('<li>'+value+'</li>');
                            });
                        }else{

                            if(data)
                            {
                                $.each(data, function(i, obj)
                                {
                                    $(".savedFiles").last().append('<div class="document-boxes" data-id="'+obj.id+'"><div class="doc-icon"><img class="center" src="'+obj.diaplay_image_path+'" ><small class="text-truncate">'+obj.name+'</small></div><div class="action-btns"><a class="deleteButton text-decoration-none"  href="#_"><i class="fa fa-trash removeComplaint" id="'+obj.id+'"  data-tempName="'+obj.temp_name+'" ></i></a></div></div>');
                                });

                                // if( maxFiles == $('.document-boxes').length)
                                // {
                                //     $('#complaintFileDiv').hide();
                                // }

                            }
                        }

                    },
                    error: function(data){
                        $(".loader").removeClass("show");
                        $('#complaintForm-error').show();
                        $('#complaintForm-error').html('Unable to process request. Please refresh the page and try again!!');

                    }
                    });

                    $('#complaint').val('');



                }else{

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                        });

                        //Complaint Form Submit via ajax
                        $(".loader").addClass("show");
                        toastr.options =
                        {
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
                        e.preventDefault();
                        var formData = new FormData(this);
console.log("{{ $storeUrl }}");
                        $.ajax({
                        type:'POST',
                        url: "{{ $storeUrl }}",//$(this).attr('action'),
                        data: formData,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success:function(response)
                        {
                            $('#LevelOneErrorMsg').html('');
                            $('#LevelTwoErrorMsg').html('');
                            $('#LevelThreeErrorMsg').html('');
                            $('#CityErrorMsg').html('');
                            $('#DistrictErrorMsg').html('');
                            $('#DistrictInputErrorMsg').html('');
                            $('#SubDivisionErrorMsg').html('');
                            $('#ChargeErrorMsg').html('');
                            $('#UCErrorMsg').html('');
                            $('#WardErrorMsg').html('');
                            $('#NAErrorMsg').html('');
                            $('#PSErrorMsg').html('');
                            $('#NAInputErrorMsg').html('');
                            $('#PSInputErrorMsg').html('');
                            $('#NewAreaErrorMsg').html('');
                            $('#NearbyErrorMsg').html('');
                            $('#AddressErrorMsg').html('');
                            $('#TitleErrorMsg').html('');
                            $('#DescriptionErrorMsg').html('');

                            //admin side complaint Fields
                            $('#PriorityErrorMsg').html('');
                            $('#NameErrorMsg').html('');
                            $('#EmaileErrorMsg').html('');
                            $('#CnicErrorMsg').html('');
                            $('#MobileNumberErrorMsg').html('');
                            $('#GenderErrorMsg').html('');

                            if(response.errors)
                            {
                                //admin side complaint Fields
                                if(response.errors.priorityId)
                                {
                                    $('#PriorityErrorMsg').show();
                                    $('#PriorityErrorMsg').append(response.errors.priorityId);
                                }


                                if(response.errors.full_name)
                                {
                                    $('#NameErrorMsg').show();
                                    $('#NameErrorMsg').append(response.errors.full_name);
                                }

                                if(response.errors.email)
                                {
                                    $('#EmaileErrorMsg').show();
                                    $('#EmaileErrorMsg').append(response.errors.email);
                                }

                                if(response.errors.cnic)
                                {
                                    $('#CnicErrorMsg').show();
                                    $('#CnicErrorMsg').append(response.errors.cnic);
                                }


                                if(response.errors.mobile_number)
                                {
                                    $('#MobileNumberErrorMsg').show();
                                    $('#MobileNumberErrorMsg').append(response.errors.mobile_number);
                                }


                                if(response.errors.gender)
                                {
                                    $('#GenderErrorMsg').show();
                                    $('#GenderErrorMsg').append(response.errors.gender);
                                }



                                //level_one
                                if(response.errors.level_one)
                                {
                                    $('#LevelOneErrorMsg').show();
                                    $('#LevelOneErrorMsg').append(response.errors.level_one);
                                }

                                 //level_two
                                 if(response.errors.level_two)
                                {
                                    $('#LevelTwoErrorMsg').show();
                                    $('#LevelTwoErrorMsg').append(response.errors.level_two);
                                }

                                 //level_three
                                 if(response.errors.level_three)
                                {
                                    $('#LevelThreeErrorMsg').show();
                                    $('#LevelThreeErrorMsg').append(response.errors.level_three);
                                }

                                //province
                                if(response.errors.city_id)
                                {
                                    $('#CityErrorMsg').show();
                                    $('#CityErrorMsg').append(response.errors.city_id);
                                }

                                //district
                                if(response.errors.district_id)
                                {
                                    $('#DistrictErrorMsg').show();
                                    $('#DistrictErrorMsg').append(response.errors.district_id);
                                }

                                if(response.errors.district_input)
                                {
                                    $('#DistrictInputErrorMsg').show();
                                    $('#DistrictInputErrorMsg').append(response.errors.district_input);
                                }

                                //Sub Division
                                if(response.errors.sub_division_id)
                                {
                                    $('#SubDivisionErrorMsg').show();
                                    $('#SubDivisionErrorMsg').append(response.errors.sub_division_id);
                                }

                                //charge_id
                                if(response.errors.charge_id)
                                {
                                    $('#ChargeErrorMsg').show();
                                    $('#ChargeErrorMsg').append(response.errors.charge_id);
                                }

                                //UC
                                if(response.errors.union_council_id)
                                {
                                    $('#UCErrorMsg').show();
                                    $('#UCErrorMsg').append(response.errors.union_council_id);
                                }

                                //Ward
                                if(response.errors.ward_id)
                                {
                                    $('#WardErrorMsg').show();
                                    $('#WardErrorMsg').append(response.errors.ward_id);
                                }

                                //national_assembly
                                if(response.errors.national_assembly_input)
                                {
                                    $('#NAInputErrorMsg').show();
                                    $('#NAInputErrorMsg').append(response.errors.national_assembly_input);
                                }

                                if(response.errors.national_assembly_id)
                                {
                                    $('#NAErrorMsg').show();
                                    $('#NAErrorMsg').append(response.errors.national_assembly_id);
                                }

                                //provincial_assembly
                                if(response.errors.provincial_assembly_id)
                                {
                                    $('#PSErrorMsg').show();
                                    $('#PSErrorMsg').append(response.errors.provincial_assembly_id);
                                }

                                if(response.errors.provincial_assembly_input)
                                {
                                    $('#PSInputErrorMsg').show();
                                    $('#PSInputErrorMsg').append(response.errors.provincial_assembly_input);
                                }

                                //new area
                                if(response.errors.new_area_id)
                                {
                                    $('#NewAreaErrorMsg').show();
                                    $('#NewAreaErrorMsg').append(response.errors.new_area_id);
                                }

                                //NearByErrorMsg
                                if(response.errors.nearby)
                                {
                                    $('#NearbyErrorMsg').show();
                                    $('#NearbyErrorMsg').append(response.errors.nearby);
                                }

                                //title
                                if(response.errors.title)
                                {
                                    $('#TitleErrorMsg').show();
                                    $('#TitleErrorMsg').append(response.errors.title);
                                }

                                //description
                                if(response.errors.description)
                                {
                                    $('#DescriptionErrorMsg').show();
                                    $('#DescriptionErrorMsg').append(response.errors.description);
                                }

                                //address
                                if(response.errors.address)
                                {
                                    $('#AddressErrorMsg').show();
                                    $('#AddressErrorMsg').append(response.errors.address);
                                }

                                $(".loader").removeClass("show");
                            }
                            else if(response.status)
                            {
                                toastr.success(response.message);
                                setTimeout(() => {
                                    //window.location.reload();
                                    window.location.href = "{{ $redirectUrl }}";
                                }, 3000);
                            }
                            else{
                                toastr.error(response.message);
                                $(".loader").removeClass("show");
                            }




                        },
                        error: function(response){
                            toastr.error(response.message);
                            $(".loader").removeClass("show");


                        }
                    });
                }



            }));


            //remove file
            $(document).on('click', '.removeComplaint', function (e) {

               //get  file unique name or id
                var dataId  = $(this).attr('id');
                //data-tempName
                var tempName  = $(this).attr('data-tempName');

                if(dataId)
                {
                    //remove Div via data-id attribute
                    var divToRemove = $('div[data-id="' + dataId + '"]');

                    // Remove the selected <div> element from the DOM.
                    divToRemove.remove();


                    // if( maxFiles > $('.document-boxes').length)
                    // {
                    //     $('#complaintFileDiv').show();
                    // }


                    var url = '{{ route('remove.compalint.files', ':tempName') }}';
                    url = url.replace(':tempName', tempName);


                    //ajax request for removing file.
                    $.ajax({
                        type:'DELETE',
                        url: url,
                        cache:false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',
                        success:function(data)
                        {
                            $(".loader").removeClass("show");

                        },
                        error: function(data){
                            $(".loader").removeClass("show");
                            $('#complaintForm-error').show();
                            $('#complaintForm-error').html('Unable to process request. Please refresh the page and try again!!');

                        }
                    });
                }

            });

            $('.mySelect').select2();


    });

    </script>

    <!--Select 2 -->
    <link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
    <script src="{!! url('assets/js/select2.min.js') !!}"></script>
