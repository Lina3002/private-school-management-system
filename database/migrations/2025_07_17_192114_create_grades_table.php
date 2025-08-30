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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects')->onDelete('cascade');
            $table->float('value', 5, 2);
            $table->integer('min_value')->default(0);
            $table->integer('max_value')->default(20);
            $table->enum('term', ['semester 1', 'semester 2']);
            $table->enum('exam_type', ['exam 1', 'exam 2','exam 3', 'final', 'oral', 'activity', 'homework', 'quiz']);
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->foreignId('staff_id')->constrained('staffs')->onDelete('cascade');
            $table->text('comment');
            $table->timestamp('grading_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
