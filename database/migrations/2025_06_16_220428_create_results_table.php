<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->onDelete('cascade');
            $table->string('student_code'); // Store the student code
            $table->json('answers')->nullable(); // Store answers as JSON
            $table->integer('score')->default(0); // Store the score for the exam
            $table->string('status')->default('pending'); // Status can be 'pending', 'passed', or 'failed'
            $table->dateTime('submitted_at')->nullable(); // Store the submission time
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
