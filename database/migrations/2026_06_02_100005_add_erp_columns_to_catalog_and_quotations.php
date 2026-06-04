<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('requires_quotation')->default(true)->after('is_featured');
            $table->string('external_erp_id')->nullable()->index()->after('requires_quotation');
            $table->timestamp('erp_synced_at')->nullable()->after('external_erp_id');
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->string('external_erp_id')->nullable()->index()->after('expires_at');
            $table->enum('erp_sync_status', ['pending', 'synced', 'failed'])->nullable()->after('external_erp_id');
            $table->timestamp('erp_synced_at')->nullable()->after('erp_sync_status');
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['requires_quotation', 'external_erp_id', 'erp_synced_at']);
        });

        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['external_erp_id', 'erp_sync_status', 'erp_synced_at']);
        });
    }
};
