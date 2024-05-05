<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
use App\Models\Quota;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller{
    public function compromiso($loan_id){
        $loan = Loan::where('id', $loan_id)->with('client')->with('quotas')->first();
        $quotaLastDate = $loan->quotas->last()->date;
        if ($loan->currency == 'DOLARES'){
            $currency = 'DOLARES AMERICANOS';
        }else{
            $currency = 'BOLIVIANOS';
        }
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $quotaLastDateText = explode('-', $quotaLastDate);
        $quotaLastDate = $quotaLastDateText[2] . ' de ' . $meses[(int)$quotaLastDateText[1] - 1] . ' de ' . $quotaLastDateText[0];
        $loanTextDate = explode('-', $loan->date);
        $dateText = $loanTextDate[2] . ' de ' . $meses[(int)$loanTextDate[1] - 1] . ' de ' . $loanTextDate[0];
        $data = [
            'loan' => $loan,
            'quotaLastDate' => $quotaLastDate,
            'currency' => $currency,
            'dateText' => $dateText
        ];
        $pdf = Pdf::loadView('pdf.compromiso', $data);
        return $pdf->stream('compromiso.pdf');
    }
    public function plan($loan_id){
        $loan = Loan::where('id', $loan_id)->with('client')->with('quotas')->first();
        if ($loan->currency == 'DOLARES'){
            $currency = 'DOLARES AMERICANOS';
        }else{
            $currency = 'BOLIVIANOS';
        }
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $loanTextDate = explode('-', $loan->date);
        $dateText = $loanTextDate[2] . ' de ' . $meses[(int)$loanTextDate[1] - 1] . ' de ' . $loanTextDate[0];
        $data = [
            'loan' => $loan,
            'currency' => $currency,
            'dateText' => $dateText
        ];
        $pdf = Pdf::loadView('pdf.plan', $data);
        return $pdf->stream('plan.pdf');
    }
    public function printPago($cuota_id){
        $cuota = Quota::where('id', $cuota_id)->first();
        $loan = Loan::where('id',$cuota->loan_id)->with('client')->with('quotas')->first();

        if ($loan->currency == 'DOLARES'){
            $currency = 'DOLARES AMERICANOS';
        }else{
            $currency = 'BOLIVIANOS';
        }
        $quotas = Quota::where('loan_id', $cuota->loan_id)
            ->orderBy('id', 'asc')
            ->get();
        $numcuota= 0;
        for ($i=0; $i<count($quotas); $i++){
            if ($quotas[$i]->id != $cuota->id){
                $numcuota++;
            }else{
                break;
            }
        }
        $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $cuotaTextDate = explode('-', $cuota->date);
        $dateText = $cuotaTextDate[2] . ' de ' . $meses[(int)$cuotaTextDate[1] - 1] . ' de ' . $cuotaTextDate[0];
        $data = [
            'loan' => $loan,
            'cuota' => $cuota,
            'currency' => $currency,
            'dateText' => $dateText,
            'numcuota' => $numcuota+1,
            'date' => date('Y-m-d')
        ];
        $pdf = Pdf::loadView('pdf.pago', $data);
        return $pdf->stream('pago.pdf');
    }
}
