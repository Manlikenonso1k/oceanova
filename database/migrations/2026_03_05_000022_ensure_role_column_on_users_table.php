<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->default('steward')->after('password');
            });
        }
    }

    public function down(): void
    {
        // Intentionally left empty to avoid dropping role column in existing deployments.
    }
};
