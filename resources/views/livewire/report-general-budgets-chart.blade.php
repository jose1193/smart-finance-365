 <div x-show="activeTab === '1'">
     <!-- Chart JS -->
     <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">

             <div wire:ignore>
                 <select wire:model="selectedUser5" wire:change="updateChartBudgetData" id="selectUserChart5"
                     style="width: 100%"
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

         </div>

         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
             <div wire:ignore>
                 <select wire:model="selectedYear4" id="selectYearChart4" wire:change="updateChartBudgetData"
                     style="width: 100%"
                     class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                     <option value="">Select Year</option>
                     @foreach ($years as $year)
                         <option value="{{ $year }}">{{ $year }}</option>
                     @endforeach
                 </select>
             </div>
         </div>

         <!-- VARIABLES for JSPDF -->
         <span id="userInfo" class="text-xs font-bold text-center text-blue-500 capitalize dark:text-gray-400"
             data-username="{{ $userNameSelected ? $userNameSelected->name : '' }}"
             data-year="{{ $selectedYear4 ? $selectedYear4 : '' }}" data-report="{{ $report_date }}">

         </span>
         <!-- END VARIABLES for JSPDF -->


     </div>
     @if ($showChart5)

         <div class="my-10 flex justify-end space-x-2">
             <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                 onclick="downloadImage()">
                 <span class="font-semibold"><i class="fa-regular fa-image px-1"></i></span>
                 Download
             </x-button>
             <x-button class="bg-purple-600 hover:bg-purple-700 shadow-lg hover:shadow-purple-500/50"
                 onclick="generatePDF()">
                 <span class="font-semibold"><i class="fa-regular fa-file-pdf px-1"></i></span>
                 Convert To PDF
             </x-button>
             <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50" wire:click="resetFields5"
                 wire:loading.attr="disabled">
                 <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                 Reset Fields
             </x-button>
         </div>
         @if ($date_start && $date_end && $date_start > $date_end)
             <p class="text-red-700 mt-2 text-center font-semibold">Error: La fecha de inicio no puede
                 ser posterior a
                 la fecha de finalización.</p>
         @endif
         <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">

                 <div wire:ignore>
                     <input type="text" id="myDatePicker5" wire:model.lazy="date_start"
                         wire:change="updateChartBudgetData" placeholder="dd/mm/yyyy" autocomplete="off"
                         class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                 </div>

             </div>

             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <div wire:ignore>
                     <input type="text" id="myDatePicker6" wire:model.lazy="date_end"
                         wire:change="updateChartBudgetData" placeholder="dd/mm/yyyy" autocomplete="off"
                         class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

                 </div>
             </div>

             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <select wire:model="SelectMainCurrencyTypeRender" wire:change="updateChartBudgetData"
                     class="w-full text-sm dark:text-gray-800 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">

                     <option value="USD">USD</option>
                     @foreach ($mainCurrencyTypeRender as $currencyType)
                         @php
                             // Si es 'Blue-ARS', cambiarlo a 'ARS'
                             $displayCurrency = $currencyType == 'Blue-ARS' ? 'ARS' : $currencyType;
                         @endphp
                         <option value="{{ $currencyType }}">{{ $displayCurrency }}</option>
                     @endforeach

                 </select>

             </div>
         </div>
         @if ($date_start)
             <p>Date Start:
                 <span class="text-green-700 ml-2 capitalize">
                     {{ \Carbon\Carbon::parse($date_start)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                 </span>
             </p>
         @endif

         @if ($date_end)
             <p>Date End:
                 <span class="text-green-700 ml-2 capitalize">
                     {{ \Carbon\Carbon::parse($date_end)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                 </span>
             </p>
         @endif
         <div id="content" class="p-2">
             <div id="chart-container5" class="my-5"
                 wire:key="{{ $GeneralChart1 }},{{ $GeneralChart2 }},{{ $GeneralChart3 }},{{ $GeneralChart4 }},{{ $GeneralChart5 }},{{ $GeneralChart6 }},{{ $GeneralChart7 }},{{ $GeneralChart8 }}">

                 <div class="grid gap-6 mb-8 md:grid-cols-2">

                     <!-- INCOME -->
                     <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 mb-5">
                         <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                             {{ $categoryName }}
                         </h4>

                         <!-- Crea un elemento canvas donde se renderizará el gráfico -->
                         <div class="w-full flex flex-wrap items-center px-4 py-2  ">
                             <div class="w-full justify-between flex space-x-7 ">
                                 <div> <canvas id="{{ $GeneralChart1 }}" width="250" height="250"></canvas>
                                 </div>
                                 <div
                                     class="rounded w-3/5 px-6 py-6 text-xs font-bold tracking-wide text-center capitalize border-b bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-700">
                                     @php
                                         $currencyType = $SelectMainCurrencyTypeRender === 'Blue-ARS' ? 'ARS' : $SelectMainCurrencyTypeRender;
                                     @endphp

                                     <p class="text-gray-500 dark:text-gray-400 font-semibold">General
                                         {{ $categoryName }}
                                     </p>
                                     <p class="text-gray-600 dark:text-gray-300 text-lg font-bold mb-7">
                                         {{ number_format(array_sum($incomeDataCurrency), 0, '.', ',') }}
                                         {{ $currencyType }}
                                     </p>
                                     <p class="text-gray-500 dark:text-gray-400 font-semibold">{{ $categoryName }}
                                         Budget
                                     </p>
                                     <p class="text-gray-600 dark:text-gray-300 text-lg font-bold mb-7">
                                         {{ number_format(array_sum($budgetDataCurrency), 0, '.', ',') }}
                                         {{ $currencyType }}
                                     </p>
                                     <p class="text-gray-500 dark:text-gray-400 font-semibold">Difference</p>
                                     <p class="text-gray-600 dark:text-gray-300 text-lg font-bold">
                                         {{ number_format(array_sum($incomeDataCurrency) - array_sum($budgetDataCurrency), 0, '.', ',') }}
                                         {{ $currencyType }}
                                     </p>

                                     {{-- Mostrar el total --}}
                                 </div>


                             </div>

                         </div>
                         <script>
                             var ctx = document.getElementById('{{ $GeneralChart1 }}').getContext('2d');
                             var incomeData = @json($incomeDataCurrency);
                             var budgetData = @json($budgetDataCurrency);
                             var totalIncome = incomeData.reduce((a, b) => a + b, 0);
                             var totalBudget = budgetData.reduce((a, b) => a + b, 0);
                             var percentageIncome = (totalBudget !== 0) ? ((totalIncome / totalBudget) * 100).toFixed(0) : 0;

                             var myChart = new Chart(ctx, {
                                 type: 'doughnut',
                                 data: {
                                     labels: [],
                                     datasets: [{
                                         label: '# of Income',
                                         data: [totalIncome, totalBudget],
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
                                         duration: 2000,
                                         onComplete: function(animation) {
                                             var ctx = this.chart.ctx;
                                             ctx.textAlign = 'center';
                                             ctx.textBaseline = 'middle';
                                             var centerX = this.chart.width / 2;
                                             var centerY = this.chart.height / 2;
                                             ctx.fillStyle = '#14b8a6';
                                             ctx.font = '28px Roboto';
                                             ctx.fillText(percentageIncome + '%', centerX, centerY);

                                             // Añadir texto adicional "of Income"
                                             ctx.fillStyle = '#808080'; // Color gris
                                             ctx.font = '14px Roboto';
                                             ctx.fillText('of {{ $categoryName }} Budget', centerX, centerY +
                                                 30); // Ajusta la posición vertical según sea necesario
                                         }
                                     },
                                     hover: {
                                         animationDuration: 0
                                     },
                                     tooltips: {
                                         callbacks: {
                                             label: function(tooltipItem, data) {
                                                 var label = (tooltipItem.index === 0) ? 'Total {{ $categoryName }}' :
                                                     'Total Budget';
                                                 var value = data.datasets[0].data[tooltipItem.index].toLocaleString('en-US');
                                                 var currencyType = '{{ $SelectMainCurrencyTypeRender }}';

                                                 // Utiliza un ternario para cambiar 'Blue-ARS' a 'ARS'
                                                 currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                                 return label + ': ' + value + currencyType;
                                             }
                                         }
                                     }

                                 }
                             });
                         </script>


                         <div
                             class="border-solid border-2 border-gray-100 rounded mt-10 p-3 dark:border-gray-700  dark:text-gray-400 dark:bg-gray-700 ">
                             <canvas id="{{ $GeneralChart2 }}"></canvas>
                         </div>

                         <script>
                             var ctx = document.getElementById('{{ $GeneralChart2 }}').getContext('2d');
                             var incomeData = @json($incomeDataCurrency);

                             var totalIncome = incomeData.reduce((a, b) => a + b, 0);

                             var dataBar = {
                                 labels: [
                                     @for ($i = 1; $i <= 12; $i++)
                                         "{{ \Carbon\Carbon::create()->month($i)->format('F') }}",
                                     @endfor
                                 ],
                                 datasets: [{
                                     label: "{{ $categoryName }}",
                                     backgroundColor: "#14b8a6",
                                     borderColor: "#14b8a6",
                                     data: [totalIncome],
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
                                     callbacks: {
                                         label: function(tooltipItem, data) {
                                             var label = (tooltipItem.index === 0) ? 'Total {{ $categoryName }}' :
                                                 'Total Budget';
                                             var value = data.datasets[0].data[tooltipItem.index].toLocaleString('en-US');
                                             var currencyType = '{{ $SelectMainCurrencyTypeRender }}';

                                             // Utiliza un ternario para cambiar 'Blue-ARS' a 'ARS'
                                             currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                             return label + ': ' + value + ' ' + currencyType;
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

                     <!-- END INCOME -->


                     <!-- EXPENSE -->
                     <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 mb-5">
                         <h4 class="mb-4 font-semibold text-gray-800 capitalize dark:text-gray-300">
                             {{ $categoryName2 }}
                         </h4>

                         <!-- Crea un elemento canvas donde se renderizará el gráfico -->
                         <div class="w-full flex flex-wrap items-center px-4 py-2  ">
                             <div class="w-full justify-between flex space-x-7 ">
                                 <div> <canvas id="{{ $GeneralChart3 }}" width="250" height="250"></canvas>
                                 </div>
                                 <div
                                     class="rounded w-3/5 px-6 py-6 text-xs font-bold tracking-wide text-center capitalize border-b bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-700">
                                     @php
                                         $currencyType2 = $SelectMainCurrencyTypeRender === 'Blue-ARS' ? 'ARS' : $SelectMainCurrencyTypeRender;
                                     @endphp

                                     <p class="text-gray-500 dark:text-gray-400 font-semibold">General
                                         {{ $categoryName2 }}
                                     </p>
                                     <p class="text-gray-600 dark:text-gray-300 text-lg font-bold mb-7">
                                         {{ number_format(array_sum($expenseDataCurrency), 0, '.', ',') }}
                                         {{ $currencyType2 }}
                                     </p>
                                     <p class="text-gray-500 dark:text-gray-400 font-semibold">{{ $categoryName2 }}
                                         Budget
                                     </p>
                                     <p class="text-gray-600 dark:text-gray-300 text-lg font-bold mb-7">
                                         {{ number_format(array_sum($budgetDataCurrency), 0, '.', ',') }}
                                         {{ $currencyType2 }}
                                     </p>
                                     <p class="text-gray-500 dark:text-gray-400 font-semibold">Difference</p>
                                     <p class="text-gray-600 dark:text-gray-300 text-lg font-bold">
                                         {{ number_format(array_sum($expenseDataCurrency) - array_sum($budgetDataCurrency), 0, '.', ',') }}
                                         {{ $currencyType2 }}
                                     </p>

                                     {{-- Mostrar el total --}}
                                 </div>


                             </div>

                         </div>

                         <script>
                             var ctx = document.getElementById('{{ $GeneralChart3 }}').getContext('2d');
                             var expenseData = @json($expenseDataCurrency);
                             var budgetData = @json($budgetDataCurrency);
                             var totalExpense = expenseData.reduce((a, b) => a + b, 0);
                             var totalBudget = budgetData.reduce((a, b) => a + b, 0);
                             var percentageExpense = (totalBudget !== 0) ? ((totalExpense / totalBudget) * 100).toFixed(0) : 0;

                             var myChart = new Chart(ctx, {
                                 type: 'doughnut',
                                 data: {
                                     labels: [],
                                     datasets: [{
                                         label: '# of Expense',
                                         data: [totalExpense, totalBudget],
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
                                             text: 'Expense Chart'
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
                                             ctx.fillStyle = '#7e3af2';
                                             ctx.font = '28px Roboto';
                                             ctx.fillText(percentageExpense + '%', centerX, centerY);

                                             // Añadir texto adicional "of Expense"
                                             ctx.fillStyle = '#808080'; // Color gris
                                             ctx.font = '14px Roboto';
                                             ctx.fillText('of {{ $categoryName2 }} Budget', centerX, centerY +
                                                 30); // Ajusta la posición vertical según sea necesario
                                         }
                                     },
                                     hover: {
                                         animationDuration: 0
                                     },
                                     tooltips: {
                                         callbacks: {
                                             label: function(tooltipItem, data) {
                                                 var label = (tooltipItem.index === 0) ? 'Total {{ $categoryName2 }}' :
                                                     'Total Budget';
                                                 var value = data.datasets[0].data[tooltipItem.index].toLocaleString('en-US');
                                                 var currencyType = '{{ $SelectMainCurrencyTypeRender }}';


                                                 currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                                 return label + ': ' + value + currencyType;
                                             }
                                         }
                                     }

                                 }
                             });
                         </script>

                         <div
                             class="border-solid border-2 border-gray-100 rounded mt-10 p-3 dark:border-gray-700  dark:text-gray-400 dark:bg-gray-700 ">
                             <canvas id="{{ $GeneralChart4 }}"></canvas>
                         </div>

                         <script>
                             var ctx = document.getElementById('{{ $GeneralChart4 }}').getContext('2d');
                             var expenseData = @json($expenseDataCurrency);

                             var totalExpense = expenseData.reduce((a, b) => a + b, 0);

                             var dataBar = {
                                 labels: [
                                     @for ($i = 1; $i <= 12; $i++)
                                         "{{ \Carbon\Carbon::create()->month($i)->format('F') }}",
                                     @endfor
                                 ],
                                 datasets: [{
                                     label: "{{ $categoryName2 }}",
                                     backgroundColor: "#7e3af2",
                                     borderColor: "#7e3af2",
                                     data: [totalExpense],
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
                                     callbacks: {
                                         label: function(tooltipItem, data) {
                                             var label = (tooltipItem.index === 0) ? 'Total {{ $categoryName2 }}' :
                                                 'Total Budget';
                                             var value = data.datasets[0].data[tooltipItem.index].toLocaleString('en-US');
                                             var currencyType = '{{ $SelectMainCurrencyTypeRender }}';

                                             // Utiliza un ternario para cambiar 'Blue-ARS' a 'ARS'
                                             currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                             return label + ': ' + value + ' ' + currencyType;
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

                     <!-- END EXPENSE -->

                     <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 mb-5">


                         <canvas id="{{ $GeneralChart5 }}" height="200"></canvas>

                         <script>
                             var ctx = document.getElementById('{{ $GeneralChart5 }}').getContext('2d');

                             var dataBar = {
                                 labels: [
                                     @for ($i = 1; $i <= 12; $i++)
                                         "{{ \Carbon\Carbon::create()->month($i)->format('F') }}",
                                     @endfor
                                 ],

                                 datasets: [{
                                         label: "Budget",
                                         backgroundColor: "#16a34a",
                                         borderColor: "#16a34a",
                                         data: @json($budgetDataCurrency),
                                     },
                                     {
                                         label: "{{ $categoryName }}",
                                         backgroundColor: "#14b8a6",
                                         borderColor: "#14b8a6",
                                         data: @json($incomeDataCurrency),
                                     },
                                     {
                                         label: "{{ $categoryName2 }}",
                                         backgroundColor: "#7e3af2",
                                         borderColor: "#7e3af2",
                                         data: @json($expenseDataCurrency),
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
                                                 value = Number(value).toLocaleString('en-US');
                                             }

                                             // Aplicar la condición para cambiar 'Blue-ARS' a 'ARS'
                                             var currencyType = '{{ $SelectMainCurrencyTypeRender }}';
                                             currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                             // Agregar colon, espacio y el tipo de moneda
                                             return datasetLabel + ': ' + value + ' ' + currencyType;
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

                     <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 mb-5">

                         <!-- Agrega un elemento canvas para el gráfico -->
                         <canvas id="{{ $GeneralChart6 }}" class="mt-5"></canvas>
                         <script>
                             var ctx = document.getElementById('{{ $GeneralChart6 }}').getContext('2d');
                             var incomeData = @json($incomeDataCurrency);
                             var expenseData = @json($expenseDataCurrency);
                             var totalIncome = incomeData.reduce((a, b) => a + b, 0);
                             var totalExpenses = expenseData.reduce((a, b) => a + b, 0);
                             var percentageExpenses = (totalIncome !== 0) ? ((totalExpenses / totalIncome) * 100).toFixed(0) : 0;

                             var textLabel = (totalIncome > totalExpenses) ? ' {{ $categoryName }}' : ' {{ $categoryName2 }}';

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
                                             ctx.fillText('of ' + textLabel, centerX, centerY +
                                                 30); // Ajustar la posición vertical según sea necesario
                                         }
                                     },
                                     hover: {
                                         animationDuration: 0
                                     },
                                     tooltips: {
                                         callbacks: {
                                             label: function(tooltipItem, data) {
                                                 var label = (tooltipItem.index === 0) ? 'Total {{ $categoryName }}' :
                                                     'Total {{ $categoryName2 }}';
                                                 var value = data.datasets[0].data[tooltipItem.index].toLocaleString('en-US');

                                                 // Aplicar la condición para cambiar 'Blue-ARS' a 'ARS'
                                                 var currencyType = '{{ $SelectMainCurrencyTypeRender }}';
                                                 currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                                 return label + ': ' + value + ' ' + currencyType;
                                             }
                                         }
                                     }


                                 }
                             });
                         </script>

                     </div>


                     <div class="min-w-0 p-4 bg-white rounded-lg capitalize shadow-xs dark:bg-gray-800">
                         <h4 class="mb-4 font-semibold text-gray-800 text-center dark:text-gray-300">
                             Top 10 {{ $categoryName }} @php
                                 $currencyType = $SelectMainCurrencyTypeRender === 'Blue-ARS' ? 'ARS' : $SelectMainCurrencyTypeRender;
                             @endphp {{ $currencyType }}
                         </h4>
                         <canvas id="{{ $GeneralChart7 }}" width="400" height="200"></canvas>

                         <script>
                             var incomeData = @json($incomeTopTen);

                             var labels = incomeData.map(function(entry) {
                                 return entry.category_name;
                             });

                             var data = incomeData.map(function(entry) {
                                 return entry.total_income;
                             });

                             var ctx = document.getElementById('{{ $GeneralChart7 }}').getContext('2d');
                             var horizontalBarChart = new Chart(ctx, {
                                 type: 'horizontalBar',
                                 data: {
                                     labels: labels,
                                     datasets: [{
                                         label: 'Total Income',
                                         data: data,
                                         backgroundColor: '#14b8a6',
                                         borderColor: '#14b8a6',
                                         borderWidth: 1
                                     }]
                                 },
                                 options: {
                                     legend: {
                                         display: false
                                     },
                                     tooltips: {
                                         callbacks: {
                                             title: function(tooltipItem, data) {
                                                 return data.labels[tooltipItem[0].index];
                                             },
                                             label: function(tooltipItem, data) {
                                                 var currencyType = '{{ $SelectMainCurrencyTypeRender }}';

                                                 // Utiliza un ternario para cambiar 'Blue-ARS' a 'ARS'
                                                 currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                                 return 'Total Income: ' + Number(tooltipItem.value).toLocaleString('en-US') + ' ' +
                                                     currencyType;
                                             }
                                         }
                                     },

                                     scales: {
                                         xAxes: [{
                                             ticks: {
                                                 beginAtZero: true
                                             },
                                             gridLines: {
                                                 display: false
                                             }
                                         }],
                                         yAxes: [{
                                             gridLines: {
                                                 display: false
                                             }
                                         }]
                                     }
                                 }
                             });
                         </script>



                     </div>


                     <div class="min-w-0 p-4 bg-white rounded-lg capitalize shadow-xs dark:bg-gray-800">
                         <h4 class="mb-4 font-semibold text-gray-800 text-center dark:text-gray-300">
                             Top 10 {{ $categoryName2 }} @php
                                 $currencyType = $SelectMainCurrencyTypeRender === 'Blue-ARS' ? 'ARS' : $SelectMainCurrencyTypeRender;
                             @endphp {{ $currencyType }}
                         </h4>
                         <canvas id="{{ $GeneralChart8 }}" width="400" height="200"></canvas>

                         <script>
                             var expensesData = @json($expenseTopTen);

                             var labelsExpenses = expensesData.map(function(entry) {
                                 return entry.category_name;
                             });

                             var dataExpenses = expensesData.map(function(entry) {
                                 return entry.total_expenses;
                             });

                             var ctxExpenses = document.getElementById('{{ $GeneralChart8 }}').getContext('2d');
                             var horizontalBarChartExpenses = new Chart(ctxExpenses, {
                                 type: 'horizontalBar',
                                 data: {
                                     labels: labelsExpenses,
                                     datasets: [{
                                         label: 'Total Expenses',
                                         data: dataExpenses,
                                         backgroundColor: '#7e3af2',
                                         borderColor: '#7e3af2',
                                         borderWidth: 1
                                     }]
                                 },
                                 options: {
                                     legend: {
                                         display: false
                                     },
                                     tooltips: {
                                         callbacks: {
                                             title: function(tooltipItem, data) {
                                                 return data.labels[tooltipItem[0].index];
                                             },
                                             label: function(tooltipItem, data) {
                                                 var currencyType = '{{ $SelectMainCurrencyTypeRender }}';

                                                 // Utiliza un ternario para cambiar 'Blue-ARS' a 'ARS'
                                                 currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                                 return 'Total Expenses: ' + Number(tooltipItem.value).toLocaleString('en-US') +
                                                     ' ' + currencyType;
                                             }
                                         }
                                     },

                                     scales: {
                                         xAxes: [{
                                             ticks: {
                                                 beginAtZero: true
                                             },
                                             gridLines: {
                                                 display: false
                                             }
                                         }],
                                         yAxes: [{
                                             gridLines: {
                                                 display: false
                                             }
                                         }]
                                     }
                                 }
                             });
                         </script>


                     </div>

                 </div>


             </div>

         </div>
     @endif


 </div>
 <script>
     document.addEventListener('livewire:load', function() {
         Livewire.on('initializeFlatpickr2', function() {
             flatpickr("#myDatePicker5", {
                 locale: "es",
                 altInput: true,
                 altFormat: "j F, Y",
                 dateFormat: "Y-m-d", // Set to the format you expect the backend to receive
                 allowInput: true,
                 onClose: function(selectedDates1, dateStr1, instance1) {
                     @this.set('date_start', dateStr1);
                 }
             });

             flatpickr("#myDatePicker6", {
                 locale: "es",
                 altInput: true,
                 altFormat: "j F, Y",
                 dateFormat: "Y-m-d", // Set to the format you expect the backend to receive
                 allowInput: true,
                 onClose: function(selectedDates, dateStr, instance) {
                     @this.set('date_end', dateStr);
                 }
             });

         });

     });
 </script>

 <!--  JSPDF HTML2CANVAS -->

 <script type="text/javascript">
     function generatePDF() {
         const {
             jsPDF
         } = window.jspdf;
         const doc = new jsPDF();

         // Usa html2canvas para convertir el contenido del div en una imagen
         html2canvas(document.getElementById('content')).then((canvas) => {
             const imgData = canvas.toDataURL('image/png');

             // Calcula las coordenadas para centrar el logo en el PDF
             const pdfWidth = doc.internal.pageSize.width;
             const logoWidth = 31;
             const logoHeight = 22;
             const logoX = (pdfWidth - logoWidth) / 2;
             const logoY = 10;
             doc.addImage('{{ asset('img/logo.png') }}', 'PNG', logoX, logoY, logoWidth, logoHeight);

             // Calcula las coordenadas para centrar la imagen en el PDF
             const imgWidth = 180;
             const imgHeight = 220;
             const x = (pdfWidth - imgWidth) / 2;
             const y = 60;

             // Obtén los valores de los atributos de datos
             const userInfoElement = document.getElementById('userInfo');
             const userNameSelected = userInfoElement.getAttribute('data-username');
             const selectedYear4 = userInfoElement.getAttribute('data-year');
             const reportDate = userInfoElement.getAttribute('data-report');

             // Agrega la imagen al PDF
             doc.addImage(imgData, 'PNG', x, y, imgWidth, imgHeight);

             // Ajusta el tamaño y color del texto
             const fontSize = 12;
             const generalReportColor = '#000000'; // Negro
             const textColor = '#0000FF'; // Azul

             // Agrega texto alineado a la izquierda debajo del logo
             const textBelowLogoY = logoY + logoHeight + 10;
             const textLeftMargin = 20;

             // Convierte la cadena 'reportDate' a formato capitalize
             function capitalize(str) {
                 return str.replace(/\b\w/g, function(char) {
                     return char.toUpperCase();
                 });
             }

             const capitalizedReportDate = capitalize(reportDate);


             // Agrega el texto al PDF con colores diferentes
             doc.setFontSize(fontSize);
             doc.setTextColor(generalReportColor);
             doc.text('General Report ', textLeftMargin, textBelowLogoY);

             doc.setTextColor(textColor);
             doc.text(' ' + userNameSelected + ' ' + selectedYear4, textLeftMargin + doc.getTextWidth(
                 'General Report '), textBelowLogoY);


             // Agrega otro texto debajo del primer texto
             const reportDateTextY = textBelowLogoY + 10;
             // Cambia el color del texto para 'Report Date'
             doc.setTextColor(generalReportColor);
             doc.text('Report Date: ', textLeftMargin, reportDateTextY);
             // Cambia el color del texto para el valor de 'capitalizedReportDate'
             doc.setTextColor(textColor);
             doc.text(capitalizedReportDate, textLeftMargin + doc.getTextWidth('Report Date: '),
                 reportDateTextY);


             // Formatea la fecha como DD-MM-YYYY
             const formattedDate = new Date().toLocaleDateString('es-ES', {
                 day: '2-digit',
                 month: '2-digit',
                 year: 'numeric'
             });


             // Genera el nombre del archivo con el nombre de usuario y la fecha
             const fileName = `General-Report-Chart-${userNameSelected}-${formattedDate}.pdf`;

             // Guarda el PDF con el nombre generado
             doc.save(fileName);
         });
     }
 </script>

 <script type="text/javascript">
     function downloadImage() {
         // Usa html2canvas para convertir el contenido del div en una imagen
         html2canvas(document.getElementById('content')).then((canvas) => {
             const userInfoElement = document.getElementById('userInfo');
             const userNameSelected = userInfoElement.getAttribute('data-username');
             const reportDate = userInfoElement.getAttribute('data-report');

             // Formatea la fecha como DD-MM-YYYY
             const formattedDate = new Date().toLocaleDateString('es-ES', {
                 day: '2-digit',
                 month: '2-digit',
                 year: 'numeric'
             });

             const imgData = canvas.toDataURL('image/jpeg'); // Puedes cambiar a 'image/png' si prefieres PNG

             // Crea un enlace temporal y simula un clic para descargar la imagen
             const link = document.createElement('a');
             const fileName =
                 `General-Report-Image-${userNameSelected}-${formattedDate}.jpg`; // Cambia a '.png' si prefieres PNG
             link.href = imgData;
             link.download = fileName;
             document.body.appendChild(link);
             link.click();
             document.body.removeChild(link);
         });
     }
 </script>


 <!-- END JSPDF HTML2CANVAS -->
