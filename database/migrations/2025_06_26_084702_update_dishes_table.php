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
        Schema::table('dishes', function (Blueprint $table) {
            $table->renameColumn('files_id', 'file_id');
            $table->dropForeign(['files_id']);
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->decimal('calories', 6, 2)->change();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dishes', function (Blueprint $table) {
            $table->renameColumn('file_id', 'files_id');
            $table->dropForeign(['file_id']);
            $table->foreign('files_id')->references('id')->on('files');
            $table->integer('calories')->change();
        });
    }
};
