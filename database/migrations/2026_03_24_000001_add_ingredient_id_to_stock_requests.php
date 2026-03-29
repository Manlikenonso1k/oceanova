<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('ingredient_id')->nullable()->after('item_name');
            $table->foreign('ingredient_id')->references('id')->on('ingredients')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('stock_requests', function (Blueprint $table) {
            $table->dropForeign(['ingredient_id']);
            $table->dropColumn('ingredient_id');
        });
    }
};
