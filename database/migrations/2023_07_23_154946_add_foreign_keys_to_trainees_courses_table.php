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
        Schema::table('trainees_courses', function (Blueprint $table) {
            $table->foreignId('trainee_id')->constrained()
            ->cascadeOnUpdate()
            ->noActionOnDelete();
            $table->foreignId('course_id')->constrained()
            ->cascadeOnUpdate()
            ->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainees_courses', function (Blueprint $table) {
            $table->dropForeign(['trainee_id']);
            $table->dropForeign(['course_id']);
        });
    }
};
