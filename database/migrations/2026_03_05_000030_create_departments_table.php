<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code')->unique();
            $table->boolean('is_main')->default(false);
            $table->timestamps();
        });

        DB::table('departments')->insert([
            ['name' => 'Main Store', 'code' => 'MAIN_STORE', 'is_main' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kitchen', 'code' => 'KITCHEN', 'is_main' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bar', 'code' => 'BAR', 'is_main' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Morithos', 'code' => 'MORITHOS', 'is_main' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oceanova', 'code' => 'OCEANOVA', 'is_main' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cleaning', 'code' => 'CLEANING', 'is_main' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
