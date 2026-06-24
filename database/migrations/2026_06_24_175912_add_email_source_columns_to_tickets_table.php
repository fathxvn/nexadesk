<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('source')->default('web')->after('status');

            $table->string('email_from')->nullable()->after('source');
            $table->string('email_from_name')->nullable()->after('email_from');
            $table->string('email_subject')->nullable()->after('email_from_name');
            $table->longText('email_body')->nullable()->after('email_subject');

            $table->string('email_message_id')->nullable()->unique()->after('email_body');
            $table->timestamp('email_received_at')->nullable()->after('email_message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropUnique(['email_message_id']);

            $table->dropColumn([
                'source',
                'email_source',
                'email_from_name',
                'email_subject',
                'email_body',
                'email_message_id',
                'email_received_at',
            ]);
        });
    }
};
