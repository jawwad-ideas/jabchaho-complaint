@extends('backend.layouts.app-master')
@section('title', 'Complaints')
@section('content')

<div class="container mt-5">
    <h1 class="mb-4">Search Tickets</h1>
    <form>
        <div class="form-row">
            <!-- Start Date Field -->
            <div class="form-group col-md-6">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>

            <!-- End Date Field -->
            <div class="form-group col-md-6">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="type">Status:</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="">Select Status</option>
                    <!-- Add options dynamically if needed -->
                </select>
            </div>
            <div class="form-group col-md-4">
                <label for="manufacturer">Category:</label>
                <select class="form-control" id="category" name="category" required>
                    <option value="">Select Category</option>
                    <!-- Add options dynamically if needed -->
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="due_date">Due Date:</label>
                <input type="date" class="form-control" id="due_date" name="due_date" required>
            </div>

            <div class="form-group col-md-4">
                <label for="model">Entity:</label>
                <select class="form-control" id="entity" name="entity" required>
                    <option value="">Select Entity</option>
                    <!-- Add options dynamically if needed -->
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="model">Priority:</label>
                <select class="form-control" id="priority" name="priority" required>
                    <option value="">Select Priority</option>
                    <!-- Add options dynamically if needed -->
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="model">Type:</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="">Select Type</option>
                    <!-- Add options dynamically if needed -->
                </select>
            </div>

            <div class="form-group col-md-4">
                <label for="model">Source:</label>
                <select class="form-control" id="source" name="source" required>
                    <option value="">Select Source</option>
                    <!-- Add options dynamically if needed -->
                </select>
            </div>
        </div>

        <!-- Buttons -->
        <div class="form-row">
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary col-md-12" id="consult">
                    <i alt="Search" class="fa fa-search">Search</i>
                </button>
            </div>
            <div class="col-md-4">
                <button type="button" class="btn btn-secondary col-md-12" id="clean">
                    <i alt="Clean" class="fa fa-trash">Clear</i>
                </button>
            </div>
        </div>
    </form>
</div>

@endsection