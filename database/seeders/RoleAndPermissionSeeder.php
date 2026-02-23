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

        $roleClass::findOrCreate('barman');
    }
}
