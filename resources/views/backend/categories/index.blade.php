@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Categories</h3>
            <small class="text-dark">Manage your Categories here.</small>

        </div>
        <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
            <div class="btn-group" role="group">
                @if (Auth::user()->can('categories.addForm'))
                    <a href="{{ route('categories.addForm') }}" class="text-decoration-none">
                        <small type="button"
                            class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                                class="fa fa-solid fa-file-circle-plus"></i><span>New Category</span></small>
                    </a>
                    <small id="showFilterBox" type="button"
                        class="btn btn-sm rounded bg-theme-dark-300 text-light me-2 border-0 fw-bold d-flex align-items-center p-2 gap-2"><i
                            class="fa fa-solid fa-filter"></i> <span>Filter</span></small>
                @endif
            </div>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">
        <div class="bg-light p-2 rounded">
            <div class="" id="filterBox" style="display:none;">
                <form class="form-inline" method="GET" action="{{ route('categories.index') }}">
                    <div class="row mb-3">
                        <div class="col-sm-4">
                            <input type="text" class="form-control" autocomplete="off" name="name"
                                value="{{ $filterData['name'] ?? '' }}" placeholder="Search by name">
                        </div>
                        <div class="col-sm-2">
                            <select class="form-control c-select" name="level">
                                <option value="">Select level</option>
                                @foreach ([1, 2, 3] as $level)
                                    <option value="{{ $level }}"
                                        {{ $filterData['levelId'] == $level ? 'selected' : '' }}>Level
                                        {{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-control" id="parent" name="parent">
                                <option value="">Select parent</option>
                                @foreach ($parentCategories as $parentCategory)
                                    <option value="{{ $parentCategory->id }}"
                                        {{ $filterData['parentId'] == $parentCategory->id ? 'selected' : '' }}>
                                        {{ $parentCategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-12 text-end mt-4">
                            <button type="submit"
                                class="btn bg-theme-yellow text-dark p-2 d-inline-flex align-items-center gap-1"
                                id="consult">
                                <span>Search</span>
                                <i alt="Search" class="fa fa-search"></i>
                            </button>
                            <a href="{{ route('categories.index') }}"
                                class="btn bg-theme-dark-300 text-light p-2 d-inline-flex align-items-center gap-1 text-decoration-none">
                                <span>Clear</span>
                                <i class="fa fa-solid fa-arrows-rotate"></i></a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="d-flex my-2">
                Showing results {{ ($categories->currentPage() - 1) * config('constants.per_page') + 1 }} to
                {{ min($categories->currentPage() * config('constants.per_page'), $categories->total()) }} of
                {{ $categories->total() }}
            </div>
            <div class="table-scroll-hr">
                <table class="table table-bordered table-striped table-compact ">
                    <thead>
                        <th>Category Title</th>
                        <th>Level</th>
                        <th>Parent</th>
                        <th width="20%" colspan="3">Action</th>
                    </thead>
                    @foreach ($categories as $key => $category)
                        <tr>

                            <td>{{ Arr::get($category, 'name') }}</td>
                            <td>{{ Arr::get($category, 'level') }}</td>
                            @if (Arr::get($category, 'parent_id') == 0)
                                <td> NONE </td>
                            @else
                                <td>{{ Arr::get($category, 'parent_name') }}</td>
                            @endif

                            @if (Auth::user()->can('categories.editForm'))
                                <td>
                                    <a class="btn btn-info btn-sm"
                                        href="{{ route('categories.editForm', $category->id) }}"><i class="fa fa-pencil"></i></a>
                                </td>
                            @endif

                            @if (Auth::user()->can('categories.destroy'))
                                <td>
                                    {!! Form::open(['method' => 'DELETE','route' => ['categories.destroy',
                                    $category->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()' ]) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                    {!! Form::close() !!}
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="d-flex">
                {!! $categories->appends(Request::except('page'))->render() !!}
            </div>
        </div>
    </div>
    <script>
        $("#showFilterBox").click(function() {
            $("#filterBox").toggle();
        });
    </script>
@endsection
