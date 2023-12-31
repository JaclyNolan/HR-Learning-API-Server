<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('account');
            $table->unsignedSmallInteger('age');
            $table->date('date_of_birth');
            $table->string('education');
            $table->string('main_programming_language');
            $table->unsignedInteger('toeic_score');
            $table->longText('experience_details');
            $table->string('department');
            $table->string('location');
            $table->timestamps();
            $table->softDeletes($column = 'deleted_at', $precision = 0);
        });

        // Add a check constraint to ensure toeic_score is within the range
        DB::statement('ALTER TABLE trainees ADD CHECK (toeic_score >= 0 AND toeic_score <= 999)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainees');
    }
};
