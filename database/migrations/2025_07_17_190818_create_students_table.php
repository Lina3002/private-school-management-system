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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('massar_code', 10)->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['male', 'female']);
            $table->text('photo');
            $table->string('email')->unique();
            $table->string('password');
            $table->date('birth_date');
            $table->boolean('driving_service')->default(false);
            $table->text('address');
            $table->string('emergency_phone', 20);
            $table->string('city_of_birth');
            $table->string('country_of_birth');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
