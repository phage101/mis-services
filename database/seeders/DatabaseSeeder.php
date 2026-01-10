<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $this->call(RequestTypeCategorySeeder::class);

        // create permissions
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'ticket-list',
            'ticket-create',
            'ticket-view',
            'ticket-edit',
            'ticket-delete',
            'ticket-respond'
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::create(['name' => $permission]);
        }

        // create roles and assign permissions
        $roleAdmin = \Spatie\Permission\Models\Role::create(['name' => 'Admin']);
        $roleAdmin->givePermissionTo(\Spatie\Permission\Models\Permission::all());

        $roleUser = \Spatie\Permission\Models\Role::create(['name' => 'User']);
        $roleUser->givePermissionTo([
            'ticket-list',
            'ticket-create',
            'ticket-view'
        ]);

        // create admin user
        $admin = \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        $admin->assignRole($roleAdmin);

        // create regular user
        $user = \App\Models\User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);
        $user->assignRole($roleUser);
    }
}
