<?php

namespace Database\Seeders;

use App\Filament\Resources\Shield\RoleResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'access_log_viewer']);

        $roles = ['super_admin', 'admin', 'author', 'developer', 'user'];

        foreach ($roles as $role) {
            $guardName = $role === 'user' ? 'api' : 'web';
            $roleCreated = (new (RoleResource::getModel()))->create([
                'name' => $role,
                'guard_name' => $guardName,
            ]);

            if ($role === 'super_admin') {
                $roleCreated->givePermissionTo('access_log_viewer');
            }
        }
    }
}
