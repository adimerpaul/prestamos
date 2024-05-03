<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable = ['client_id', 'date', 'amount', 'payments', 'interest_rate', 'custodial_fee', 'currency'];
    protected $hidden = ['created_at', 'updated_at'];
    public function client(){
        return $this->belongsTo(Client::class);
    }
}
