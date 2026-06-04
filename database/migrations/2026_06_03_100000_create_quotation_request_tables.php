<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('external_id', 50)->unique();
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'sent_to_erp',
                'erp_failed',
                'spam',
            ])->default('pending');
            $table->json('payload_json');
            $table->string('client_ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->text('admin_notes')->nullable();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('priority', ['normal', 'high', 'urgent'])->default('normal');
            $table->text('rejection_reason')->nullable();
            $table->unsignedBigInteger('erp_estimation_id')->nullable();
            $table->timestamp('erp_imported_at')->nullable();
            $table->text('erp_import_error')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
            $table->index('priority');
        });

        Schema::create('quotation_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_request_id')->constrained()->cascadeOnDelete();
            $table->string('original_name');
            $table->string('stored_path', 500);
            $table->string('mime', 100);
            $table->unsignedInteger('size_bytes');
            $table->timestamps();
        });

        Schema::create('quotation_request_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_request_id')->constrained()->cascadeOnDelete();
            $table->string('event', 50);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->json('meta_json')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotation_request_events');
        Schema::dropIfExists('quotation_attachments');
        Schema::dropIfExists('quotation_requests');
    }
};
