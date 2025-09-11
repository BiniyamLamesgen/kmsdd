<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Employee;

class RolePermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:manage roles')->except(['getUserRoles', 'getUserPermissions']);
        $this->middleware('permission:manage permissions')->only(['permissions', 'givePermissionToRole', 'revokePermissionFromRole']);
    }

    public function roles()
    {
        $roles = Role::with('permissions')->get();
        return response()->json(['roles' => $roles]);
    }

    public function permissions()
    {
        $permissions = Permission::all();
        return response()->json(['permissions' => $permissions]);
    }

    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array|exists:permissions,name'
        ]);

        $role = Role::create(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->givePermissionTo($request->permissions);
        }

        return response()->json(['role' => $role->load('permissions')]);
    }

    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array|exists:permissions,name'
        ]);

        $role->update(['name' => $request->name]);
        
        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return response()->json(['role' => $role->load('permissions')]);
    }

    public function deleteRole(Role $role)
    {
        if ($role->name === 'Super Admin') {
            return response()->json(['error' => 'Cannot delete Super Admin role'], 403);
        }

        $role->delete();
        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function assignRole(Request $request, Employee $employee)
    {
        $request->validate([
            'roles' => 'required|array|exists:roles,name'
        ]);

        $employee->syncRoles($request->roles);
        
        return response()->json([
            'message' => 'Roles assigned successfully',
            'employee' => $employee->load('roles')
        ]);
    }

    public function removeRole(Request $request, Employee $employee)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $employee->removeRole($request->role);
        
        return response()->json([
            'message' => 'Role removed successfully',
            'employee' => $employee->load('roles')
        ]);
    }

    public function givePermissionToRole(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'required|array|exists:permissions,name'
        ]);

        $role->givePermissionTo($request->permissions);
        
        return response()->json([
            'message' => 'Permissions granted successfully',
            'role' => $role->load('permissions')
        ]);
    }

    public function revokePermissionFromRole(Request $request, Role $role)
    {
        $request->validate([
            'permission' => 'required|string|exists:permissions,name'
        ]);

        $role->revokePermissionTo($request->permission);
        
        return response()->json([
            'message' => 'Permission revoked successfully',
            'role' => $role->load('permissions')
        ]);
    }

    public function getUserRoles(Employee $employee)
    {
        return response()->json([
            'employee' => $employee->only(['id', 'first_name', 'last_name', 'email']),
            'roles' => $employee->roles,
            'permissions' => $employee->getAllPermissions()
        ]);
    }

    public function getUserPermissions(Employee $employee)
    {
        return response()->json([
            'direct_permissions' => $employee->getDirectPermissions(),
            'role_permissions' => $employee->getPermissionsViaRoles(),
            'all_permissions' => $employee->getAllPermissions()
        ]);
    }
}
