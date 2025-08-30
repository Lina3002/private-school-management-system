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
        // Add missing columns to students table (only if they don't exist)
        if (!Schema::hasColumn('students', 'classroom_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->onDelete('set null');
            });
        }
        
        if (!Schema::hasColumn('students', 'parent_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->foreignId('parent_id')->nullable()->constrained('users')->onDelete('set null');
            });
        }

        // Add missing columns to classrooms table (only if they don't exist)
        if (!Schema::hasColumn('classrooms', 'description')) {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->text('description')->nullable();
            });
        }
        
        if (!Schema::hasColumn('classrooms', 'capacity')) {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->integer('capacity')->default(30);
            });
        }
        
        if (!Schema::hasColumn('classrooms', 'academic_year')) {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->string('academic_year')->default('2024-2025');
            });
        }

        // Add missing columns to subjects table (only if they don't exist)
        if (!Schema::hasColumn('subjects', 'staff_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->foreignId('staff_id')->nullable()->constrained('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from students table
        if (Schema::hasColumn('students', 'classroom_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropForeign(['classroom_id']);
                $table->dropColumn('classroom_id');
            });
        }
        
        if (Schema::hasColumn('students', 'parent_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            });
        }

        // Remove columns from classrooms table
        if (Schema::hasColumn('classrooms', 'description')) {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
        
        if (Schema::hasColumn('classrooms', 'capacity')) {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->dropColumn('capacity');
            });
        }
        
        if (Schema::hasColumn('classrooms', 'academic_year')) {
            Schema::table('classrooms', function (Blueprint $table) {
                $table->dropColumn('academic_year');
            });
        }

        // Remove columns from subjects table
        if (Schema::hasColumn('subjects', 'staff_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                $table->dropForeign(['staff_id']);
                $table->dropColumn('staff_id');
            });
        }
    }
};
