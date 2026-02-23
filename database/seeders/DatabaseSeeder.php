<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);
        $this->call(MenuCatalogSeeder::class);
        $this->call(IngredientSeeder::class);
        $this->call(BarInventorySeeder::class);

        // User::factory(10)->create();

        User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'role' => 'general_order_person',
                'password' => bcrypt('password'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'procurement@example.com'],
            [
                'name' => 'Procurement Officer',
                'role' => 'procurement_officer',
                'password' => bcrypt('password'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'kitchen@example.com'],
            [
                'name' => 'Kitchen Manager',
                'role' => 'kitchen_manager',
                'password' => bcrypt('password'),
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'barman@example.com'],
            [
                'name' => 'Barman',
                'role' => 'barman',
                'password' => bcrypt('password'),
            ]
        );
    }
}
