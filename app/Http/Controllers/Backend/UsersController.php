<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Backend\StoreUserRequest;
use App\Http\Requests\Backend\UpdateUserRequest;
use App\Helpers\Helper;
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

        $data = [
            'roles' => $roles,
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

        $user->create($postUserData);

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
        return view('backend.users.show', [
            'user' => $user
        ]);
    }

    /**
     * Edit user data
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) 
    {
        return view('backend.users.edit', [
            'user' => $user,
            'userRole' => $user->roles->pluck('name')->toArray(),
            'roles' => Role::latest()->get()
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
    public function update(User $user, UpdateUserRequest $request) 
    {
        
        $password = $request->input('password');
        $postUserData = $request->validated();

        if(empty($password) && is_null($password)){
            unset($postUserData['password']);
        }
        
        //remove confirm password
        unset($postUserData['confirm_password']);
        
        $user->update($postUserData);

        $roleId = (int) $request->get('role');
        $roleIdArray = array($roleId);
        $user->syncRoles($roleIdArray);

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
        $user->delete();

        return redirect()->route('users.index')
            ->withSuccess(__('User deleted successfully.'));
    }
}