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
        $this->call(MenuCatalogSeeder::class);
        $this->call(IngredientSeeder::class);

        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'general_order_person',
        ]);

        User::factory()->create([
            'name' => 'Procurement Officer',
            'email' => 'procurement@example.com',
            'role' => 'procurement_officer',
        ]);

        User::factory()->create([
            'name' => 'Kitchen Manager',
            'email' => 'kitchen@example.com',
            'role' => 'kitchen_manager',
        ]);
    }
}
