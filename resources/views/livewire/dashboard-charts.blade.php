<!-- COMPONENTS.WELCOME.BLADE.PHP -->


<div class="grid gap-6 mb-8 md:grid-cols-2 my-10">
    <!-- INCOME -->
    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
        <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
            {{ $categoryName }}
        </h4>

        <!-- Crea un elemento canvas donde se renderizará el gráfico -->
        <div class="w-full flex flex-wrap items-center px-4 py-2  ">
            <div class="w-full justify-between flex space-x-7 ">
                <div> <canvas id="myChartIncomeDashboard" width="250" height="250"></canvas></div>
                <div
                    class="rounded w-3/5 px-6 py-6 text-xs font-bold tracking-wide text-center  capitalize border-b bg-gray-100 dark:border-gray-700  dark:text-gray-400 dark:bg-gray-700">
                    <p class="text-gray-500 dark:text-gray-400 font-semibold">Actual {{ $categoryName }}</p>
                    <p class="text-gray-600 dark:text-gray-300 text-lg font-bold mb-7">
                        {{ number_format($totalIncome, 0, '.', ',') }} USD
                    </p>
                    <p class="text-gray-500 dark:text-gray-400 font-semibold">{{ $categoryName }}
                        {{ __('messages.label_category_budget') }}</p>
                    <p class="text-gray-600 dark:text-gray-300 text-lg font-bold mb-7">
                        {{ number_format($totalBudget, 0, '.', ',') }} USD
                    </p>
                    <p class="text-gray-500 dark:text-gray-400 font-semibold">{{ __('messages.label_difference') }} </p>
                    <p class="text-gray-600 dark:text-gray-300 text-lg font-bold">
                        {{ number_format($totalIncome - $totalBudget, 0, '.', ',') }} USD </p>


                    {{-- Mostrar el total --}}

                </div>

            </div>

        </div>

        <script>
            var ctx = document.getElementById('myChartIncomeDashboard').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        label: '# of Income',
                        data: [{{ $totalIncome }}, {{ $totalBudget }}],
                        backgroundColor: ['#14b8a6', '#f1f5f9'],
                        borderColor: ['#14b8a6', '#f1f5f9'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: false,
                            text: 'Income Chart'
                        },
                    },

                    cutoutPercentage: 65,
                    animation: {
                        duration: 2000, // Ajusta la duración de la animación según tus preferencias
                        onComplete: function(animation) {
                            var ctx = this.chart.ctx;
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            var centerX = this.chart.width / 2;
                            var centerY = this.chart.height / 2;
                            ctx.fillStyle = '#0d9488';
                            ctx.font = '28px Roboto';
                            ctx.fillText('{{ number_format($percentageIncome, 0, '.', ',') }}' + '%', centerX,
                                centerY);

                            // Añadir texto adicional "of Expense"
                            ctx.fillStyle = '#808080'; // Color gris
                            ctx.font = '14px Roboto';
                            ctx.fillText('of {{ $categoryName }} ', centerX, centerY +
                                30); // Ajusta la posición vertical según sea necesario
                        }
                    },
                    hover: {
                        animationDuration: 0 // Evitar que la animación del porcentaje se reinicie al hacer hover
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = (tooltipItem.index === 0) ? 'Total Income' : 'Total Budget';
                                var value = data.datasets[0].data[tooltipItem.index].toLocaleString('en-US');
                                return label + ': ' + value + ' USD';
                            }
                        }
                    }
                }
            });
        </script>


        <div
            class="border-solid border-2 border-gray-100 rounded mt-10 p-3 dark:border-gray-700  dark:text-gray-400 dark:bg-gray-700 ">
            <canvas id="myChartIncomeDashboard2"></canvas>
        </div>
        <script>
            var ctx = document.getElementById('myChartIncomeDashboard2').getContext('2d');

            var dataBar = {
                labels: [
                    @for ($i = 1; $i <= 12; $i++)

                        "{{ ucfirst(\Carbon\Carbon::create()->month($i)->translatedFormat('F')) }}",
                    @endfor
                ],
                datasets: [{
                    label: "{{ $categoryName }}",
                    backgroundColor: "#0d9488",
                    borderColor: "#0d9488",
                    data: [
                        @foreach ($monthlyResults as $result)
                            {{ $result['totalIncomeMonthly'] }},
                        @endforeach
                    ]
                }]
            };

            var options = {
                responsive: true,
                legend: {
                    display: false // Oculta la leyenda
                },
                scales: {
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false, // Oculta las líneas de la cuadrícula en el eje x
                        },
                        title: 'Mes',
                        ticks: {
                            beginAtZero: true // Comienza desde cero en el eje x
                        }
                    }],
                    yAxes: [{
                        display: true,
                        gridLines: {
                            color: "rgba(0, 0, 0, 0)", // Establece el color de las líneas de la cuadrícula en blanco
                        },
                        title: 'Valor',
                        ticks: {
                            beginAtZero: true // Comienza desde cero en el eje y
                        }
                    }]
                },
                tooltips: {
                    enabled: true,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            var value = tooltipItem.yLabel.toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'USD',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                            label += ': ' + value;
                            return label;
                        }
                    }
                }
            };

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: dataBar,
                options: options
            });
        </script>


        <!-- TABLE Income -->
        <div class="flex flex-col mt-8 ">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg ">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-bold tracking-wide text-center text-gray-600 capitaliza border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-700">

                                <th class="px-4 py-3 " colspan="5">
                                    {{ $categoryName }} (USD) - {{ ucfirst(now()->translatedFormat('F Y')) }}
                                </th>
                            </tr>
                            <tr
                                class=" border-b-2 border-teal-500 text-xs font-bold tracking-wide text-center text-gray-600 capitalize   bg-gray-100 dark:border-gray-400 dark:text-gray-400 dark:bg-gray-700">
                                <th class="px-4 py-3">{{ __('messages.label_month_th') }}</th>

                                <th class="px-4 py-3">Actual</th>

                                <th class="px-4 py-3">Budget</th>
                                <th class="px-4 py-3">Dif (USD)</th>
                                <th class="px-4 py-3">% Budget</th>



                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-700">

                            @foreach ($monthlyResults as $result)
                                <tr class="text-gray-700 text-xs capitalize text-center dark:text-gray-400">
                                    <td class="px-4 py-3 ">
                                        {{ \Carbon\Carbon::create()->month($result['month'])->format('F') }}


                                    </td>

                                    <td class="px-4 py-3 text-xs text-center">

                                        {{ number_format($result['totalIncomeMonthly'], 0, '.', ',') }} $
                                    </td>

                                    <td class="px-4 py-3 text-xs text-center">

                                        {{ number_format($result['totalBudgetMonthly'], 0, '.', ',') }} $
                                    </td>
                                    <td class="px-4 py-3 text-xs text-center">

                                        {{ number_format($result['totalIncomeMonthly'] - $result['totalBudgetMonthly'], 0, '.', ',') }}
                                        $

                                    </td>
                                    <td
                                        class="px-4 py-3 text-xs text-center flex items-center justify-between  space-x-2 ">
                                        <div class="relative w-16 h-4 bg-gray-300 rounded-full overflow-hidden">
                                            <div class="absolute top-0 left-0 h-full {{ $result['percentageMonthlyIncome'] < 100 ? 'bg-teal-500' : 'bg-teal-500' }}"
                                                style="width: {{ $result['percentageMonthlyIncome'] }}%;"></div>
                                        </div>

                                        <span>
                                            {{ number_format($result['percentageMonthlyIncome'], 0, '.', ',') }} %
                                        </span>
                                        <span class="inline-block w-3 h-3  rounded-full"
                                            style="background-color: {{ $result['percentageMonthlyIncome'] < 100 ? '#7e3af2' : '#14b8a6' }}"></span>

                                    </td>





                                </tr>
                            @endforeach
                            <tr
                                class=" border-t-3  border-teal-500 text-xs font-bold tracking-wide text-center text-gray-600 capitalize  dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-700">
                                <th class="px-4 py-3 text-xs font-bold text-gray-800 dark:text-gray-400">Total USD</th>

                                <th class="px-4 py-3">{{ number_format($totalIncome, 0, '.', ',') }} $</th>

                                <th class="px-4 py-3">{{ number_format($totalBudget, 0, '.', ',') }} $</th>
                                <th class="px-4 py-3"> {{ number_format($totalIncome - $totalBudget, 0, '.', ',') }}
                                    $</th>
                                <th class="px-4 py-3 ">
                                    {{ number_format($percentageIncome, 0, '.', ',') }} %
                                </th>


                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END TABLE INCOME -->
    </div>

    <!-- END INCOME -->

    <!-- EXPENSES -->
    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
        <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
            {{ $categoryName2 }}
        </h4>

        <!-- Crea un elemento canvas donde se renderizará el gráfico -->
        <div class="w-full flex flex-wrap items-center px-4 py-2  ">
            <div class="w-full justify-between flex space-x-7 ">
                <div> <canvas id="myChartExpensesDashboard" width="250" height="250"></canvas></div>
                <div
                    class="rounded w-3/5 px-6 py-6 text-xs font-bold tracking-wide text-center  capitalize border-b bg-gray-100 dark:border-gray-700  dark:text-gray-400 dark:bg-gray-700">
                    <p class="text-gray-500 dark:text-gray-400 font-semibold">Actual {{ $categoryName2 }}</p>
                    <p class="text-gray-600 dark:text-gray-300 text-lg font-bold mb-7">
                        {{ number_format($totalExpenses, 0, '.', ',') }} USD
                    </p>
                    <p class="text-gray-500 dark:text-gray-400 font-semibold">{{ $categoryName2 }}
                        {{ __('messages.label_category_budget') }}</p>
                    <p class="text-gray-600 dark:text-gray-300 text-lg font-bold mb-7">
                        {{ number_format($totalBudget, 0, '.', ',') }} USD
                    </p>
                    <p class="text-gray-500 dark:text-gray-400 font-semibold">{{ __('messages.label_difference') }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300 text-lg font-bold">
                        {{ number_format($totalExpenses - $totalBudget, 0, '.', ',') }} USD </p>

                </div>

            </div>

        </div>


        <script>
            var ctx = document.getElementById('myChartExpensesDashboard').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        label: '# of Expenses',
                        data: [{{ $totalExpenses }}, {{ $totalBudget }}],
                        backgroundColor: ['#7e3af2', '#f1f5f9'],
                        borderColor: ['#7e3af2', '#f1f5f9'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: false,
                            text: 'Expenses Chart'
                        },
                    },
                    cutoutPercentage: 65,
                    animation: {
                        duration: 2000, // Ajusta la duración de la animación según tus preferencias
                        onComplete: function(animation) {
                            var ctx = this.chart.ctx;
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            var centerX = this.chart.width / 2;
                            var centerY = this.chart.height / 2;
                            ctx.fillStyle = '#7e3af2';
                            ctx.font = '28px Roboto';
                            ctx.fillText('{{ number_format($percentageExpense, 0, '.', ',') }}' + '%', centerX,
                                centerY);

                            // Añadir texto adicional "of Expense"
                            ctx.fillStyle = '#808080'; // Color gris
                            ctx.font = '14px Roboto';
                            ctx.fillText('of {{ $categoryName2 }}', centerX, centerY +
                                30); // Ajusta la posición vertical según sea necesario
                        }
                    },
                    hover: {
                        animationDuration: 0 // Evitar que la animación del porcentaje se reinicie al hacer hover
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = (tooltipItem.index === 0) ? ' Total {{ $categoryName2 }}' :
                                    'Total Budget';
                                var value = data.datasets[0].data[tooltipItem.index].toLocaleString('en-US');
                                return label + ': ' + value + ' USD';
                            }
                        }
                    }
                }
            });
        </script>

        <div
            class="border-solid border-2 border-gray-100 rounded mt-10 p-3 dark:border-gray-700  dark:text-gray-400 dark:bg-gray-700 ">
            <canvas id="myChartExpensesDashboard2"></canvas>
        </div>

        <script>
            var ctx = document.getElementById('myChartExpensesDashboard2').getContext('2d');

            var dataBar = {
                labels: [
                    @for ($i = 1; $i <= 12; $i++)
                        "{{ ucfirst(\Carbon\Carbon::create()->month($i)->translatedFormat('F')) }}",
                    @endfor
                ],
                datasets: [{
                    label: "{{ $categoryName2 }}",
                    backgroundColor: "#7e3af2",
                    borderColor: "#7e3af2",
                    data: [
                        @foreach ($monthlyResults as $result)
                            {{ $result['totalExpensesMonthly'] }},
                        @endforeach
                    ]
                }]
            };

            var options = {
                responsive: true,
                legend: {
                    display: false // Oculta la leyenda
                },
                scales: {
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false, // Oculta las líneas de la cuadrícula en el eje x
                        },
                        title: 'Mes',
                        ticks: {
                            beginAtZero: true // Comienza desde cero en el eje x
                        }
                    }],
                    yAxes: [{
                        display: true,
                        gridLines: {
                            color: "rgba(0, 0, 0, 0)", // Establece el color de las líneas de la cuadrícula en blanco
                        },
                        title: 'Valor',
                        ticks: {
                            beginAtZero: true // Comienza desde cero en el eje y
                        }
                    }]
                },
                tooltips: {
                    enabled: true,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = data.datasets[tooltipItem.datasetIndex].label || '';
                            var value = tooltipItem.yLabel.toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'USD',
                                minimumFractionDigits: 0,
                                maximumFractionDigits: 0
                            });
                            label += ': ' + value;
                            return label;
                        }
                    }
                }
            };

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: dataBar,
                options: options
            });
        </script>




        <!-- TABLE EXPENSES -->
        <div class="flex flex-col mt-8 ">
            <div class="py-2 -my-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
                <div
                    class="inline-block min-w-full overflow-hidden align-middle border-b border-gray-200 shadow sm:rounded-lg ">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="text-xs font-bold tracking-wide text-center text-gray-600 capitaliza border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-700">

                                <th class="px-4 py-3 " colspan="5">
                                    {{ $categoryName2 }} (USD) - {{ ucfirst(now()->translatedFormat('F Y')) }}
                                </th>
                            </tr>
                            <tr
                                class=" border-b-2 border-[#7e3af2] text-xs font-bold tracking-wide text-center text-gray-600 capitalize   bg-gray-100 dark:border-gray-400 dark:text-gray-400 dark:bg-gray-700">
                                <th class="px-4 py-3">{{ __('messages.label_month_th') }}</th>

                                <th class="px-4 py-3">Actual</th>

                                <th class="px-4 py-3">Budget</th>
                                <th class="px-4 py-3">Dif (USD)</th>
                                <th class="px-4 py-3">% Budget</th>



                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-700">

                            @foreach ($monthlyResults as $result)
                                <tr class="text-gray-700 text-xs capitalize text-center dark:text-gray-400">
                                    <td class="px-4 py-3 ">
                                        {{ \Carbon\Carbon::create()->month($result['month'])->format('F') }}


                                    </td>

                                    <td class="px-4 py-3 text-xs text-center">

                                        {{ number_format($result['totalExpensesMonthly'], 0, '.', ',') }} $
                                    </td>

                                    <td class="px-4 py-3 text-xs text-center">

                                        {{ number_format($result['totalBudgetMonthly'], 0, '.', ',') }} $
                                    </td>
                                    <td class="px-4 py-3 text-xs text-center">

                                        {{ number_format($result['totalExpensesMonthly'] - $result['totalBudgetMonthly'], 0, '.', ',') }}
                                        $

                                    </td>
                                    <td
                                        class="px-4 py-3 text-xs text-center flex items-center justify-between  space-x-2 ">
                                        <div class="relative w-16 h-4 bg-gray-300 rounded-full overflow-hidden">
                                            <div class="absolute top-0 left-0 h-full {{ $result['percentageMonthlyExpense'] < 100 ? 'bg-purple-600' : 'bg-purple-600' }}"
                                                style="width: {{ $result['percentageMonthlyExpense'] }}%;"></div>
                                        </div>

                                        <span>
                                            {{ number_format($result['percentageMonthlyExpense'], 0, '.', ',') }} %
                                        </span>
                                        <span class="inline-block w-3 h-3  rounded-full"
                                            style="background-color: {{ $result['percentageMonthlyExpense'] < 100 ? '#14b8a6' : '#7e3af2' }}"></span>

                                    </td>





                                </tr>
                            @endforeach
                            <tr
                                class=" border-t-3  border-[#7e3af2] text-xs font-bold tracking-wide text-center text-gray-600 capitalize  dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-700">
                                <th class="px-4 py-3 text-xs font-bold text-gray-800 dark:text-gray-400">Total USD</th>

                                <th class="px-4 py-3">{{ number_format($totalExpenses, 0, '.', ',') }} $</th>

                                <th class="px-4 py-3">{{ number_format($totalBudget, 0, '.', ',') }} $</th>
                                <th class="px-4 py-3"> {{ number_format($totalExpenses - $totalBudget, 0, '.', ',') }}
                                    $</th>
                                <th class="px-4 py-3 ">
                                    {{ number_format($percentageExpense, 0, '.', ',') }} %
                                </th>


                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- END TABLE EXPENSES -->
    </div>

    <!-- END EXPENSES -->

    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 mt-3">

        <canvas id="myChart"></canvas>


        <script>
            var ctx = document.getElementById('myChart').getContext('2d');

            var dataBar = {
                labels: [
                    @for ($i = 1; $i <= 12; $i++)
                        "{{ ucfirst(\Carbon\Carbon::create()->month($i)->translatedFormat('F')) }}",
                    @endfor
                ],

                datasets: [{
                        label: "{{ $categoryName }}",
                        backgroundColor: "#14b8a6",
                        borderColor: "#14b8a6",
                        data: [
                            @foreach ($monthlyResults as $result)
                                {{ $result['totalIncomeMonthly'] }},
                            @endforeach
                        ]

                    },
                    {
                        label: "{{ $categoryName2 }}",
                        backgroundColor: "#7e3af2",
                        borderColor: "#7e3af2",
                        data: [
                            @foreach ($monthlyResults as $result)
                                {{ $result['totalExpensesMonthly'] }},
                            @endforeach
                        ]
                    }
                ]
            };

            var options = {

                scales: {
                    xAxes: [{
                        display: true,
                        title: 'Mes',

                    }],
                    yAxes: [{
                        display: true,
                        title: 'Valor'
                    }]
                },
                tooltips: {
                    callbacks: {
                        title: function(tooltipItem, data) {
                            return data.labels[tooltipItem[0].index];
                        },
                        label: function(tooltipItem, data) {
                            var datasetLabel = data.datasets[tooltipItem.datasetIndex].label;
                            var value = tooltipItem.value;

                            // Aplicar formato con toLocaleString
                            if (!isNaN(value)) {
                                value = Number(value).toLocaleString('en-US') + ' USD ';
                            }

                            return datasetLabel + ': ' + value;
                        }
                    }
                }
            };

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: dataBar,
                options: options
            });
        </script>




    </div>

    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 mt-3">

        <!-- Agrega un elemento canvas para el gráfico -->
        <canvas id="myDoughnutChart"></canvas>

        <script>
            var ctx = document.getElementById('myDoughnutChart').getContext('2d');
            var totalIncome = {{ $totalIncome }};
            var totalExpenses = {{ $totalExpenses }};
            var percentageExpenses = (totalIncome !== 0) ? ((totalExpenses / totalIncome) * 100).toFixed(0) : 0;

            var textLabel = (totalIncome > totalExpenses) ? 'of {{ $categoryName }}' : 'of {{ $categoryName2 }}';

            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [],
                    datasets: [{
                        label: '# of ',
                        data: [totalIncome, totalExpenses],
                        backgroundColor: ['#14b8a6', '#7e3af2'],
                        borderColor: ['#14b8a6', '#7e3af2'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false,
                        },
                        title: {
                            display: false,
                            text: ' Chart'
                        },
                    },
                    cutoutPercentage: 65,
                    animation: {
                        duration: 2000,
                        onComplete: function(animation) {
                            var ctx = this.chart.ctx;
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            var centerX = this.chart.width / 2;
                            var centerY = this.chart.height / 2;

                            ctx.fillStyle = '#eab308';
                            ctx.font = '28px Roboto';
                            ctx.fillText(percentageExpenses + '%', centerX, centerY);

                            // Agregar el texto "of Income" o "of Expenses" debajo del porcentaje
                            ctx.fillStyle = '#808080'; // Gris
                            ctx.font = '16px Roboto';
                            ctx.fillText(textLabel, centerX, centerY +
                                30); // Ajustar la posición vertical según sea necesario
                        }
                    },
                    hover: {
                        animationDuration: 0 // Evitar que la animación del porcentaje se reinicie al hacer hover
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = (tooltipItem.index === 0) ? 'Total {{ $categoryName }}' :
                                    'Total {{ $categoryName2 }}';
                                var value = data.datasets[0].data[tooltipItem.index].toLocaleString('en-US');
                                return label + ': ' + value + ' USD';
                            }
                        }
                    }
                }
            });
        </script>

    </div>
</div>
