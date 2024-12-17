<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Check if superadmin user already exists
        $existingUser = User::where('email', 'superadmin@sukna.sa')->first();

        if (!$existingUser) {
            // Create superadmin user
            $user = User::create([
                'id' => 1,
                'username' => 'superadmin',
                'firstname' => 'Super',
                'lastname' => 'Admin',
                'phone' => '0500000000',
                'email' => 'superadmin@sukna.sa',
                'email_verified_at' => now(),
                'password' => Hash::make('superadmin'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $role = Role::create(['name' => 'superadmin']);
            $user->assignRole($role);

            Artisan::call('shield:generate', ['--panel' => 'admin', '--no-interaction' => true]);
            Artisan::call('shield:super-admin', ['--user' => $user->id, '--no-interaction' => true]);

        }
    }
}
