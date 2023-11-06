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
                    <th>Main Category</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Month</th>
                    <th>Operation ARS</th>
                    <th>Currency</th>
                    <th>Total Currency</th>
                    <th>Estatus</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($operationsFetchMonths as $item)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>


                        <td>
                            {{ $item->main_category_title }}</td>
                        <td>
                            {{ $item->category_title }}
                        </td>
                        <td>
                            {{ Str::words($item->operation_description, 2, '...') }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::create()->month($item->operation_month)->locale('en')->monthName }}
                        </td>
                        <td>
                            $
                            {{ number_format($item->operation_amount, 0, '.', ',') }}
                        </td>
                        <td>
                            $
                            {{ number_format($item->operation_currency, 0, '.', ',') }}
                        </td>
                        <td>
                            {{ number_format($item->operation_currency_total, 0, '.', ',') }}
                            $</td>
                        <td>
                            @if ($item->operation_status === '1')
                                <span
                                    class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                    {{ $item->status_description }}
                                </span>
                            @elseif ($item->operation_status === '3')
                                <span
                                    class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                    {{ $item->status_description }}
                                </span>
                            @elseif ($item->operation_status === '2')
                                <span
                                    class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600">
                                    {{ $item->status_description }}
                                </span>
                            @else
                                <!-- Otro caso por defecto si no coincide con 'admin' ni 'user' -->
                                <span
                                    class="px-2 py-1 font-semibold leading-tight text-white bg-red-700 rounded-full dark:bg-gray-700 dark:text-gray-100">
                                    {{ $item->status_description }}
                                </span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                <tr class="footTr">
                    <td>
                        {{ $user }}

                    </td>
                    <td>
                        @if ($selectedYear3)
                            {{ $selectedYear3 }}
                        @else
                            Year Not Selected
                        @endif
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>$
                        {{ number_format($totalMonthAmount, 0, '.', ',') }}
                    </td>
                    <td>

                    </td>
                    <td>

                    </td>
                    <td>
                        {{ number_format($totalMonthAmountCurrency, 0, '.', ',') }}
                        $
                    </td>

                </tr>
            </tbody>
        </table>




        <br> <br>

        <br>



    </div>
</body>

</html>
