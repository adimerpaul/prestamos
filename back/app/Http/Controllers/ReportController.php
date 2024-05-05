<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Loan;
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
//        error_log(json_encode($data));
        $pdf = Pdf::loadView('pdf.compromiso', $data);
        return $pdf->stream('compromiso.pdf');
    }
}
