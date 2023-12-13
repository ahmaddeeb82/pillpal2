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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['in_preparation','sent','delivered'])->default('in_preparation');
            $table->double('total_price')->nullable();
            $table->boolean('payed')->default(false);
            $table->foreignId('user_id')->constrained();
            $table->foreignId('admin_id')->constrained()->cascadeOnDelete();
            $table->date('order_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
