<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->decimal('loan_amount', 15, 2);
            $table->decimal('annual_interest_rate', 8, 3);
            $table->integer('loan_term_years');
            $table->decimal('monthly_extra_payment', 15, 2)->default(0);
            $table->decimal('calculated_monthly_payment', 15, 2);
            $table->decimal('effective_interest_rate', 8, 3);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};