<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UserRequest;
use App\Mail\WelcomeUser;
use App\Mail\UserDataUpdated;
use Illuminate\Support\Facades\Mail;
use Exception;
use Log;

class UserController extends Controller
{
    /**
     * listing of the users.
     *
     * @return void
     */
    public function index()
    {
        return view('users.list');
    }

    /**
     * get all the users.
     *
     * @return void
     */
    public function getData()
    {
        $users = User::select(['id', 'first_name','last_name', 'email', 'created_at'])->latest();
        
        return DataTables::of($users)
            ->addColumn('action', function($user){
                return view('partials.user-action-buttons', compact('user'))->render();
            })->make(true);
    }

    /**
     * Creating users.
     *
     * @return void
     */
    public function create()
    {
        $roles = Role::where('name','!=','Superadmin')->get();
        return view('users.create', compact('roles'));
    }

    /**
     * storing newly created users.
     *
     * @param Request $request
     * @return void
     */
    public function store(UserRequest $request)
    {
        try {
            User::create([
                'first_name' => $request->first_name,'last_name'  => $request->last_name,
                'email'      => $request->email,'role_id' => $request->role_id,
                'password'   => Hash::make($request->password)
            ]);

            //sending mail to newly created users with creds.
            Mail::to($request->email)->send(new WelcomeUser($request->all(), $request->password));

        } catch (Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create user. Please try again later.');
        }

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }


    /**
     * Editing of user.
     *
     * @param string $id
     * @return void
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::where('name','!=','Superadmin')->get();
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param Request $request
     * @param string $id
     * @return void
     */
    public function update(UserRequest $request, string $id)
    {
        // Updating the user's data.
        try {
            $user = User::findOrFail($id);
            $user->update([
                'first_name' => $request->first_name,'last_name' => $request->last_name,
                'email' => $request->email,'role_id' => $request->role_id,
            ]);

            //sending mail to user with updated details. 
            $userDetails = $user->where('id',$id)->select('first_name','last_name','email')->first()->toArray();
            Mail::to($user->email)->send(new UserDataUpdated($userDetails));
        
        } catch (Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update user. Please try again later.');
        }

        return redirect()->route('users.edit', $user->id)->with('success', 'User updated successfully.');

    }

    /**
     * Deleting user.
     *
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        // Delete the user.
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

}
