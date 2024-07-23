@extends('backend.layouts.app-master')

@section('content')
    <div
        class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-yellow">
        <div class="p-title">
            <h3 class="fw-bold text-dark m-0">Edit Category</h3>
        </div>

    </div>
    <div class="page-content bg-white p-lg-5 px-2">

        <div class="alert alert-danger" id="error" style="display:none"></div>
        <div class="alert alert-success" id="success" style="display:none"></div>
        <div class="container mt-4">

            <form method="POST" action="{{ route('categories.edit') }}">
                @csrf
                <input type="hidden" id="categoryId" name="categoryId" value="{{ Arr::get($categoryData, 'id') }}">
                <div class="row">
                    <div class="col-lg-12">

                        <div class="form-section mb-5">
                            <div class="form-section mb-5 form-section-title m-xl-0 mx-2 my-3">
                                <h4 class="fw-bold mt-0">Edit this Category</h4>
                            </div>
                        </div>
                        <div class="container mt-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Name</label>
                                <input
                                    value="@if (old('name')) {{ old('name') }}@elseif(empty(old('name')) && old('_token')) {{ '' }}@else{{ Arr::get($categoryData, 'name') }} @endif"
                                    type="text" class="form-control" name="name" maxlength="100" placeholder="Name"
                                    id="name">
                            </div>

                            <div class="mb-3">
                                <label for="level" class="form-label">Level</label>
                                <select class="form-control" id="level" name="level">
                                    <option value="">Select the level</option>
                                    @foreach ([1, 2, 3] as $level)
                                        <option value="{{ $level }}"
                                            @if ($level == Arr::get($categoryData, 'level')) selected @endif>Level
                                            {{ $level }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Parent</label>
                                <select class="form-control" id="parent_id" name="parent_id">
                                    <option value="0">Select a parent</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category['id'] }}"
                                            @if ($category['id'] == Arr::get($categoryData, 'parent_id')) selected @endif>
                                            {{ $category['name'] }}</option>
                                    @endforeach
                                </select>
                            </div>


                            <button type="submit"
                                class="btn bg-theme-yellow text-dark d-inline-flex align-items-center gap-3">Update
                                Category</button>
                            <a href="{{ route('categories.index') }}" class="btn bg-theme-dark-300 text-light">Back</a>
                        </div>
                    </div>
                </div>
            </form>
            <div class="container px-4 pt-2">
                @if (Auth::user()->can('categories.destroy'))
                    {!! Form::open(['method' => 'DELETE','route' => ['categories.destroy',
                    Arr::get($categoryData, 'id')],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()' ]) !!}
                    {!! Form::submit('Delete Category', ['class' => 'btn bg-danger text-dark d-inline-flex align-items-center gap-3']) !!}
                    {!! Form::close() !!}
                @endif
            </div>
        </div>

    </div>
@endsection
