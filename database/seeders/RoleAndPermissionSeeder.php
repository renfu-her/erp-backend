<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 重置角色和權限的快取
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 建立權限
        // 員工管理權限
        Permission::create(['name' => 'view employees']);
        Permission::create(['name' => 'create employees']);
        Permission::create(['name' => 'edit employees']);
        Permission::create(['name' => 'delete employees']);

        // 部門管理權限
        Permission::create(['name' => 'view departments']);
        Permission::create(['name' => 'create departments']);
        Permission::create(['name' => 'edit departments']);
        Permission::create(['name' => 'delete departments']);

        // 出勤管理權限
        Permission::create(['name' => 'view attendances']);
        Permission::create(['name' => 'create attendances']);
        Permission::create(['name' => 'edit attendances']);
        Permission::create(['name' => 'delete attendances']);

        // 請假管理權限
        Permission::create(['name' => 'view leaves']);
        Permission::create(['name' => 'create leaves']);
        Permission::create(['name' => 'edit leaves']);
        Permission::create(['name' => 'delete leaves']);
        Permission::create(['name' => 'approve leaves']);

        // 薪資管理權限
        Permission::create(['name' => 'view payrolls']);
        Permission::create(['name' => 'create payrolls']);
        Permission::create(['name' => 'edit payrolls']);
        Permission::create(['name' => 'delete payrolls']);

        // 建立角色
        $admin = Role::create(['name' => 'admin']);
        $hr = Role::create(['name' => 'hr']);
        $manager = Role::create(['name' => 'manager']);
        $employee = Role::create(['name' => 'employee']);

        // 分配權限給角色
        $admin->givePermissionTo(Permission::all());

        $hr->givePermissionTo([
            'view employees', 'create employees', 'edit employees',
            'view departments', 'create departments', 'edit departments',
            'view attendances', 'create attendances', 'edit attendances',
            'view leaves', 'create leaves', 'edit leaves', 'approve leaves',
            'view payrolls', 'create payrolls', 'edit payrolls',
        ]);

        $manager->givePermissionTo([
            'view employees',
            'view departments',
            'view attendances',
            'view leaves', 'approve leaves',
            'view payrolls',
        ]);

        $employee->givePermissionTo([
            'view employees',
            'view departments',
            'view attendances',
            'view leaves', 'create leaves',
            'view payrolls',
        ]);
    }
}
