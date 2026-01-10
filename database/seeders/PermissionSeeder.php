<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Standardized Permissions List
        $permissions = [
            // User Management
            'users.list',
            'users.create',
            'users.edit',
            'users.delete',

            // Role Management
            'roles.list',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Ticketing Module
            'tickets.list',
            'tickets.create',
            'tickets.view',
            'tickets.edit',
            'tickets.delete',
            'tickets.respond',

            // Meeting Scheduler
            'meetings.list',
            'meetings.create',
            'meetings.view',
            'meetings.edit',
            'meetings.delete',

            // Event Management
            'events.list',
            'events.create',
            'events.view',
            'events.edit',
            'events.delete',
            'events.attendance',

            // Office Management
            'offices.list',
            'offices.create',
            'offices.edit',
            'offices.delete',

            // Division Management
            'divisions.list',
            'divisions.create',
            'divisions.edit',
            'divisions.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission);
        }

        // Create or Update Roles
        $roleAdmin = Role::findOrCreate('Admin');
        $roleUser = Role::findOrCreate('User');

        // Admin gets everything
        $roleAdmin->syncPermissions(Permission::all());

        // Regular User Permissions
        $roleUser->syncPermissions([
            'tickets.list',
            'tickets.create',
            'tickets.view',
            'meetings.list',
            'meetings.create',
            'meetings.view',
            'events.list',
            'events.view',
        ]);
    }
}
