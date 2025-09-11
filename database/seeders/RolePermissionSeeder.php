<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions for both guards
        $permissions = [
            // Employee permissions
            'view employees',
            'create employees',
            'edit employees',
            'delete employees',
            'manage employee profiles',
            
            // Skills permissions
            'view skills',
            'create skills',
            'edit skills',
            'delete skills',
            'endorse skills',
            
            // Experience permissions
            'view experiences',
            'create experiences',
            'edit experiences',
            'delete experiences',
            
            // Project permissions
            'view projects',
            'create projects',
            'edit projects',
            'delete projects',
            
            // Achievement permissions
            'view achievements',
            'create achievements',
            'edit achievements',
            'delete achievements',
            
            // Certification permissions
            'view certifications',
            'create certifications',
            'edit certifications',
            'delete certifications',
            
            // Education permissions
            'view education',
            'create education',
            'edit education',
            'delete education',
            
            // Knowledge Sharing permissions
            'view knowledge sharing',
            'create knowledge sharing',
            'edit knowledge sharing',
            'delete knowledge sharing',
            
            // Endorsement permissions
            'view endorsements',
            'create endorsements',
            'edit endorsements',
            'delete endorsements',
            
            // Reports and Analytics
            'view reports',
            'view analytics',
            'export data',
            
            // System Administration
            'manage roles',
            'manage permissions',
            'system settings',
        ];

        // Create permissions for both guards
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'employee']);
        }

        // Create roles for web guard (Users)
        $this->createWebGuardRoles();
        
        // Create roles for employee guard (Employees)
        $this->createEmployeeGuardRoles();
    }

    private function createWebGuardRoles(): void
    {
        $webPermissions = Permission::where('guard_name', 'web')->get();

        $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $adminRole->givePermissionTo($webPermissions);

        $hrRole = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
        $hrRole->givePermissionTo($webPermissions->whereIn('name', [
            'view employees', 'create employees', 'edit employees', 'manage employee profiles',
            'view skills', 'view experiences', 'view projects', 'view achievements',
            'view certifications', 'view education', 'view knowledge sharing',
            'view reports', 'view analytics', 'export data'
        ]));

        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $managerRole->givePermissionTo($webPermissions->whereIn('name', [
            'view employees', 'view skills', 'endorse skills',
            'view experiences', 'view projects', 'view achievements',
            'view certifications', 'view education', 'view knowledge sharing',
            'create achievements', 'edit achievements'
        ]));
    }

    private function createEmployeeGuardRoles(): void
    {
        $employeePermissions = Permission::where('guard_name', 'employee')->get();

        $adminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'employee']);
        $adminRole->givePermissionTo($employeePermissions);

        $hrRole = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'employee']);
        $hrRole->givePermissionTo($employeePermissions->whereIn('name', [
            'view employees', 'create employees', 'edit employees', 'manage employee profiles',
            'view skills', 'view experiences', 'view projects', 'view achievements',
            'view certifications', 'view education', 'view knowledge sharing',
            'view reports', 'view analytics', 'export data'
        ]));

        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'employee']);
        $managerRole->givePermissionTo($employeePermissions->whereIn('name', [
            'view employees', 'view skills', 'endorse skills',
            'view experiences', 'view projects', 'view achievements',
            'view certifications', 'view education', 'view knowledge sharing',
            'create achievements', 'edit achievements'
        ]));

        $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'employee']);
        $employeeRole->givePermissionTo($employeePermissions->whereIn('name', [
            'view employees', 'view skills', 'endorse skills',
            'create experiences', 'edit experiences', 'delete experiences',
            'create projects', 'edit projects', 'delete projects',
            'create certifications', 'edit certifications', 'delete certifications',
            'create education', 'edit education', 'delete education',
            'create knowledge sharing', 'edit knowledge sharing', 'delete knowledge sharing',
            'create endorsements'
        ]));

        $viewerRole = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'employee']);
        $viewerRole->givePermissionTo($employeePermissions->whereIn('name', [
            'view employees', 'view skills', 'view experiences',
            'view projects', 'view achievements', 'view certifications',
            'view education', 'view knowledge sharing'
        ]));
    }
}
