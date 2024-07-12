@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
        <div class="p-title">
            <h3 class="fw-bold text-white m-0">Add User Wise Area Mappings</h3>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">

        <div class="alert alert-danger" id="error" style="display:none"></div>
        <div class="alert alert-success" id="success" style="display:none"></div>
        <form method="post" action="{{ route('area.mapping.create') }}" autocomplete="off">
            @csrf
            <div class="row">
                <div class="col-lg-12">

                    <div class="form-section mb-5">
                        <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                            <h4 class="fw-bold mt-0">Add Area Mapping</h4>
                        </div>
                    </div>
                    <div class="container mt-4">
                        {{-- Users --}}
                        <!-- <div class="mb-3">
                            <label for="user_id" class="form-label">User</label>
                            <select class="mySelect form-control" name="user_id">
                                <option value="">Select User</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div> -->
                        {{-- New Areas --}}
                        <div class="mb-3">
                            <label for="new_area_id" class="form-label">Area</label>
                            <select class="mySelect form-control" id="new_area_id" name="new_area_id[]" multiple="multiple">
                                <option value="">Select Area</option>
                                @foreach ($new_areas as $new_area)
                                    <option value="{{ $new_area->id }}"
                                        {{ old('new_area_id') == $new_area->id ? 'selected' : '' }}>
                                        {{ $new_area->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- New Areas --}}
                        <div class="mb-3">
                            <label for="district_id" class="form-label">District</label>
                            <select class="form-control" id="district_id" name="district_id">
                                <option value="">Select District</option>
                                @foreach ($districts as $district)
                                    <option value="{{ $district->id }}"
                                        {{ old('district_id') == $district->id ? 'selected' : '' }}>
                                        {{ $district->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        {{-- NA --}}
                        <div class="mb-3">
                            <label for="national_assembly_id" class="form-label">NA</label>
                            <select class="mySelect form-control" name="national_assembly_id">
                                <option value="">Select NA</option>
                                @foreach ($nas as $na)
                                    <option value="{{ $na->id }}"
                                        {{ old('national_assembly_id') == $na->id ? 'selected' : '' }}>
                                        {{ $na->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- PS --}}
                        <div class="mb-3">
                            <label for="provincial_assembly_id" class="form-label">PS</label>
                            <select class="mySelect form-control" name="provincial_assembly_id">
                                <option value="">Select PS</option>
                                @foreach ($pas as $ps)
                                    <option value="{{ $ps->id }}"
                                        {{ old('provincial_assembly_id') == $ps->id ? 'selected' : '' }}>
                                        {{ $ps->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit"
                            class="btn bg-theme-green text-white d-inline-flex align-items-center gap-3">Create Area
                            Mapping</button>
                        <a href="{{ route('area.mapping.index') }}" class="btn btn-default">Cancel</a></button>
                    </div>
                </div>
            </div>
        </form>
    </div>
        <script>
            $(document).ready(function (e) {
                $('.mySelect').select2();
            });
        </script>
            <!--Select 2 -->
<link href="{!! url('assets/css/select2.min.css') !!}" rel="stylesheet">
<script src="{!! url('assets/js/select2.min.js') !!}"></script>
@endsection
