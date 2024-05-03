<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use Illuminate\Http\Request;

class LoanController extends Controller{
    public function index(Request $request){
        $fechaIni = $request->input('fechaInit');
        $fechaFin = $request->input('fechaFin');
        $search = $request->input('filter') == null ? '' : $request->input('filter');
        if ($search== ''){
            $loans = Loan::where('date', '>=', $fechaIni)
                ->where('date', '<=', $fechaFin)
                ->orderBy('id', 'desc')
                ->with('client')
                ->paginate(20);
            return $loans;
        }else{
            $loans = Loan::with('client')
                ->whereHas('client', function ($query) use ($search){
                    $query->where('name', 'like', '%'.$search.'%');
                })
                ->orWhere('id', 'like', '%'.$search.'%')
                ->orderBy('id', 'desc')
                ->paginate(20);
        }
        return $loans;
    }
    public function store(Request $request){
        $request->validate([
            'amount' => 'required|numeric',
            'term' => 'required|numeric',
            'rate' => 'required|numeric',
        ]);
        $amount = $request->input('amount');
        $term = $request->input('term');
        $rate = $request->input('rate');
        $rate = $rate / 100;
        $monthly_rate = $rate / 12;
        $monthly_payment = $amount * $monthly_rate / (1 - (1 + $monthly_rate) ** -$term);
        $total_payment = $monthly_payment * $term;
        return view('loan', ['monthly_payment' => $monthly_payment, 'total_payment' => $total_payment]);
    }
}
