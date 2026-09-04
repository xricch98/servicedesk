<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'view_own_tickets',
            'create_ticket',
            'create_ticket_for_others',
            'view_department_tickets',
            'view_all_tickets',
            'assign_ticket',
            'change_ticket_status',
            'transfer_ticket',
            'reply_to_ticket',
            'manage_department_users',
            'manage_all_users',
            'manage_roles',
            'manage_departments',
            'manage_categories',
            'write_knowledge_base',
            'post_announcements',
            'view_department_reports',
            'view_all_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $roles = [
            'Requester' => [
                'view_own_tickets',
                'create_ticket',
            ],
            'Agent' => [
                'view_own_tickets',
                'create_ticket',
                'view_department_tickets',
                'change_ticket_status',
                'reply_to_ticket',
            ],
            'Department Admin' => [
                'view_own_tickets',
                'create_ticket',
                'create_ticket_for_others',
                'view_department_tickets',
                'assign_ticket',
                'change_ticket_status',
                'transfer_ticket',
                'reply_to_ticket',
                'manage_department_users',
                'view_department_reports',
            ],
            'Main Admin' => $permissions,
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
