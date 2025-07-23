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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('annual_interest_rate', 5, 3);
            $table->unsignedInteger('loan_term_years');
            $table->decimal('monthly_extra_payment', 10, 2)->default(0);
            $table->decimal('calculated_monthly_payment', 10, 2);
            $table->decimal('effective_interest_rate', 5, 3)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
