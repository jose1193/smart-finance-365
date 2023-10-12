<!-- COMPONENTS.WELCOME.BLADE.PHP -->
<h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
    Charts
</h2>

<div class="grid gap-6 mb-8 md:grid-cols-2">
    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
        <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
            Bars
        </h4>
        <canvas id="myChart"></canvas>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


        <script>
            var ctx = document.getElementById('myChart').getContext('2d');

            var data = {
                labels: [
                    @for ($i = 1; $i <= 12; $i++)
                        "{{ \Carbon\Carbon::create()->month($i)->format('F') }}",
                    @endfor
                ],

                datasets: [{
                        label: "{{ $categoryName }}",
                        backgroundColor: "#0694a2",
                        borderColor: "#0694a2",
                        data: @json($incomeData)

                    },
                    {
                        label: "{{ $categoryName2 }}",
                        backgroundColor: "#7e3af2",
                        borderColor: "#7e3af2",
                        data: @json($expenseData)
                    }
                ]
            };

            var options = {
                title: {
                    display: true,
                    text: 'Bar Chart ',
                    responsive: true,
                    legend: {
                        display: false,
                    },

                },
                scales: {
                    xAxes: [{
                        display: true,
                        title: 'Mes',

                    }],
                    yAxes: [{
                        display: true,
                        title: 'Valor'
                    }]
                }
            };

            var myChart = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: options
            });
        </script>

        <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
            <!-- Chart legend -->
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 mr-1 bg-teal-500 rounded-full"></span>
                <span>{{ $categoryName }}</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                <span>{{ $categoryName2 }}</span>
            </div>
        </div>



    </div>



    <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
        <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
            Lines
        </h4>
        <canvas id="line"></canvas>
        <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
            <!-- Chart legend -->
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                <span>Income</span>
            </div>
            <div class="flex items-center">
                <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                <span>Expenses</span>
            </div>
        </div>
        <script>
            /**
             * For usage, visit Chart.js docs https://www.chartjs.org/docs/latest/
             */
            const lineConfig = {
                type: "line",
                data: {
                    labels: [
                        @for ($i = 1; $i <= 12; $i++)
                            "{{ \Carbon\Carbon::create()->month($i)->format('F') }}",
                        @endfor
                    ],
                    datasets: [{
                            label: "{{ $categoryName }}",
                            backgroundColor: "#0694a2",
                            borderColor: "#0694a2",
                            data: @json($incomeData), // Datos de ingresos
                            fill: false,
                        },
                        {
                            label: "{{ $categoryName2 }}",
                            fill: false,
                            backgroundColor: "#7e3af2",
                            borderColor: "#7e3af2",
                            data: @json($expenseData), // Datos de gastos
                        },
                    ],
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false,
                    },
                    tooltips: {
                        mode: "index",
                        intersect: false,
                    },
                    hover: {
                        mode: "nearest",
                        intersect: true,
                    },
                    scales: {
                        x: {
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: "Month",
                            },
                        },
                        y: {
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: "Value",
                            },
                        },
                    },
                },
            };

            // Cambia esto al ID de tu elemento de gráfico en el HTML
            const lineCtx = document.getElementById("line");
            window.myLine = new Chart(lineCtx, lineConfig);
        </script>

    </div>
</div>
