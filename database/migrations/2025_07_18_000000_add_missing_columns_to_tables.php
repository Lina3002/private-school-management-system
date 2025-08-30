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
        // Add missing columns to students table
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('set null');
            $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('cascade');
        });

        // Add missing columns to classrooms table
        Schema::table('classrooms', function (Blueprint $table) {
            $table->text('description')->nullable();
            $table->integer('capacity')->default(30);
            $table->string('academic_year')->default('2024-2025');
        });

        // Add missing columns to subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('staff_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from students table
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['school_id']);
            $table->dropColumn(['classroom_id', 'parent_id', 'school_id']);
        });

        // Remove columns from classrooms table
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropColumn(['description', 'capacity', 'academic_year']);
        });

        // Remove columns from subjects table
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['staff_id']);
            $table->dropColumn('staff_id');
        });
    }
}; 