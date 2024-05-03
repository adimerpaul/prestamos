<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LoanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        DB::table('loans')->insert([
            'client_id' => 1,
            'date' => '2024-05-03',
            'amount' => 25,
            'payments' => 3,
            'interest_rate' => 0.05,
            'custodial_fee' => 0.01,
            'currency' => 'DOLARES',
        ]);
    }
}
