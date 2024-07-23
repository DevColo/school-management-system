<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // user roles
        $superadminRole = Role::create(['name' => 'superadmin']);
        $adminRole = Role::create(['name' => 'admin']);
        $studentRole = Role::create(['name' => 'student']);
        $librarianRole = Role::create(['name' => 'librarian']);
        $guestRole = Role::create(['name' => 'guest']);

        /*
        * user roles permissions
        

        // users operations
        Permission::create(['name' => 'create-users']);
        Permission::create(['name' => 'edit-users']);
        Permission::create(['name' => 'delete-users']);
        Permission::create(['name' => 'view-users']);

        // roles operations
        Permission::create(['name' => 'create-users-roles']);
        Permission::create(['name' => 'edit-users-roles']);
        Permission::create(['name' => 'delete-users-roles']);
        Permission::create(['name' => 'drop-users-roles']);
        Permission::create(['name' => 'assign-users-roles']);

        // permission operations privilege
        Permission::create(['name' => 'create-roles-permissions']);
        Permission::create(['name' => 'edit-roles-permissions']);
        Permission::create(['name' => 'delete-roles-permissions']);
        Permission::create(['name' => 'drop-roles-permissions']);
        Permission::create(['name' => 'assign-users-roles-permissions']);

        // category oparations
        Permission::create(['name' => 'create-category']);
        Permission::create(['name' => 'edit-category']);
        Permission::create(['name' => 'delete-category']);
        Permission::create(['name' => 'drop-category']);
        Permission::create(['name' => 'view-category']);

        // sub category operations
        Permission::create(['name' => 'create-sub-category']);
        Permission::create(['name' => 'edit-sub-category']);
        Permission::create(['name' => 'delete-sub-category']);
        Permission::create(['name' => 'drop-sub-category']);
        Permission::create(['name' => 'view-sub-category']);

        // brand operations
        Permission::create(['name' => 'create-brand']);
        Permission::create(['name' => 'edit-brand']);
        Permission::create(['name' => 'delete-brand']);
        Permission::create(['name' => 'drop-brand']);
        Permission::create(['name' => 'view-brand']);

        // product operations
        Permission::create(['name' => 'create-product']);
        Permission::create(['name' => 'edit-product']);
        Permission::create(['name' => 'delete-product']);
        Permission::create(['name' => 'drop-product']);
        Permission::create(['name' => 'view-product']);

        // sales operations
        Permission::create(['name' => 'create-sales']);
        Permission::create(['name' => 'edit-sales']);
        Permission::create(['name' => 'delete-sales']);
        Permission::create(['name' => 'drop-sales']);
        Permission::create(['name' => 'view-sales']);

        // customer operations
        Permission::create(['name' => 'create-customers']);
        Permission::create(['name' => 'edit-customers']);
        Permission::create(['name' => 'delete-customers']);
        Permission::create(['name' => 'drop-customers']);
        Permission::create(['name' => 'view-customers']);

        // reports
        Permission::create(['name' => 'sales-report']);
        Permission::create(['name' => 'customers-report']);
        Permission::create(['name' => 'products-report']);

        // message 
        Permission::create(['name' => 'message-customers']);
        Permission::create(['name' => 'message-users']);

        // system settings
        Permission::create(['name' => 'general-settings']);
        Permission::create(['name' => 'email-settings']);
        Permission::create(['name' => 'rate-settings']);
        Permission::create(['name' => 'currency-settings']);
        Permission::create(['name' => 'theme-settings']);

        // default roles permissions assignment
        $superadminRole->givePermissionTo([
            'create-users',
            'edit-users',
            'delete-users',
            'view-users',

            // roles operations
            'create-users-roles',
            'edit-users-roles',
            'delete-users-roles',
            'drop-users-roles',
            'assign-users-roles',

            // permission operations privilege
            'create-roles-permissions',
            'edit-roles-permissions',
            'delete-roles-permissions',
            'drop-roles-permissions',
            'assign-users-roles-permissions',

            // category oparations
            'create-category',
            'edit-category',
            'delete-category',
            'drop-category',
            'view-category',

            // sub category operations
            'create-sub-category',
            'edit-sub-category',
            'delete-sub-category',
            'drop-sub-category',
            'view-sub-category',

            // brand operations
            'create-brand',
            'edit-brand',
            'delete-brand',
            'drop-brand',
            'view-brand',

            // product operations
            'create-product',
            'edit-product',
            'delete-product',
            'drop-product',
            'view-product',

            // sales operations
            'create-sales',
            'edit-sales',
            'delete-sales',
            'drop-sales',
            'view-sales',

            // customer operations
            'create-customers',
            'edit-customers',
            'delete-customers',
            'drop-customers',
            'view-customers',

            // reports
            'sales-report',
            'customers-report',
            'products-report',

            // message 
            'message-customers',
            'message-users',

            // system settings
            'general-settings',
            'email-settings',
            'rate-settings',
            'currency-settings',
            'theme-settings',
        ]);

        $managerRole->givePermissionTo([
            // user
            'view-users',

            // category oparations
            'create-category',
            'edit-category',
            'delete-category',
            'drop-category',
            'view-category',

            // sub category operations
            'create-sub-category',
            'edit-sub-category',
            'delete-sub-category',
            'drop-sub-category',
            'view-sub-category',

            // brand operations
            'create-brand',
            'edit-brand',
            'delete-brand',
            'drop-brand',
            'view-brand',

            // product operations
            'create-product',
            'edit-product',
            'delete-product',
            'drop-product',
            'view-product',

            // sales operations
            'create-sales',
            'edit-sales',
            'delete-sales',
            'drop-sales',
            'view-sales',

            // customer operations
            'create-customers',
            'edit-customers',
            'delete-customers',
            'drop-customers',
            'view-customers',

            // reports
            'sales-report',
            'customers-report',
            'products-report',

            // message 
            'message-customers',
            'message-users',

            // system settings
            'general-settings',
            'email-settings',
            'rate-settings',
            'currency-settings',
            'email-settings',
            'theme-settings',
        ]);

        $sales_personRole->givePermissionTo([
            // sales operations
            'create-sales',
            'edit-sales',
            'drop-sales',
            'view-sales',

            // customer operations
            'view-customers',

            // system settings
            'theme-settings',
        ]);

        $product_creatorRole->givePermissionTo([
            // category oparations
            'create-category',
            'edit-category',
            'drop-category',
            'view-category',

            // sub category operations
            'create-sub-category',
            'edit-sub-category',
            'drop-sub-category',
            'view-sub-category',

            // brand operations
            'create-brand',
            'edit-brand',
            'drop-brand',
            'view-brand',

            // product operations
            'create-product',
            'edit-product',
            'drop-product',
            'view-product',

            // system settings
            'theme-settings',
        ]);
        */

    }
}
