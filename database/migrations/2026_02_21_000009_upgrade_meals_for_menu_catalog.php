<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            try {
                $table->dropUnique('meals_name_unique');
            } catch (Throwable $e) {
            }

            $table->foreignId('menu_section_id')->nullable()->after('id')->constrained('menu_sections')->nullOnDelete();
            $table->text('description')->nullable()->after('price');
            $table->json('tags')->nullable()->after('image');
            $table->string('slug')->nullable()->after('name');
            $table->unsignedInteger('sort_order')->default(0)->after('category');
            $table->boolean('is_active')->default(true)->after('sort_order');
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropForeign(['menu_section_id']);
            $table->dropColumn([
                'menu_section_id',
                'description',
                'tags',
                'slug',
                'sort_order',
                'is_active',
            ]);

            try {
                $table->dropUnique('meals_slug_unique');
            } catch (Throwable $e) {
            }

            $table->unique('name');
        });
    }
};
