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

        <h6 class=" font-weight-bold" style="font-size:16px;text-transform: capitalize;">Report Date: <span
                style="color:#1d4ed8;">

                {{ $date }}
            </span></h6>
        <br>
        <table class="customTable">
            <thead>
                <tr>
                    <th>Nro</th>
                    <th>Main Category</th>
                    <th>Category</th>
                    <th>Subcategory</th>
                    <th>Description</th>
                    <th>Month</th>
                    <th>Date</th>
                    <th>Currency</th>
                    <th>Operation</th>
                    <th>Rate CONV/USD</th>
                    <th>Total USD</th>
                    <th>State</th>
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
                            {{ Str::words($item->subcategory_name, 2) ?: 'N/A' }}
                        </td>
                        <td>
                            {{ Str::words($item->operation_description, 2, '...') }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::create()->month($item->operation_month)->locale('en')->monthName }}
                        </td>
                        <td>
                            {{ \Carbon\Carbon::parse($item->operation_date)->format('d/m/Y') }}
                        </td>
                        <td>
                            {{ $item->operation_currency_type }}
                        </td>
                        <td>

                            {{ number_format($item->operation_amount, 0, '.', ',') }}
                        </td>
                        <td>

                            {{ $item->operation_currency }}
                        </td>
                        <td>
                            {{ $item->operation_currency_total < 1 ? $item->operation_currency_total : number_format($item->operation_currency_total) }}
                            $
                        </td>
                        <td>
                            @if ($item->operation_status == '1')
                                <span
                                    style="padding: 0.25rem 0.5rem; font-weight: 600; line-height: 1.5; color: #2f855a; background-color: #9ae6b4; border-radius: 0.375rem;">
                                    {{ $item->status_description }}
                                </span>
                            @elseif ($item->operation_status == '3')
                                <span
                                    style="padding: 0.25rem 0.5rem; font-weight: 600; line-height: 1.5; color: #2f855a; background-color: #9ae6b4; border-radius: 0.375rem;">
                                    {{ $item->status_description }}
                                </span>
                            @elseif ($item->operation_status == '2')
                                <span
                                    style="padding: 0.25rem 0.5rem; font-weight: 600; line-height: 1.5; color: #ed8936; background-color: #fbd38d; border-radius: 0.375rem;">
                                    {{ $item->status_description }}
                                </span>
                            @else
                                <!-- Otro caso por defecto si no coincide con 'admin' ni 'user' -->
                                <span
                                    style="padding: 0.25rem 0.5rem; font-weight: 600; line-height: 1.5; color: #c53030; background-color: #feb2b2; border-radius: 0.375rem;">
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
                    <td colspan="8">
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
