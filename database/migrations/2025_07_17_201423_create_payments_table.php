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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['transport', 'school']);
            $table->enum('method', ['cash', 'cheque', 'card', 'transfer']);
            $table->float('amount', 8, 2);
            $table->dateTime('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->date('duedate');
            $table->enum('payment_status', ['paid', 'awaiting', 'overdue']);
            $table->text('receipt_file');
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
