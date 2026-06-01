<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dateTime('sla_started_at')->nullable()->after('status');
            $table->dateTime('sla_due_at')->nullable()->after('sla_started_at');
            $table->dateTime('sla_resolved_at')->nullable()->after('sla_due_at');
            $table->dateTime('sla_breached_at')->nullable()->after('sla_resolved_at');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'sla_started_at',
                'sla_due_at',
                'sla_resolved_at',
                'sla_breached_at',
            ]);
        });
    }
};
