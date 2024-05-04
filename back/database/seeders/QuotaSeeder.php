<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuotaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
//        $table->unsignedBigInteger('loan_id');
//        $table->foreign('loan_id')->references('id')->on('loans');
//        $table->date('date');
//        $table->decimal('amount', 8, 2);
//        $table->decimal('interest', 8, 2);
//        $table->decimal('custodial_fee', 8, 2);
//        $table->decimal('saldo', 8, 2);
//        $table->decimal('total_bs', 8, 2);
        DB::table('quotas')->insert([
            [
                'loan_id' => 1,
                'date' => '2024-05-03',
                'amount' => 8.33,
                'interest' => 1.25,
                'custodial_fee' => 0.25,
                'capital' => 6.83,
                'saldo' => 15,
                'total_bs' => 150,
                'client_id' => 1
            ],
        ]);
    }
}
