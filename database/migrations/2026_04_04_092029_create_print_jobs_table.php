<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NOTE: The default Laravel queue/jobs table is 0001_01_01_000002_create_jobs_table.php
// This table is for PRINTING production jobs — renamed to avoid conflict
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('print_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // e.g. JOB-2026-0001
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->nullOnDelete();
            $table->foreignId('customer_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('assigned_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', [
                'queued', 'prepress', 'printing', 'finishing', 'quality_check', 'ready', 'delivered', 'cancelled'
            ])->default('queued');
            $table->date('target_delivery_date')->nullable();
            $table->date('actual_delivery_date')->nullable();
            $table->string('external_erp_id')->nullable()->index(); // Phase 8 ERP sync placeholder
            $table->text('internal_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('print_jobs');
    }
};
