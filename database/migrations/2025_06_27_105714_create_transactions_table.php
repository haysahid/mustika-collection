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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('type_id')->constrained('transaction_types')->onDelete('cascade');
            $table->string('code')->unique()->index();
            $table->string('note')->nullable();
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('set null');
            $table->foreignId('shipping_method_id')->nullable()->constrained('shipping_methods')->onDelete('set null');
            $table->integer('province_id')->nullable()->index();
            $table->string('province_name')->nullable();
            $table->integer('city_id')->nullable()->index();
            $table->string('city_name')->nullable();
            $table->string('address')->nullable();
            $table->integer('shipping_cost')->nullable();
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
