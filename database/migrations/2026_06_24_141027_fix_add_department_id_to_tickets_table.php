<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('tickets', 'department_id')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->foreignId('department_id')
                    ->nullable()
                    ->after('category')
                    ->constrained()
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('tickets', 'department_id')) {
            Schema::table('tickets', function (Blueprint $table) {
                $table->dropConstrainedForeignId('department_id');
            });
        }
    }
};