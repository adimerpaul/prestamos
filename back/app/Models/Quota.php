<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quota extends Model{
    use HasFactory;
    protected $fillable = [
        'loan_id',
        'client_id',
        'date',
        'amount',
        'interest',
        'custodial_fee',
        'capital',
        'saldo',
        'total_bs'
    ];

    public function loan(){
        return $this->belongsTo(Loan::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }
    protected $hidden = ['created_at', 'updated_at'];
}
