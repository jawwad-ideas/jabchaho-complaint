@extends('backend.layouts.app-master')

@section('content')
<div
    class="page-title-section border-bottom mb-1 d-lg-flex justify-content-between align-items-center d-block bg-theme-green">
    <div class="p-title">
        <h3 class="fw-bold text-white m-0">Complainants</h3>
        <small class="text-white"> Manage your Complainants here.</small>
    </div>
    <div class="text-xl-start text-md-center text-center mt-xl-0 mt-3">
        <div class="btn-group" role="group">
            <small id="showFilterBox" type="button"
                class="btn btn-sm rounded bg-theme-green-light me-2 border-0 text-theme-green fw-bold d-flex align-items-center p-2 gap-2"><i
                    class="fa fa-solid fa-filter"></i> <span>Filter</span></small>

        </div>
    </div>

</div>
<div class="page-content bg-white p-lg-5 px-2">

    <div class="bg-light p-4 rounded">

        <div class="" id="filterBox" style="display:none;">
            <form class="form-inline" method="GET" action="{{ route('complainants.index') }}">
                <div class="row mb-3">
                    <div class="col-sm-2">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="complainant_id"
                            value="{{ Arr::get($filterData, 'complainant_id') ?? '' }}"
                            onkeydown="return isNumberKey(event);" placeholder="Complainant ID">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="name"
                            value="{{ Arr::get($filterData, 'name') ?? '' }}" placeholder="Name">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="email"
                            value="{{ $filterData['email'] ?? '' }}" placeholder="Email">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="mobile_number"
                            maxlength="11" value="{{ $filterData['mobile_number'] ?? '' }}" placeholder="Mobile No.">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control form-control-sm" autocomplete="off" name="cnic"
                            value="{{ $filterData['cnic'] ?? '' }}" maxlength="15" oninput="formatCNIC(this);"
                            placeholder="CNIC">
                    </div>
                    <div class="col-sm-2">
                        <select class="form-control form-control-sm" id="gender" name="gender">
                            <option value="">Gender</option>
                            @foreach($genderOptions as $key => $value)
                            <option value="{{ $key }}" {{ ($filterData['gender'] ?? '') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="row mb-3">
                    <div class="col-sm-12  text-end">
                        <button type="submit"
                            class="btn bg-theme-green text-white p-2 d-inline-flex align-items-center gap-1">Search</button>
                        <a href="{{ route('complainants.index') }}"
                            class="btn bg-theme-dark text-white p-2 d-inline-flex align-items-center gap-1 text-decoration-none">Reset</a>
                    </div>

                </div>
            </form>
        </div>

        <div class="d-flex my-2">
            Showing results {{ ($complainants->currentPage() - 1) * config('constants.per_page') + 1 }} to
            {{ min($complainants->currentPage() * config('constants.per_page'), $complainants->total()) }} of
            {{ $complainants->total() }}
        </div>

        <div class="table-scroll-hr">
            <table class="table table-striped">
                <tr>
                    <th scope="col" width="1%">#</th>
                    <th scope="col" width="15%">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col" width="10%">Mobile Number</th>
                    <th scope="col" width="10%">Cnic</th>
                    <th scope="col" width="10%">Gender</th>
                    <th scope="col" width="1%" colspan="3">Action</th>

                    @foreach ($complainants as $key => $complainant)
                <tr>
                    <td>{{Arr::get($complainant,'id')}}</td>
                    <td>{{Arr::get($complainant,'full_name')}}</td>
                    <td>{{Arr::get($complainant,'email')}}</td>
                    <td>{{Arr::get($complainant,'mobile_number')}}</td>
                    <td>{{Arr::get($complainant,'cnic')}}</td>
                    <td>
                        @if(array_key_exists(Arr::get($complainant, 'gender'), config('constants.gender_options')))
                        {{config('constants.gender_options')[Arr::get($complainant, 'gender')]}}
                        @endif
                    </td>
                    <td><a href="{{ route('complainants.show', $complainant->id) }}"
                            class="btn btn-warning btn-sm">Show</a></td>
                    <td><a href="{{ route('complainants.edit', $complainant->id) }}"
                            class="btn btn-info btn-sm">Edit</a></td>
                    <td>
                        {!! Form::open(['method' => 'DELETE','route' => ['complainants.destroy',
                        $complainant->id],'style'=>'display:inline','onsubmit' => 'return ConfirmDelete()' ]) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

        <div class="d-flex">
            {!! $complainants->appends(Request::except('page'))->render() !!}
        </div>
    </div>


    <script>
    $("#showFilterBox").click(function() {
        $("#filterBox").toggle();
    });
    </script>
    @endsection
