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
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->date('date');
            $table->string('code');
            $table->string('description')->nullable();
            $table->decimal('amount', 8, 2);
            $table->integer('payments');
            $table->decimal('interest_rate', 8, 2);
            $table->decimal('custodial_fee', 8, 2);
            $table->string('currency');
            $table->decimal('dolar', 8, 2)->default(6.96);
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
