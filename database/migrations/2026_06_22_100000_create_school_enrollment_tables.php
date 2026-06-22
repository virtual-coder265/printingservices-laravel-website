<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('opens_at');
            $table->dateTime('closes_at');
            $table->unsignedInteger('max_placements')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            $table->index(['is_published', 'opens_at', 'closes_at']);
        });

        Schema::create('student_enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('external_id', 50)->unique();
            $table->enum('type', ['interest', 'enrollment'])->default('interest');
            $table->foreignId('registration_period_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', [
                'pending',
                'reviewed',
                'accepted',
                'rejected',
                'waitlisted',
                'withdrawn',
            ])->default('pending');
            $table->json('payload_json');
            $table->string('client_ip', 45)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->text('admin_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('type');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_enrollments');
        Schema::dropIfExists('registration_periods');
    }
};
