<?php

use App\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UsersRolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'superadmin',
            'admin',
            'user'
        ];

        foreach ($roles as $role) {
            if (Role::where('name', $role) === null) {
                Role::create(['name' => $role]);
            }
        }

        $users = [
            [
                'name'=>'Jeremy Kavuncuoglu',
                'email' => 'jeremy@skaraudio.com',
                'email_verified_at' => now(),
                'password' => Hash::make('#Jeremy123!'),
                'access_token' => User::createToken(32),
                'remember_token' => User::createToken(),
                'roles' => ['superadmin', 'admin', 'user']
            ],
            [
                'name'=>'Kevin Schlenker',
                'email' => 'kevin@skaraudio.com',
                'email_verified_at' => now(),
                'password' => Hash::make('#Kevin123!'),
                'access_token' => User::createToken(32),
                'remember_token' => User::createToken(),
                'roles' => ['admin', 'user']
            ],
            [
                'name'=>'Matthew Kimball',
                'email' => 'matt@skaraudio.com',
                'email_verified_at' => now(),
                'password' => Hash::make('#Matt123!'),
                'access_token' => User::createToken(32),
                'remember_token' => User::createToken(),
                'roles' => ['user']
            ]
        ];

        foreach ($users as $user) {
            $roles = $user['roles'];
            unset($user['roles']);
            $user = User::create($user);
            $user->hasRole($roles);
        }
    }
}
