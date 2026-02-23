<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roleClass = '\\Spatie\\Permission\\Models\\Role';

        if (! class_exists($roleClass)) {
            return;
        }

        foreach ([
            'super_admin',
            'admin',
            'procurement_officer',
            'kitchen_manager',
            'general_order_person',
            'barman',
        ] as $role) {
            $roleClass::findOrCreate($role);
        }
    }
}
