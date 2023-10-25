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


                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                    <div x-data="{ activeTab: '1' }">
                        <ul class="flex border-b">
                            <li class="mr-2">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                    @click="activeTab = '1'" :class="{ 'bg-blue-700': activeTab === '1' }">
                                    General
                                </button>
                            </li>
                            <li class="mr-2">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                    @click="activeTab = '2'" :class="{ 'bg-blue-700': activeTab === '2' }">
                                    Categories
                                </button>
                            </li>
                            <li class="mr-2">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                    @click="activeTab = '3'" :class="{ 'bg-blue-700': activeTab === '3' }">
                                    Between Dates
                                </button>
                            </li>

                            <li class="mr-2">
                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                    @click="activeTab = '4'" :class="{ 'bg-blue-700': activeTab === '3' }">
                                    Months
                                </button>
                            </li>
                        </ul>

                        <div class="mt-4">
                            <div x-show="activeTab === '1'">
                                <!-- Chart JS -->
                                <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
                                    <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                                        <select wire:model="selectedUser" wire:change="updateChartData"
                                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Select User</option>
                                            @if (auth()->user()->hasRole('Admin'))
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            @else
                                                <option value="{{ auth()->user()->id }}">{{ auth()->user()->name }}
                                                </option>
                                            @endif

                                        </select>
                                    </div>



                                    <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                                        <select wire:model="selectedYear" wire:change="updateChartData"
                                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Select Year</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                @if ($showChart)
                                    <div id="chart-container" class="my-5">

                                        <div class="grid gap-6 mb-8 md:grid-cols-2">
                                            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                                                <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                                                    Bars
                                                </h4>

                                                <canvas id="myChartGeneral" height="200"></canvas>
                                                <div
                                                    class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                                    <!-- Chart legend -->
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                                        <span
                                                            class="font-semibold">{{ $totalIncome = array_sum($incomeData) }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                                        <span
                                                            class="font-semibold">{{ $totalExpense = array_sum($expenseData) }}</span>
                                                    </div>
                                                </div>




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
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                                        <span class="font-semibold">{{ $categoryName }}</span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                                        <span class="font-semibold">{{ $categoryName2 }}</span>
                                                    </div>
                                                </div>



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
                                    <script>
                                        document.addEventListener('livewire:load', function() {
                                            Livewire.on('dataUpdated', function() {
                                                console.log('dataUpdated'); // Verifica si esta función se ejecuta
                                                // Resto del código para actualizar las gráficas
                                            });
                                        });
                                    </script>
                                @endif
                            </div>
                            <div x-show="activeTab === '2'">
                                <!-- REPORT GENERAL CATEGORIES TABLE  -->
                                <div id="between-dates-chart-table">
                                    <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
                                        <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                                            <select wire:model="selectedUser2" wire:change="updateCategoriesData"
                                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="">Select User</option>
                                                @if (auth()->user()->hasRole('Admin'))
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="{{ auth()->user()->id }}">
                                                        {{ auth()->user()->name }}
                                                    </option>
                                                @endif

                                            </select>
                                        </div>

                                        <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                                            <select wire:model="selectedCategoryId" wire:change="updateCategoriesData"
                                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="">Select Category</option>
                                                @foreach ($categoriesRender->groupBy('main_category_title') as $mainCategoryTitle => $categories)
                                                    <optgroup label="{{ $mainCategoryTitle }}">
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}">
                                                                {{ $category->category_name }}
                                                            </option>
                                                        @endforeach
                                                    </optgroup>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                                            <select wire:model="selectedYear2" wire:change="updateCategoriesData"
                                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="">Select Year</option>
                                                @foreach ($years as $year)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                    </div>

                                    @if ($showChart2)
                                        <div id="chart-container" class="my-5">

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
                                                            <span
                                                                class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                                            <span class="font-semibold">$
                                                                {{ $formatted_amount = number_format($totalGeneral, 0, '.', ',') }}</span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <div class="flex items-center">
                                                                <span
                                                                    class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                                                <span class="font-semibold">
                                                                    @if ($categoryNameSelected)
                                                                        {{ $categoryNameSelected->category_name }}
                                                                    @else
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>




                                                </div>



                                                <div
                                                    class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                                                    <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                                                        Lines
                                                    </h4>
                                                    <canvas id="line" height="200"></canvas>
                                                    <div
                                                        class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                                        <!-- Chart legend -->
                                                        <div class="flex items-center">
                                                            <div class="flex items-center">
                                                                <span
                                                                    class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                                                <span class="font-semibold">
                                                                    @if ($categoryNameSelected)
                                                                        {{ $categoryNameSelected->category_name }}
                                                                    @else
                                                                    @endif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <span
                                                                class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                                            <span class="font-semibold">
                                                                $
                                                                {{ $formatted_amount = number_format($totalGeneral, 0, '.', ',') }}
                                                            </span>
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                            <script>
                                                @if ($userNameSelected2)
                                                    var userName = "{{ $userNameSelected2->name }}";
                                                @else
                                                    var userName = "";
                                                @endif
                                                var ctx = document.getElementById('myChartGeneral').getContext('2d');

                                                var dataBar = {
                                                    labels: [
                                                        @for ($i = 1; $i <= 12; $i++)
                                                            "{{ \Carbon\Carbon::create()->month($i)->format('F') }}",
                                                        @endfor
                                                    ],

                                                    datasets: [{
                                                            label: userName,
                                                            backgroundColor: "#0694a2",
                                                            borderColor: "#0694a2",
                                                            data: [
                                                                @foreach ($ArrayCategories as $data)
                                                                    {{ $data['total'] }},
                                                                @endforeach
                                                            ],

                                                        },

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
                                                                label: "",
                                                                backgroundColor: "#7e3af2",
                                                                borderColor: "#7e3af2",
                                                                data: [
                                                                    @foreach ($ArrayCategories as $data)
                                                                        {{ $data['total'] }},
                                                                    @endforeach
                                                                ],
                                                                fill: false,
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
                                    @endif
                                </div>
                                <!-- END REPORT GENERAL CATEGORIES TABLE  -->
                            </div>
                            <div x-show="activeTab === '3'">
                                <!-- REPORT GENERAL BETWEEN DATE TABLE  -->
                                <div id="between-dates-chart-table">
                                    <div
                                        class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10 ">
                                        <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                                            <select wire:model="selectedUser3" wire:change="updateBetweenData"
                                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                <option value="">Select User</option>
                                                @if (auth()->user()->hasRole('Admin'))
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }}
                                                        </option>
                                                    @endforeach
                                                @else
                                                    <option value="{{ auth()->user()->id }}">
                                                        {{ auth()->user()->name }}
                                                    </option>
                                                @endif
                                            </select>
                                        </div>



                                        <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                                            <input type="date" wire:model="date_start"
                                                wire:change="updateBetweenData"
                                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>

                                        <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                                            <input type="date" wire:model="date_end"
                                                wire:change="updateBetweenData"
                                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        </div>

                                    </div>

                                    @if ($showChart3)
                                        <div id="chart-container" class="my-5">

                                            <div class="grid gap-6 mb-8 md:grid-cols-2">
                                                <div
                                                    class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                                                    <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                                                        Bars
                                                    </h4>
                                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                                    <canvas id="myChartGeneral" height="200"></canvas>
                                                    <div
                                                        class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                                        <!-- Chart legend -->
                                                        <div class="flex items-center">
                                                            <span
                                                                class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                                            <span
                                                                class="font-semibold">{{ $totalIncome = array_sum($incomeData3) }}</span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <span
                                                                class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                                            <span
                                                                class="font-semibold">{{ $totalExpense = array_sum($expenseData3) }}</span>
                                                        </div>
                                                    </div>




                                                </div>



                                                <div
                                                    class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                                                    <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                                                        Lines
                                                    </h4>
                                                    <canvas id="line" height="200"></canvas>
                                                    <div
                                                        class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                                        <!-- Chart legend -->
                                                        <div class="flex items-center">
                                                            <span
                                                                class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                                            <span class="font-semibold">{{ $categoryName }}</span>
                                                        </div>
                                                        <div class="flex items-center">
                                                            <span
                                                                class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                                            <span class="font-semibold">{{ $categoryName2 }}</span>
                                                        </div>
                                                    </div>



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
                                                            data: @json($incomeData3), // Datos de ingresos

                                                        },
                                                        {
                                                            label: "{{ $categoryName2 }}",
                                                            backgroundColor: "#7e3af2",
                                                            borderColor: "#7e3af2",
                                                            data: @json($expenseData3), // Datos de ingresos
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
                                                                data: @json($incomeData3), // Datos de ingresos
                                                                fill: false,
                                                            },
                                                            {
                                                                label: "{{ $categoryName2 }}",
                                                                fill: false,
                                                                backgroundColor: "#7e3af2",
                                                                borderColor: "#7e3af2",
                                                                data: @json($expenseData3), // Datos de gastos
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
                                        <script>
                                            document.addEventListener('livewire:load', function() {
                                                Livewire.on('dataUpdated', function() {
                                                    console.log('dataUpdated'); // Verifica si esta función se ejecuta
                                                    // Resto del código para actualizar las gráficas
                                                });
                                            });
                                        </script>
                                    @endif

                                </div>
                                <!-- END REPORT GENERAL BETWEEN DATE TABLE  -->
                            </div>

                            <div x-show="activeTab === '4'">
                                <!-- Chart JS -->
                                <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
                                    <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                                        <select wire:model="selectedUser4" wire:change="updateMonthData"
                                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Select User</option>
                                            @if (auth()->user()->hasRole('Admin'))
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="{{ auth()->user()->id }}">
                                                    {{ auth()->user()->name }}
                                                </option>
                                            @endif

                                        </select>
                                    </div>

                                    <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0">
                                        <select wire:model="selectedMonth" wire:change="updateMonthData"
                                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Select Month</option>
                                            @foreach ($this->months() as $month)
                                                <option value="{{ $month['number'] }}">{{ $month['number'] }} -
                                                    {{ $month['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                                        <select wire:model="selectedYear3" wire:change="updateMonthData"
                                            class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="">Select Year</option>
                                            @foreach ($years as $year)
                                                <option value="{{ $year }}">{{ $year }}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                </div>
                                @if ($showChart4)
                                    <div id="chart-container" class="my-5">

                                        <div class="grid gap-6 mb-8 md:grid-cols-2">
                                            <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                                                <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                                                    Bars
                                                </h4>

                                                <canvas id="myChartGeneral4" height="200"></canvas>
                                                <div
                                                    class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                                    <!-- Chart legend -->
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-blue-600 rounded-full"></span>
                                                        <span class="font-semibold">
                                                            @if ($userNameSelected4)
                                                                {{ $userNameSelected4->name }}
                                                            @else
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-green-600 rounded-full"></span>
                                                        <span class="font-semibold">
                                                            $
                                                            {{ $formatted_amount = number_format($totalMonthAmount, 0, '.', ',') }}


                                                        </span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                                        <span class="font-semibold"> <span class="font-semibold">
                                                                @if ($selectedMonthName)
                                                                    {{ $selectedMonthName }}
                                                                @endif
                                                            </span></span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                                        <span class="font-semibold"> {{ $selectedYear3 }}</span>
                                                    </div>
                                                </div>




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
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-green-600 rounded-full"></span>
                                                        <span class="font-semibold">
                                                            $
                                                            {{ $formatted_amount = number_format($totalMonthAmount, 0, '.', ',') }}


                                                        </span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-blue-600 rounded-full"></span>
                                                        <span class="font-semibold">
                                                            @if ($userNameSelected4)
                                                                {{ $userNameSelected4->name }}
                                                            @else
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                                        <span class="font-semibold">
                                                            @if ($selectedMonthName)
                                                                {{ $selectedMonthName }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <span
                                                            class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                                        <span class="font-semibold"> {{ $selectedYear3 }}</span>
                                                    </div>
                                                </div>



                                            </div>
                                        </div>
                                        <script>
                                            var ctx = document.getElementById('myChartGeneral4').getContext('2d');

                                            var dataBar = {
                                                labels: [
                                                    @foreach ($operationsFetchMonths as $item)
                                                        {{ $item->category_title }},
                                                    @endforeach
                                                ],

                                                datasets: [{
                                                        label: "",
                                                        backgroundColor: "#0694a2",
                                                        borderColor: "#0694a2",
                                                        data: [
                                                            @foreach ($operationsFetchMonths as $item)
                                                                {{ $item->operation_amount }},
                                                            @endforeach
                                                        ],

                                                    },

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
                                                            data: , // Datos de ingresos
                                                            fill: false,
                                                        },
                                                        {
                                                            label: "{{ $categoryName2 }}",
                                                            fill: false,
                                                            backgroundColor: "#7e3af2",
                                                            borderColor: "#7e3af2",
                                                            data: , // Datos de gastos
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
                                    <script>
                                        document.addEventListener('livewire:load', function() {
                                            Livewire.on('dataUpdated', function() {
                                                console.log('dataUpdated'); // Verifica si esta función se ejecuta
                                                // Resto del código para actualizar las gráficas
                                            });
                                        });
                                    </script>
                                @endif
                            </div>
                        </div>
                    </div>







                </div>
            </main>


            <!-- END PANEL MAIN CATEGORIES -->

        </div>
    </div>


</div>







<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
