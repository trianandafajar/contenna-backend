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
        Schema::create('update_log_descriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('log_id');
            $table->text('description');
            $table->timestamps();

            $table->foreign('log_id')->references('id')->on('update_logs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('update_log_descriptions');
    }
};
