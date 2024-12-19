@extends('backend.layouts.app-master')

@section('content')
    <style>
        tr[data-url] {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        tr[data-url]:hover {
            background-color: #f0f0f0;
        }
    </style>
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">
                Machines
            </h3>
            <small class="text-dark">Manage your machines here.</small>
        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                <small id="showFilterBox" type="button"
                       class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                        class="fa fa-solid fa-filter"></i> <span>Filter</span>
                </small>
            </div>
        </div>
    </div>
    <div class="page-content bg-white p-lg-5 px-2">

        <div class="bg-light p-2 rounded">
            <div id="modalDiv"></div>

            <div class="" id="filterBox" style="display:block;">
                <form class="form-inline" method="GET" action="{{ route('machine.list') }}">
                    <div class="row mb-3">
                        <div class="col-lg-12 d-flex flex-wrap">
                            <div class="col-sm-3 px-2">
                                <input type="text" class="form-control p-2" autocomplete="off" name="name" value="{{ $name ?? '' }}" placeholder="Machine Name">
                            </div>
                            <div class="col-sm-3 px-2 mt-2">
                                <select class="form-select p-2" id="is_enabled" name="is_enabled">
                                    <option value="" {{ $is_enabled === null ? 'selected' : '' }}>Status</option>
                                    @foreach($statusOption as $key => $option)
                                        <option value="{{ $key }}" {{ (string)$key === (string)$is_enabled ? 'selected' : '' }}>
                                            {{ ucfirst($option) }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                    class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                                    id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('machine.list') }}"
                               class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="d-flex my-2">
                Showing results {{ ($machines->currentPage() - 1) * config('constants.per_page') + 1 }} to
                {{ min($machines->currentPage() * config('constants.per_page'), $machines->total()) }} of {{ $machines->total() }}
            </div>

            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact " id="clickableTable">
                    <thead>
                    <tr>
                        <th scope="col" width="1%">Sr no.</th>
                        <th scope="col" width="15%">Machine Name</th>
                        <th scope="col" width="15%">Status</th>
                        <th scope="col" width="1%" colspan="3">Action</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach ($machines as $machine)
                        <tr data-url="{{ route('orders.edit', $machine->id) }}">
                            <td scope="row">{{ $machine->id }}</td>
                            <td width="15%">{{ $machine->name }}</td>
                            <td width="15%">@if( $machine->is_enabled == 1  ) Active @else Inactive @endif</td>
                            <td><a href="{{ route('orders.edit', $machine->id) }}" class="btn btn-info btn-sm"><i class="fa fa-pencil"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex">
                {!! $machines->links() !!}
            </div>

        </div>
    </div>
    <!--Assign To Modal -->
    <div id="modalDiv"></div>

    <script>
        $(document).ready(function () {
            $("#showFilterBox").click(function() {
                $("#filterBox").toggle();
            });

        document.getElementById('clickableTable').addEventListener('click', function(event) {
            const row = event.target.closest('tr'); // Get the clicked <tr>
            if (row && row.dataset.url) {
                window.location.href = row.dataset.url;
            }
        });

    </script>

@endsection
