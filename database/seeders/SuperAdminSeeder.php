<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
        'profile_image' => 'avatar.jpg',
        'email' => 'tg6471jr@gmail.com',
        'user_name' => 'tg6471jr@gmail.com',
        'password' => '$2y$10$qqmHagMGv1mWMQNIqE9zCuaRqD2BNmgstSoCxnVjM5Xkhjz6qSc/q',
        'status' => '1'
        ]);

        // $role = Role::create(['name' => 'admin']);

        // $permissions = Permission::pluck('id', 'id')->all();


        // $role->syncPermissions($permissions);

        $user->assignRole('superadmin');
    }
}
