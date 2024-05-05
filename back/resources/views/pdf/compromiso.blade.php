@extends('pdf.layout')
@section('content')
<div class="center h1 bold">COMPROMISO DE PAGO</div>
<br>
<p class="justify line-height">
    Conste por el presente documento privado que en caso de incumplimiento surtirá efectos de
    instrumento público el contrato de préstamo bajo las siguientes clausulas:
</p>
<p class="justify line-height">
    <span class="underline bold"> PRIMERO.-</span> Diremos que yo Luis E. Zurita Poma., a partir de ahora denominado
    ACREEDOR y por otra {{ $loan->client->name }}
    se denominara DEUDOR
    (A), ambos mayores de edad, naturales y vecinos de esta ciudad quienes en común acuerdo
    decidieron suscribir el presente contrato de préstamo.
</p>
<p class="justify line-height">
    <span class="underline bold"> SEGUNDA.-</span> El DEUDOR reconoce la obligación que tiene con el ACREEDOR, la suma de
    {{$currency}},

    {{$loan->amount}}

    , mas el pago de intereses señalados por la Ley

    conforme al Art. 409 de Código Civil.

</p>
<p class="justify line-height">
    <span class="underline bold"> TERCERA.-</span>
    Al presente y de conformidad a

    los art. 329 y 330 inc. I del código civil el

    DEUDOR se compromete al pago total más los intereses liquidados en forma impostergable e
    improrrogable en fecha
    {{$quotaLastDate}}

</p>
<p class="justify line-height">
    <span class="underline bold"> CUARTA.-</span>En caso de incumplimiento y por así convenir nuestros intereses ACREEDOR y

    DEUDOR, se procederá a la venta de la garantía ofrecida para el préstamo conforme dispone el
    Art. 593 y 595 inc. I y II del Código Civil, producto de la venta se procederá a la cancelación
    total de la deuda, por lo que a partir de la fecha de incumplimiento ultimo no deberá centavo
    alguno, quedando cancelado su obligación, por lo que posteriores actuados se harán valer por el
    presente documento.

</p>
<p class="justify line-height">
    <span class="underline bold"> QUINTA.-</span> Dirá por ultimo que nosotros ACREEDOR y DEUDOR, damos nuestra

    conformidad con el tenor integro del presente documento por ser así lo convenido y consentido
    firmado en constancia a los {{$dateText}}
</p>
<br>
<br><br><br>
<table class="w-100">
    <tr>
        <td class="center">
            <p class="center h2 bold">ACREEDOR</p>
            <p class="center h3">Luis E. Zurita Poma</p>
            <p class="center h3">C.I. 3118537</p>
        </td>
        <td class="center">
            <p class="center h2 bold">DEUDOR</p>
            <p class="center h3">{{ $loan->client->name }}</p>
            <p class="center h3">C.I. {{ $loan->client->ci }}</p>
        </td>
    </tr>
</table>
@endsection
