<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->string('unit', 50)->default('pcs')->change();
            $table->decimal('weight', 12, 3)->default(0)->after('unit');
        });
    }

    public function down(): void
    {
        Schema::table('ingredients', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->enum('unit', ['kg', 'gram', 'pcs', 'liter'])->default('pcs')->change();
        });
    }
};
