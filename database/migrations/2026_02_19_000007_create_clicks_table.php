<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->string('link_name');
            $table->string('url', 2048);
            $table->timestamp('clicked_at')->useCurrent();
            $table->timestamps();

            $table->index(['clicked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};
