<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Backend\StoreUserRequest;
use App\Http\Requests\Backend\UpdateUserRequest;
use App\Helpers\Helper;
use App\Models\NationalAssembly;
use App\Models\NewArea;
use App\Models\ProvincialAssembly;
use App\Models\UserWiseAreaMapping;
use Illuminate\Support\Arr;

class UsersController extends Controller
{
    /**
     * Display all users
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $filterData                     = array();
        $dashboardFilter = $request->query('dashboard_filter');

        $users = User::select('*')->orderBy('id', 'desc');

        //Apply Filter
        $full_name = $request->input('full_name');
        $user_email = $request->input('user_email');
        $user_name = $request->input('user_name');
        $roles = $request->input('role');

        //die($full_name);
        if (!empty($full_name)) {
            $users->where('users.name', 'like', '%' . $full_name . '%');
        }
        if (!empty($user_email)) {
            $users->where('users.email', 'like', '%' . $user_email . '%');
        }
        if (!empty($user_name)) {
            $users->where('users.username', 'like', '%' . $user_name . '%');
        }
        if (!empty($roles)) {
            $users->where('model_has_roles.role_id', $roles);
        }

        $users = $users->latest()->orderBy('id', 'DESC')->paginate(config('constants.per_page'));

        $roles = Role::all();

        $filterData = [
            'full_name' => $full_name,
            'user_email' => $user_email,
            'user_name' => $user_name,
            'roles' => $roles,
        ];

        $filterData['dashboardFilter'] = $dashboardFilter;

        return view('backend.users.index', compact('users'))->with($filterData);
    }

    /**
     * Show form for creating user
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $newArea = NewArea::all();
        $pA = ProvincialAssembly::all();
        $nA = NationalAssembly::all();
        $data = [
            'roles' => $roles,
            'newArea' => $newArea,
            'pA' => $pA,
            'nA' => $nA,
        ];
        return view('backend.users.create')->with($data);
    }

    /**
     * Store a newly created user
     *
     * @param User $user
     * @param StoreUserRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(User $user, StoreUserRequest $request)
    {
        //For demo purposes only. When creating user or inviting a user
        // you should create a generated random password and email it to the user

        $postUserData = $request->validated();
        unset($postUserData['confirm_password']);
        unset($postUserData['national_assembly_id']);
        unset($postUserData['provincial_assembly_id']);
        unset($postUserData['new_area_id']);
        unset($postUserData['role']);
        //encrypt password
        $postUserData['password'] = bcrypt($postUserData['password']);
        $userId = User::insertGetId($postUserData);
        $roleId = (int) $request->input('role');
        $roleIdArray = [$roleId];

        // Sync roles for the newly created user
        $user->find($userId)->syncRoles($roleIdArray);

        //for mna and mpa only
        $role = Role::where('id', $roleId)->first();
        if ($role->name == 'MNA' || $role->name == 'MPA') {
            $userWiseAreaMappingObject = new UserWiseAreaMapping;
            $newArea = $request->input('new_area_id');
            $pS = $request->input('provincial_assembly_id');
            $nA = $request->input('national_assembly_id');

            $userWiseData = [
                'new_area_id' => 0,
                'provincial_assembly_id' => $pS ? $pS : 0,
                'national_assembly_id' => $nA ? $nA : 0,
                'user_id' => $userId,
            ];
            $userWiseAreaMappingObject->insert($userWiseData);
        }

        return redirect()->route('users.index')
            ->withSuccess(__('User created successfully.'));
    }

    /**
     * Show user data
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $userObj = new User;
        $userId = $user->id;
        $area_na_ps_name = $userObj->getAreaNaPsName($userId);
        return view('backend.users.show', [
            'user' => $user,
            'areas' => $area_na_ps_name,
        ]);
    }

    /**
     * Edit user data
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, UserWiseAreaMapping $areaMapping)
    {
        $newArea = NewArea::all();
        $pA = ProvincialAssembly::all();
        $nA = NationalAssembly::all();
        $userId = $user->id;
        return view('backend.users.edit', [
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get(),
            'newArea' => $newArea,
            'pA' => $pA,
            'nA' => $nA,
            'area_mappings' => $areaMapping->where('user_id', $userId)->first()
        ]);
    }

    /**
     * Update user data
     *
     * @param User $user
     * @param UpdateUserRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, UserWiseAreaMapping $userWiseAreaMapping, UpdateUserRequest $request)
    {

        $password = $request->input('password');
        $postUserData = $request->validated();

        $updateUserData = [
            "name" => $postUserData['name'],
            "email" => $postUserData['email'],
            "username" => $postUserData['username'],
            "password" => $postUserData['password'],
            "confirm_password" => $postUserData['confirm_password']
        ];

        $updateAreaData = [
            'national_assembly_id' => $postUserData['national_assembly_id'] ? $postUserData['national_assembly_id'] : 0,
            'provincial_assembly_id' => $postUserData['provincial_assembly_id'] ? $postUserData['provincial_assembly_id'] : 0
        ];


        if (empty($password) && is_null($password)) {
            unset($postUserData['password']);
        }

        //remove confirm password
        unset($postUserData['confirm_password']);

        $user->update($updateUserData);
        if (!empty($updateAreaData)) {
            $userWiseAreaMapping::where(['user_id' => $user->id])->update($updateAreaData);
        }
        $roleId = (int) $request->get('role');
        $roleIdArray = array($roleId);
        $user->syncRoles($roleIdArray);

        //if record exists delete if not exists delete
        $role = Role::where('id', $roleId)->first();
        $exists = UserWiseAreaMapping::where('user_id', $user->id)->exists();

        if ($role->name == 'MNA' || $role->name == 'MPA') {
            //id not exist add record in user wise area
            if (!$exists) {
                $userWiseAreaMappingObject = new UserWiseAreaMapping;
                $newArea = $request->input('new_area_id');
                $pS = $request->input('provincial_assembly_id');
                $nA = $request->input('national_assembly_id');

                $userWiseData = [
                    'new_area_id' => 0,
                    'provincial_assembly_id' => $pS ? $pS : 0,
                    'national_assembly_id' => $nA ? $nA : 0,
                    'user_id' => $user->id,
                ];
                //create
                $userWiseAreaMappingObject->insert($userWiseData);
            }
        } else {
            if ($exists) {
                //delete record in user wise area
                $userWiseAreaMappingObject = new UserWiseAreaMapping;
                $userWiseAreaMappingObject->where('user_id', $user->id)->delete();
            }
        }

        return redirect()->route('users.index')
            ->withSuccess(__('User updated successfully.'));
    }

    /**
     * Delete user data
     *
     * @param User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $userWiseAreaMappingObject = new UserWiseAreaMapping;
        $userWiseAreaMappingObject->where('user_id', $user->id)->delete();
        $user->delete();

        return redirect()->route('users.index')
            ->withSuccess(__('User deleted successfully.'));
    }
}


/*

git clone https://github.com/codeanddeploy/laravel8-authentication-example.git

if your using my previous tutorial navigate your project folder and run composer update



install packages

composer require spatie/laravel-permission
composer require laravelcollective/html

then run php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"

php artisan migrate

php artisan make:migration create_posts_table

php artisan migrate

models
php artisan make:model Post

middleware
- create custom middleware
php artisan make:middleware PermissionMiddleware

register middleware
-

routes

controllers

- php artisan make:controller UsersController
- php artisan make:controller PostsController
- php artisan make:controller RolesController
- php artisan make:controller PermissionsController

requests
- php artisan make:request StoreUserRequest
- php artisan make:request UpdateUserRequest

blade files

create command to lookup all routes
- php artisan make:command CreateRoutePermissionsCommand
- php artisan permission:create-permission-routes

seeder for default roles and create admin user
php artisan make:seeder CreateAdminUserSeeder
php artisan db:seed --class=CreateAdminUserSeeder



*/
