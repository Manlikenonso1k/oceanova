<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'subtotal')) {
                $table->decimal('subtotal', 12, 2)->default(0)->after('unit_price');
            }
        });

        DB::table('order_items')->update([
            'subtotal' => DB::raw('CASE WHEN subtotal IS NULL OR subtotal = 0 THEN COALESCE(total, unit_price * quantity, 0) ELSE subtotal END'),
        ]);
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            if (Schema::hasColumn('order_items', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
        });
    }
};
