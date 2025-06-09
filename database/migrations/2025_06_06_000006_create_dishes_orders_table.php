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
        Schema::create('dishes_orders', function (Blueprint $table) {
            $table->unsignedBigInteger('dishes_id');
            $table->foreign('dishes_id')->references('id')->on('dishes')->onDelete('set null');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->integer('quantity');
            #$table->foreignId('order_id')->onDelete('cascade');
            #$table->foreignId('dishes_id')->onDelete('set null');
            #$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes_orders');
    }
};
