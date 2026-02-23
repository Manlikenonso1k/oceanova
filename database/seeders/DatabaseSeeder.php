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

        $generalOrderUser = User::query()->updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'role' => 'general_order_person',
                'password' => bcrypt('password'),
            ]
        );

        $procurementOfficer = User::query()->updateOrCreate(
            ['email' => 'procurement@example.com'],
            [
                'name' => 'Procurement Officer',
                'role' => 'procurement_officer',
                'password' => bcrypt('password'),
            ]
        );

        $kitchenManager = User::query()->updateOrCreate(
            ['email' => 'kitchen@example.com'],
            [
                'name' => 'Kitchen Manager',
                'role' => 'kitchen_manager',
                'password' => bcrypt('password'),
            ]
        );

        $barman = User::query()->updateOrCreate(
            ['email' => 'barman@example.com'],
            [
                'name' => 'Barman',
                'role' => 'barman',
                'password' => bcrypt('password'),
            ]
        );

        $this->syncSpatieRole($generalOrderUser, 'general_order_person');
        $this->syncSpatieRole($procurementOfficer, 'procurement_officer');
        $this->syncSpatieRole($kitchenManager, 'kitchen_manager');
        $this->syncSpatieRole($barman, 'barman');
    }

    private function syncSpatieRole(User $user, string $role): void
    {
        if (! class_exists('\\Spatie\\Permission\\Models\\Role')) {
            return;
        }

        if (! method_exists($user, 'assignRole')) {
            return;
        }

        $user->syncRoles([$role]);
    }
}
