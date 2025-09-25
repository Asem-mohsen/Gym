<?php

namespace Database\Seeders;

use App\Services\PermissionAssignmentService;
use Illuminate\Database\Seeder;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionService = app(PermissionAssignmentService::class);
        
        $permissionService->createAllPermissions();
        $permissionService->createAllRoles();
    }
}
