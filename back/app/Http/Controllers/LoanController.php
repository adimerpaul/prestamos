<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Loan;
use App\Models\Quota;
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
    public function nextId(){
        $loan = Loan::orderBy('id', 'desc')->first();
        if ($loan == null){
            return 1;
        }
        return $loan->id + 1;
    }
    public function store(Request $request){
        $client = $this->upsertCLient($request);
        $loan = new Loan();
        $loan->client_id = $client->id;
        $loan->date = $request->date;
        $loan->code = '';
        $loan->amount = $request->amount;
        $loan->payments = $request->payments;
        $loan->interest_rate = $request->interest_rate;
        $loan->custodial_fee = $request->custodial_fee;
        $loan->description = $request->description;
        $loan->currency = $request->currency;
        $loan->dolar = $request->dolar;
        $loan->save();

        foreach ($request->cuotas as $cuota){
            $quota = new Quota();
            $quota->loan_id = $loan->id;
            $quota->client_id = $client->id;
            $quota->date = $cuota['date'];
            $quota->amount = $cuota['amount'];
            $quota->interest = $cuota['interest'];
            $quota->custodial_fee = $cuota['custodial_fee'];
            $quota->capital = $cuota['capital'];
            $quota->saldo = $cuota['saldo'];
            $quota->total_bs = $cuota['total_bs'];
            $quota->save();
        }

        return $loan;
    }
    public function upsertCLient($request){
        $client = Client::where('ci', $request->input('ci'))->first();
        if ($client == null){
            $client = new Client();
            $client->ci = $request->input('ci');
            $client->name = $request->input('name');
            $client->save();
        }else{
            $client->name = $request->input('name');
            $client->save();
        }
        return $client;
    }
}
