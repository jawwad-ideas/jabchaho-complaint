@extends('backend.layouts.app-master')
@section('title', 'Complaints')
@section('content')

<div class="container mt-5">
    <h1 class="mb-4">Search Assets</h1>
    <!-- <form> -->
    <div class="form-row">
        <!-- Start Date Field -->
        <div class="form-group col-md-6">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date">
        </div>

        <!-- End Date Field -->
        <div class="form-group col-md-6">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date">
        </div>
    </div>

    <!-- Dropdowns -->
    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="type">Type:</label>
            <select class="form-control" id="type" name="type">
                <option value="">Select Type</option>
                <!-- Add options dynamically if needed -->
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="manufacturer">Manufacturer:</label>
            <select class="form-control" id="manufacturer" name="manufacturer">
                <option value="">Select Manufacturer</option>
                <!-- Add options dynamically if needed -->
            </select>
        </div>
        <div class="form-group col-md-4">
            <label for="model">Model/Version:</label>
            <select class="form-control" id="model" name="model">
                <option value="">Select Model/Version</option>
                <!-- Add options dynamically if needed -->
            </select>
        </div>
    </div>

    <!-- Buttons -->
    <div class="form-row">
        <div class="col-md-6">
            <button type="button" class="btn btn-primary col-md-12" id="consult">
                <i alt="Search" class="fa fa-search">Search</i>
            </button>
        </div>
        <div class="col-md-6">
            <button type="button" class="btn btn-secondary col-md-12" id="clean">
                <i alt="Clean" class="fa fa-trash">Clear</i>
            </button>
        </div>
    </div>
    <!-- </form> -->
</div>

@endsection