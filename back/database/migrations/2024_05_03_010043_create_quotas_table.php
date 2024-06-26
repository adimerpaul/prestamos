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
        Schema::create('quotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->foreign('loan_id')->references('id')->on('loans');
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->date('date');
            $table->decimal('amount', 8, 2);
            $table->decimal('interest', 8, 2);
            $table->decimal('custodial_fee', 8, 2);
            $table->decimal('capital', 8, 2);
            $table->decimal('saldo', 8, 2);
            $table->decimal('total_bs', 8, 2);
            $table->string('status')->default('PENDIENTE');

            $table->decimal('capital_paid', 8, 2)->default(0);
            $table->decimal('interest_paid', 8, 2)->default(0);
            $table->decimal('custodial_fee_paid', 8, 2)->default(0);
            $table->decimal('total_bs_paid', 8, 2)->default(0);
            $table->decimal('total_paid', 8, 2)->default(0);
            $table->integer('days')->default(0);
            $table->date('date_paid')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotas');
    }
};
