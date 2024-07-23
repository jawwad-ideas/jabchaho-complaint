@extends('backend.layouts.app-master')

@section('content')
    <div class="bg-light p-2 rounded">
        <h1>{{ ucfirst($role->name) }} Role</h1>
        <div class="lead">
            
        </div>
        
        <div class="container mt-4">

            <h3>Assigned permissions</h3>

            <table class="table table-striped">
                <thead>
                    <th scope="col" width="40%">Description</th>
                    <th scope="col" width="20%">Name</th>
                    <th scope="col" width="1%">Guard</th> 
                </thead>

                @foreach($rolePermissions as $permission)
                        @if(in_array($permission->name,config('constants.admin_user_default_action')))
                            @php continue; @endphp
                        @endif
                        
                        @php
                            $routeDescription = config('constants.admin_action_with_description');
                            $description = $permission->name;
                        @endphp
                        
                        @if(!empty($routeDescription[$permission->name]))
                            @php $description = $routeDescription[$permission->name]; @endphp
                        @endif
                
                    <tr>
                        <td>{{ $description }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>{{ $permission->guard_name }}</td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
    <div class="mt-4">
        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-info">Edit</a>
        <a href="{{ route('roles.index') }}" class="btn bg-theme-dark-300 text-light">Back</a>
    </div>
@endsection
