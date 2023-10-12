<div :class="{ 'theme-dark': dark }" x-data="data()" lang="en">



    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- MENU SIDEBAR -->
        <x-menu-sidebar />
        <!-- END MENU SIDEBAR -->
        <div class="flex flex-col flex-1 w-full">

            <!-- HEADER -->
            <x-header-dashboard />
            <!-- END HEADER -->

            <!-- PANEL MAIN CATEGORIES -->
            <!--INCLUDE ALERTS MESSAGES-->

            <x-message-success />


            <!-- END INCLUDE ALERTS MESSAGES-->

            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid">

                    <!-- CTA -->
                    <div
                        class="mt-5 flex items-center justify-between p-4 mb-8 text-sm font-semibold text-white bg-blue-500 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple">
                        <div class="flex items-center">
                            <i class="fa-solid fa-money-bills mr-3"></i>

                            <x-slot name="title">
                                {{ __('General Chart') }}
                            </x-slot>
                            <a href="{{ route('general-charts') }}">
                                <span>General Chart</span></a>
                        </div>

                    </div>

                    <!-- Chart JS -->
                    <div class="my-7 mx-auto flex justify-content-center space-x-2">
                        <select wire:model="selectedYear"
                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                            <option value="">Select Year

                            </option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>

                    </div>




                    <div id="chart-container" class="">
                        @if ($showChart)
                            <div class="grid gap-6 mb-8 md:grid-cols-2">
                                <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                                    <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                                        Bars
                                    </h4>
                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                    <canvas id="myChartGeneral" height="200"></canvas>
                                    <div
                                        class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                        <!-- Chart legend -->
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                            <span>{{ $categoryName }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                            <span>{{ $categoryName2 }}</span>
                                        </div>
                                    </div>

                                    <script>
                                        var ctx = document.getElementById('myChartGeneral').getContext('2d');

                                        var dataBar = {
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

                                                },
                                                {
                                                    label: "{{ $categoryName2 }}",
                                                    backgroundColor: "#7e3af2",
                                                    borderColor: "#7e3af2",
                                                    data: @json($expenseData), // Datos de ingresos
                                                }
                                            ]
                                        };

                                        var options = {
                                            title: {
                                                display: true,
                                                text: ' ',
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
                                            data: dataBar,
                                            options: options
                                        });
                                    </script>






                                </div>



                                <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                                    <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                                        Lines
                                    </h4>
                                    <canvas id="line" height="200"></canvas>
                                    <div
                                        class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                        <!-- Chart legend -->
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                            <span>{{ $categoryName }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                            <span>{{ $categoryName2 }}</span>
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
                        @endif
                    </div>

                    @push('scripts')
                        <script>
                            document.addEventListener('livewire:load', function() {
                                const chartContainer = document.getElementById('chart-container');

                                window.livewire.on('updateChartData', data => {
                                    // Actualiza tu gráfica con los nuevos datos en `data`
                                    // Puedes usar Chart.js, Highcharts, Google Charts o cualquier otra biblioteca de gráficos que prefieras
                                    // Asegúrate de tener el código JavaScript necesario para mostrar la gráfica aquí
                                });
                            });
                        </script>
                    @endpush


                </div>
            </main>


            <!-- END PANEL MAIN CATEGORIES -->

        </div>
    </div>


</div>






<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>