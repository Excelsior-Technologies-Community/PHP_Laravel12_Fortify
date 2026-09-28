<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions', 'users')->get();
        $permissions = Permission::all();
        $users = User::with('roles')->get();

        return view('security.roles', compact('roles', 'permissions', 'users'));
    }

    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name|max:50',
            'label' => 'nullable|string|max:100',
            'permissions' => 'nullable|array',
        ]);

        $role = Role::create([
            'name' => strtolower(str_replace(' ', '-', trim($request->name))),
            'label' => $request->label ?? ucfirst($request->name),
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->back()->with('success', "Role '{$role->name}' created successfully.");
    }

    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->roles()->sync([$request->role_id]);

        return redirect()->back()->with('success', "Role updated for user {$user->name}.");
    }
}
