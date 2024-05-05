@extends('pdf.layout')
@section('content')
<div class="center h3 bold">COMPROBANTE DE PAGO</div>
<p class="justify line-height">
    <b>Nombre :</b> {{ $loan->client->name }} <br>
    <b>Monto :</b> {{$currency}} {{$loan->amount}} <br>
    <b>Numero de cuota :</b> {{$numcuota}} <br>
    <b>Fecha de Pago :</b> {{formatDate($date)}} <br>
    <b>Monto a Pagar :</b> {{$cuota->total_bs}} <br>
</p>
{{--<table class="w-100 borderTable">--}}
{{--    <thead>--}}
{{--        <tr>--}}
{{--            <th class="center">Nro</th>--}}
{{--            <th class="center">Saldo Inicial</th>--}}
{{--            <th class="center">Interes {{intval($loan->interest_rate)}}%</th>--}}
{{--            <th class="center">Custodia {{intval($loan->custodial_fee)}}%</th>--}}
{{--            <th class="center">Cap.</th>--}}
{{--            <th class="center">Total</th>--}}
{{--            <th class="center">Saldo Final</th>--}}
{{--            <th class="center">Total a Pagar en Bs.</th>--}}
{{--            <th class="center">Fecha de Pago</th>--}}
{{--        </tr>--}}
{{--    </thead>--}}
{{--    <tbody>--}}
{{--        @foreach($loan->quotas as $quota)--}}
{{--            <tr>--}}
{{--                <td class="center">{{$loop->iteration}}</td>--}}
{{--                <td class="center">{{$quota->amount}}</td>--}}
{{--                <td class="center">{{$quota->interest}}</td>--}}
{{--                <td class="center">{{$quota->custodial_fee}}</td>--}}
{{--                <td class="center">{{$quota->capital}}</td>--}}
{{--                <td class="center">{{($quota->interest + $quota->custodial_fee + $quota->capital)}}</td>--}}
{{--                <td class="center">{{$quota->saldo}}</td>--}}
{{--                <td class="center">{{$quota->total_bs}}</td>--}}
{{--                <td class="center">{{formatDate($quota->date)}}</td>--}}
{{--            </tr>--}}
{{--        @endforeach--}}
{{--    </tbody>--}}
{{--</table>--}}
{{--<br>--}}
{{--<p class="justify line-height">--}}
{{--    <textarea cols="30" rows="10" class="border center" readonly>{{$loan->description}}</textarea>--}}
{{--</p>--}}
{{--<p class="center caption">--}}
{{--    En caso de incumplimiento de pago se procederá y autoriza la venta de mi garantía ofrecida para el préstamo--}}
{{--</p>--}}
<br>
<br>
<br>
<br>
<br>
<p class="center caption">
    <b>Firma del Cliente</b>
</p>
@php
function formatDate($date) {
    $meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    $fecha = explode('-', $date);
    return $fecha[2] . '-' . $meses[$fecha[1] - 1] . '-' . $fecha[0];
}
@endphp
@endsection
