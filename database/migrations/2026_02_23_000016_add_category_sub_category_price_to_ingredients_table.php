<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->string('category')->default('Kitchen')->after('name');
            $table->string('sub_category')->nullable()->after('category');
            $table->decimal('price', 12, 2)->default(0)->after('min_stock_alert_level');
            $table->index(['category', 'sub_category']);
        });
    }

    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropIndex(['category', 'sub_category']);
            $table->dropColumn(['category', 'sub_category', 'price']);
        });
    }
};
