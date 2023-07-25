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
        Schema::table('trainers_topics', function (Blueprint $table) {
            $table->foreignId('trainer_id')->constrained()
            ->cascadeOnUpdate()
            ->noActionOnDelete();
            $table->foreignId('topic_id')->constrained()
            ->cascadeOnUpdate()
            ->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainers_topics', function (Blueprint $table) {
            $table->dropForeign(['trainer_id']);
            $table->dropForeign(['topic_id']);
        });
    }
};
