<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->decimal('price_ngn', 14, 2)->default(0);
            $table->decimal('opening_stock', 12, 3)->default(0);
            $table->decimal('added_stock', 12, 3)->default(0);
            $table->decimal('trans_in', 12, 3)->default(0);
            $table->decimal('trans_out', 12, 3)->default(0);
            $table->decimal('total_stock', 12, 3)->default(0);
            $table->decimal('sales', 12, 3)->default(0);
            $table->decimal('closing_stock', 12, 3)->default(0);
            $table->text('remarks')->nullable();
            $table->enum('category', ['Bar', 'Kitchen']);
            $table->date('stock_date');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['category', 'stock_date']);
            $table->index(['item_name', 'stock_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_stocks');
    }
};
