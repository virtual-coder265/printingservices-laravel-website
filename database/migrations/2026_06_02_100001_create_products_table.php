<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->text('note')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('vat_rate', 5, 2)->default(17.50);
            $table->enum('stock_status', ['in_stock', 'made_to_order', 'out_of_stock'])->default('in_stock');
            $table->string('thumbnail')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->string('external_erp_id')->nullable()->index();
            $table->timestamp('erp_synced_at')->nullable();
            $table->string('fulfillment_type')->default('standard');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
