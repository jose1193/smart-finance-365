<!doctype html>
<html lang="en">

<head>
    <title>Generate PDF</title>
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

        <h6 class=" font-weight-bold" style="font-size:16px;text-transform: capitalize;">Report Date: <span
                style="color:#1d4ed8;">

                {{ $date }}
            </span></h6>
        <br>
        @if ($date_start)
            <p style="text-transform: capitalize; color:#1d4ed8"> {{ $date_start }} </p>
        @endif

        @if ($date_end)
            -
            <p style="text-transform: capitalize; color:#1d4ed8"> {{ $date_end }}</p>
        @endif
        <br>
        <table class="customTable">
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>Mes</th>
                    <th>Budget</th>
                    <th>Income</th>
                    <th>Expense</th>
                    <th>% Budget</th>
                    <th>Savings</th>
                    <th>% Save</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalSavings = 0;
                @endphp
                @for ($i = 1; $i <= 12; $i++)
                    <tr>
                        <td> {{ $i }}</td>
                        <td>
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}</td>
                        <td>
                            {{ number_format($budgetDataCurrency[$i - 1], 0, '.', ',') }} </td>
                        <td>
                            {{ number_format($incomeDataCurrency[$i - 1], 0, '.', ',') }} </td>
                        <td
                            style="{{ $expenseDataCurrency[$i - 1] > $budgetDataCurrency[$i - 1] ? 'color: red;' : '' }}">
                            {{ number_format($expenseDataCurrency[$i - 1], 0, '.', ',') }}
                        </td>

                        <td
                            style="{{ $budgetDataCurrency[$i - 1] > 0 && $expenseDataCurrency[$i - 1] / $budgetDataCurrency[$i - 1] > 100 ? 'color: red;' : '' }}">
                            @if ($budgetDataCurrency[$i - 1] > 0)
                                {{ number_format(($expenseDataCurrency[$i - 1] / $budgetDataCurrency[$i - 1]) * 100, 0) }}%
                            @else
                                N/A
                            @endif
                        </td>
                        <td
                            style="{{ $incomeDataCurrency[$i - 1] - $expenseDataCurrency[$i - 1] < 0 ? 'color: red;' : '' }}">
                            {{ number_format($incomeDataCurrency[$i - 1] - $expenseDataCurrency[$i - 1], 0) }}

                        </td>
                        <td class="px-4 py-3 text-center"
                            style="{{ $incomeDataCurrency[$i - 1] > 0 && ($incomeDataCurrency[$i - 1] - $expenseDataCurrency[$i - 1]) / $incomeDataCurrency[$i - 1] < 0 ? 'color: red;' : '' }}">
                            @if ($incomeDataCurrency[$i - 1] > 0)
                                {{ number_format((($incomeDataCurrency[$i - 1] - $expenseDataCurrency[$i - 1]) / $incomeDataCurrency[$i - 1]) * 100, 2) }}%
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                    @php
                        $totalSavings += $incomeDataCurrency[$i - 1] - $expenseDataCurrency[$i - 1];
                    @endphp
                @endfor
                <tr class="footTr">
                    <td>
                        {{ $user }}

                    </td>
                    <td>
                        @if ($selectedYear4)
                            {{ $selectedYear4 }}
                        @else
                            Year Not Selected
                        @endif
                    </td>
                    <td>

                        {{ number_format($totalBudgetCurrency, 0, '.', ',') }}
                        {{ $currencyType === 'Blue-ARS' ? 'ARS' : $currencyType }}
                    </td>
                    <td>
                        {{ number_format($totalIncomeCurrency, 0, '.', ',') }}
                        {{ $currencyType === 'Blue-ARS' ? 'ARS' : $currencyType }}

                    </td>
                    <td>
                        {{ number_format($totalExpenseCurrency, 0, '.', ',') }}
                        {{ $currencyType === 'Blue-ARS' ? 'ARS' : $currencyType }}
                    </td>

                    <td>

                    </td>
                    <td style="{{ $totalSavings < 0 ? 'color: red;' : '' }}">
                        {{ number_format($totalSavings, 0, '.', ',') }}
                        {{ $currencyType === 'Blue-ARS' ? 'ARS' : $currencyType }}
                    </td>
                    <td>


                    </td>



                </tr>
            </tbody>
        </table>




        <br> <br>

        <br>



    </div>
</body>

</html>
