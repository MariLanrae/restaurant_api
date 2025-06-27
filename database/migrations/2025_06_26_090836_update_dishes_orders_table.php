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
        Schema::rename('dishes_orders', 'dish_order');
        Schema::table('dish_order', function (Blueprint $table) {
            $table->renameColumn('dishes_id', 'dish_id');
            $table->foreign('dish_id')->references('id')->on('dishes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('dishes_orders', function (Blueprint $table) {
            $table->renameColumn('dish_id', 'dishes_id');
            $table->dropForeign(['dish_id']);
        });

        Schema::rename('dish_order', 'dishes_orders');

    }
};
