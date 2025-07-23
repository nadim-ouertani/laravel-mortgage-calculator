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
        Schema::create('extra_repayment_schedule', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('loan_id');
            $table->unsignedInteger('month_number');
            $table->decimal('starting_balance', 15, 2);
            $table->decimal('monthly_payment', 10, 2);
            $table->decimal('principal_component', 10, 2);
            $table->decimal('interest_component', 10, 2);
            $table->decimal('extra_repayment', 10, 2)->default(0);
            $table->decimal('ending_balance', 15, 2);
            $table->unsignedInteger('remaining_term')->nullable();
            $table->timestamps();
            
            $table->index(['loan_id', 'month_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_repayment_schedule');
    }
};
