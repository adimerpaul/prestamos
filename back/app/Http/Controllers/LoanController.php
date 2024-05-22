<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Loan;
use App\Models\Quota;
use Illuminate\Http\Request;

class LoanController extends Controller{

    public function quotaAnular(Request $request, $id){
        $quota = Quota::find($id);
        $quotaSearch = Quota::where('loan_id', $quota->loan_id)
            ->where('status', 'PAGADO')
            ->orderBy('id', 'desc')
            ->first();

        if ($quotaSearch->id !== $quota->id){
            return response()->json(['message' => 'No se puede anular una cuota que no sea la ultima'], 400);
        }


        $quota->status = 'PENDIENTE';
        $quota->save();
        $quotas = Quota::where('loan_id', $quota->loan_id)
            ->orderBy('id', 'asc')
            ->get();
        $quotas->each(function($quota){
            $quota->isLast = $this->isLastQuotaActive($quota);
        });

        $loanStatus = 'PAGADO';
        $quotas->each(function($quota) use (&$loanStatus){
            if ($quota->status == 'PENDIENTE'){
                $loanStatus = 'PENDIENTE';
            }
        });
        $loan = Loan::find($quota->loan_id);
        $loan->status = $loanStatus;
        $loan->save();

        return response()->json([
            'quotas' => $quotas,
            'status' => $loanStatus
        ]);
    }
    public function quotaPay(Request $request, $id){
        $quota = Quota::find($id);
        $quota->status = 'PAGADO';
        $quota->save();

        $quotas = Quota::where('loan_id', $quota->loan_id)
            ->orderBy('id', 'asc')
            ->get();

        $quotas->each(function($quota){
            $quota->isLast = $this->isLastQuotaActive($quota);
        });

        $loanStatus = 'PAGADO';
        $quotas->each(function($quota) use (&$loanStatus){
            if ($quota->status == 'PENDIENTE'){
                $loanStatus = 'PENDIENTE';
            }
        });
        $loan = Loan::find($quota->loan_id);
        $loan->status = $loanStatus;
        $loan->save();


        return response()->json([
            'quotas' => $quotas,
            'status' => $loanStatus
        ]);
    }
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
    public function loanDateUpdate(Request $request, $id){
        $loan = Loan::find($id);
        $loan->date = $request->date;
        $loan->save();
        return $loan;
    }

    public function quotaDateUpdate(Request $request, $id){
        $quota = Quota::find($id);
        $quota->date = $request->date;
        $quota->save();


        $quotas = Quota::where('loan_id', $quota->loan_id)
            ->orderBy('id', 'asc')
            ->get();
        $quotas->each(function($quota){
            $quota->isLast = $this->isLastQuotaActive($quota);
        });

        $loanStatus = 'PAGADO';
        $quotas->each(function($quota) use (&$loanStatus){
            if ($quota->status == 'PENDIENTE'){
                $loanStatus = 'PENDIENTE';
            }
        });
        $loan = Loan::find($quota->loan_id);
        $loan->status = $loanStatus;
        $loan->save();

        return response()->json([
            'quotas' => $quotas,
            'status' => $loanStatus
        ]);
    }
    public function store(Request $request){
        $client = $this->upsertCLient($request);
        $loan = new Loan();
        $loan->client_id = $client->id;
        $loan->date = $request->date;
        $loan->code = '';
        $loan->amount = $request->amount;
        $loan->saldo = $request->amount;
        $loan->amortization = 0;
        $loan->payments = $request->payments;
        $loan->interest_rate = $request->interest_rate;
        $loan->custodial_fee = $request->custodial_fee;
        $loan->description = $request->description;
        $loan->currency = $request->currency;
        $loan->dolar = $request->dolar;
        $loan->status = 'PENDIENTE';
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
            $quota->status = 'PENDIENTE';
            $quota->save();
        }

        return $loan;
    }
    public function loanDescriptionUpdate(Request $request, $id){
        $loan = Loan::find($id);
        $loan->description = $request->description;
        $loan->save();
        return $loan;
    }
    public function loansAnular($id){
        $loan = Loan::find($id);
        $loan->status = 'ANULADO';
        $loan->save();
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
    public function show($id){
        $loan = Loan::with(['client', 'quotas'])->find($id);
        $loan->quotas->each(function($quota){
            $quota->isLast = $this->isLastQuotaActive($quota);
        });
        return $loan;
    }
    public function isLastQuotaActive($quota){
        $lastQuota = Quota::where('loan_id', $quota->loan_id)
            ->where('status', 'PENDIENTE')
            ->orderBy('id', 'asc')
            ->first();
        if ($lastQuota == null){
            return false;
        }else{
            return $lastQuota->id == $quota->id;
        }
    }
}
