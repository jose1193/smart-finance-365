<!doctype html>
<html lang="en">

<head>
    <title>Genearte PDF</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <style>
        table {
            font-size: 14px;

        }

        body {
            /* The image used */
            background-image: url("https://smart-finance365.com/img/bg-pdf.jpg");


            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        table.customTable {
            width: 96%;
            background-color: #FFFFFF;
            border-collapse: collapse;
            border-width: 2px;
            border-color: #7EA8F8;
            border-style: solid;
            color: #302D2E;
            text-align: center;

        }

        table.customTable td,
        table.customTable th {
            border-width: 2px;
            border-color: #7EA8F8;
            border-style: solid;
            padding: 5px;
        }

        table.customTable thead {
            background-color: #7EA8F8;
        }



        .footTr {
            font-weight: 800;
        }
    </style>
</head>

<body>
    <br>
    <div class="container-fluid mt-5 mx-auto">
        <img src="https://smart-finance365.com/img/logo.png" width="170" alt="logo">
        <h5 class=" font-weight-bold" style="font-size:21px;">PDF {{ $title }} - {{ $user }}</h5>

        <h6 class=" font-weight-bold" style="font-size:16px;">Report Date: <span style="color:#1d4ed8;">

                {{ $date }}
            </span></h6>
        <br>
        <table class="customTable">
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>Mes</th>
                    <th>Income</th>
                    <th>USD</th>
                    <th>Expense</th>
                    <th>USD</th>
                </tr>
            </thead>
            <tbody>
                @for ($i = 1; $i <= 12; $i++)
                    <tr>
                        <td> {{ $i }}</td>
                        <td>
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}</td>
                        <td>$
                            {{ number_format($incomeData3[$i - 1], 0, '.', ',') }}</td>
                        <td>
                            {{ number_format($incomeDataCurrency3[$i - 1], 0, '.', ',') }}$</td>
                        <td>
                            {{ number_format($expenseData3[$i - 1], 0, '.', ',') }}</td>
                        <td>
                            {{ number_format($expenseDataCurrency3[$i - 1], 0, '.', ',') }}$</td>
                    </tr>
                @endfor
                <tr class="footTr">
                    <td>
                        {{ $user }}

                    </td>
                    <td>
                        @if ($date_start)
                            <p>Date Start: {{ $date_start }}</p>
                        @endif

                        @if ($date_end)
                            <p>Date End: {{ $date_end }}</p>
                        @endif
                    </td>
                    <td>$
                        {{ number_format($totalIncome3, 0, '.', ',') }}
                    </td>
                    <td>
                        {{ number_format($totalIncomeCurrency3, 0, '.', ',') }}$
                    </td>
                    <td>$
                        {{ number_format($totalExpense3, 0, '.', ',') }}
                    </td>
                    <td>
                        {{ number_format($totalExpenseCurrency3, 0, '.', ',') }}$
                    </td>

                </tr>
            </tbody>
        </table>




        <br> <br>

        <br>



    </div>
</body>

</html>
