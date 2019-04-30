<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'id' => 1,
            'name' => 'Administrador'
        ]);
        Role::create([
            'id' => 2,
            'name' => 'Secretaría'
        ]);
        User::create([
            'id' => 1,
            'role_id' => 1,
            'name' => 'Carlos andres',
            'email' => 'carlosaperez1997@gmail.com',
            'phone' => '3173806454',
            'password' => \Hash::make('123456789')
        ]);
        User::create([
            'id' => 2,
            'role_id' => 2,
            'name' => 'Liliana',
            'email' => 'liliana@gmail.com',
            'phone' => '3159478254',
            'password' => \Hash::make('123456789')
        ]);
    }
}
