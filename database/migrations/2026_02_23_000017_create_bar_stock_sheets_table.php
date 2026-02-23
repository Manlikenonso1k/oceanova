<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bar_stock_sheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ingredient_id')->constrained()->cascadeOnDelete();
            $table->date('period_start');
            $table->date('period_end');
            $table->decimal('opening_stock', 12, 3)->default(0);
            $table->decimal('added_stock', 12, 3)->default(0);
            $table->decimal('trans_in', 12, 3)->default(0);
            $table->decimal('trans_out', 12, 3)->default(0);
            $table->decimal('sales', 12, 3)->default(0);
            $table->decimal('total_stock', 12, 3)->default(0);
            $table->decimal('expected_closing', 12, 3)->default(0);
            $table->decimal('closing_stock', 12, 3)->default(0);
            $table->decimal('variance', 12, 3)->default(0);
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['ingredient_id', 'period_start', 'period_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bar_stock_sheets');
    }
};
