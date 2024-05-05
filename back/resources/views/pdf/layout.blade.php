<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Compromiso</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            border: 0;
            font-family: Cambria, Cochin, Georgia, Times, 'Times New Roman', serif;
            font-size: 17px;
        }
        .center {
            text-align: center;
        }
        .paddingPage {
            padding: 2.5cm;
        }
        .bold {
            font-weight: bold;
        }
        .h1 {
            font-size: 20px;
        }
        .h2 {
            font-size: 18px;
        }
        .h3 {
            font-size: 16px;
        }
        .justify {
            text-align: justify;
        }
        .line-height {
            line-height: 2;
        }
        .underline {
            text-decoration: underline;
        }
        .w-100 {
            width: 100%;
        }
        .borderTable {
            border-collapse: collapse;
            border: 1px solid black;
        }
        .borderTable th, .borderTable td {
            border: 1px solid black;
            padding: 5px;
            font-size: 12px;
        }
        .border {
            outline: 1px solid black;
        }
        .caption {
            font-size: 12px;
        }
    </style>
</head>
<body class="paddingPage">
@yield('content')
</body>
</html>
