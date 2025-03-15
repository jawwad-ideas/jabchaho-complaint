@extends('backend.layouts.app-master')

@section('content')
    <div class="bg-light p-2 rounded">
        <h1>Show user</h1>
        <div class="lead">

        </div>

        <div class="container mt-4">
            <div>
                Name: {{ $user->name }}
            </div>
            <div>
                Email: {{ $user->email }}
            </div>
            <div>
                Username: {{ $user->username }}
            </div>
            <div>
                Google 2FA: {{ config('constants.boolean_options.'.$user->google2fa_enabled) }}
            </div>
            
            
        </div>

    </div>
    <div class="mt-4">
        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-info">Edit</a>
        <a href="{{ route('users.index') }}" class="btn bg-theme-dark-300 text-light">Back</a>
    </div>
@endsection
