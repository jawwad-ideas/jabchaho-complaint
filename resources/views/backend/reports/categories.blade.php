@extends('backend.layouts.app-master')
@section('title', 'Complaints')
@section('content')

<div class="container mt-5">
    <h1>Tickets By Categories</h1>
    <form>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="start_date">Start Date:</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>

            <div class="form-group col-md-6">
                <label for="end_date">End Date:</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
        </div>

        <div class="form-row">
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary col-md-12" id="consult"> <i alt="Search"
                        class="fa fa-search">Search</i>
                </button>
            </div>
            <div class="col-md-6">
                <button type="button" class="btn btn-secondary col-md-12" id="clean">
                    <i alt="Clean" class="fa fa-trash">Clear</i>
                </button>
            </div>
        </div>
    </form>
</div>

@endsection