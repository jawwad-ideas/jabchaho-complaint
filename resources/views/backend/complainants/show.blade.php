@extends('backend.layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h1>Show Complainant</h1>
        <div class="lead">

        </div>

        <div class="container mt-4">
            <div>
                Name: {{ $complainant->full_name }}
            </div>
            <div>
                Email: {{ $complainant->email }}
            </div>
            <div>
                Mobile Number: {{ $complainant->mobile_number }}
            </div>
            <div>
                Cnic: {{ $complainant->cnic }}
            </div>
            <div>
                Gender:
                @if(array_key_exists(Arr::get($complainant, 'gender'), config('constants.gender_options')))
                    {{config('constants.gender_options')[Arr::get($complainant, 'gender')]}}
                @endif
            </div>
        </div>

    </div>
    <div class="mt-4">
        <a href="{{ route('complainants.edit', $complainant->id) }}" class="btn btn-info">Edit</a>
        <a href="{{ route('complainants.index') }}" class="btn btn-default">Back</a>
    </div>
@endsection
