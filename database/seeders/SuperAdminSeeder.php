<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Creating default supradmin.
     *
     * @return void
     */
    public function run(): void
    {
        // Create the SuperAdmin role
        $role = Role::firstOrCreate(['name' => 'Superadmin']);
        // Create a user and assign the SuperAdmin role
        $user = User::firstOrCreate(
            ['email' => 'superadmin@neosoftmail.com'], 
            [
                'first_name' => 'Super',
                'last_name' => 'Admin',
                'role_id'=> $role->id,
                'password' => Hash::make('VKX-mp~vkoOCMRPC'),
            ]
        );

        // Output success message
        $this->command->info('SuperAdmin user created successfully with SuperAdmin role.');
    
    }
}
