<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'table_number')) {
                $table->string('table_number')->nullable()->after('customer_name');
            }

            if (!Schema::hasColumn('orders', 'waiter_id')) {
                $table->foreignId('waiter_id')->nullable()->after('table_number')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('orders', 'total_price')) {
                $table->decimal('total_price', 12, 2)->default(0)->after('total');
            }

            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });

        DB::table('orders')
            ->whereIn('status', ['Pending', 'Preparing', 'Delivered', 'Cancelled'])
            ->update([
                'status' => DB::raw("CASE status
                    WHEN 'Pending' THEN 'pending'
                    WHEN 'Preparing' THEN 'cooking'
                    WHEN 'Delivered' THEN 'served'
                    WHEN 'Cancelled' THEN 'served'
                    ELSE status
                END"),
            ]);

        DB::table('orders')->update([
            'table_number' => DB::raw('COALESCE(table_number, customer_name)'),
            'total_price' => DB::raw('CASE WHEN total_price IS NULL OR total_price = 0 THEN COALESCE(total, 0) ELSE total_price END'),
        ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'notes')) {
                $table->dropColumn('notes');
            }

            if (Schema::hasColumn('orders', 'waiter_id')) {
                $table->dropConstrainedForeignId('waiter_id');
            }

            if (Schema::hasColumn('orders', 'table_number')) {
                $table->dropColumn('table_number');
            }

            if (Schema::hasColumn('orders', 'total_price')) {
                $table->dropColumn('total_price');
            }
        });
    }
};
