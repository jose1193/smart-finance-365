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


     </div>
     @if ($showChart5)

         <div class="my-10 flex justify-end space-x-2">
             <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                 wire:click="exportToExcel" wire:loading.attr="disabled">
                 <span class="font-semibold"><i class="fa-regular fa-image px-1"></i></span>
                 Download
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
         <div id="chart-container5" class="my-5"
             wire:key="chart-{{ $selectedUser5 }}-{{ $selectedYear4 }}-{{ uniqid() }}">

             <div class="grid gap-6 mb-8 md:grid-cols-2">

                 <!-- INCOME -->
                 <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 mb-5">
                     <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                         {{ $categoryName }}
                     </h4>

                     <!-- Crea un elemento canvas donde se renderizará el gráfico -->
                     <div class="w-full flex flex-wrap items-center px-4 py-2  ">
                         <div class="w-full justify-between flex space-x-7 ">
                             <div> <canvas id="myChartIncomeGeneraldoughnut" width="250" height="250"></canvas>
                             </div>
                             <div
                                 class="rounded w-3/5 px-6 py-6 text-xs font-bold tracking-wide text-center capitalize border-b bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-700">
                                 @php
                                     $currencyType = $SelectMainCurrencyTypeRender === 'Blue-ARS' ? 'ARS' : $SelectMainCurrencyTypeRender;
                                 @endphp

                                 <p class="text-gray-500 dark:text-gray-400 font-semibold">General {{ $categoryName }}
                                 </p>
                                 <p class="text-gray-600 dark:text-gray-300 text-lg font-bold mb-7">
                                     {{ number_format(array_sum($incomeDataCurrency), 0, '.', ',') }}
                                     {{ $currencyType }}
                                 </p>
                                 <p class="text-gray-500 dark:text-gray-400 font-semibold">{{ $categoryName }} Budget
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
                         var ctx = document.getElementById('myChartIncomeGeneraldoughnut').getContext('2d');
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
                         <canvas id="myChartIncomeGeneralbar"></canvas>
                     </div>

                     <script>
                         var ctx = document.getElementById('myChartIncomeGeneralbar').getContext('2d');
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
                             <div> <canvas id="myChartExpenseGeneraldoughnut" width="250" height="250"></canvas>
                             </div>
                             <div
                                 class="rounded w-3/5 px-6 py-6 text-xs font-bold tracking-wide text-center capitalize border-b bg-gray-100 dark:border-gray-700 dark:text-gray-400 dark:bg-gray-700">
                                 @php
                                     $currencyType2 = $SelectMainCurrencyTypeRender === 'Blue-ARS' ? 'ARS' : $SelectMainCurrencyTypeRender;
                                 @endphp

                                 <p class="text-gray-500 dark:text-gray-400 font-semibold">General {{ $categoryName2 }}
                                 </p>
                                 <p class="text-gray-600 dark:text-gray-300 text-lg font-bold mb-7">
                                     {{ number_format(array_sum($expenseDataCurrency), 0, '.', ',') }}
                                     {{ $currencyType2 }}
                                 </p>
                                 <p class="text-gray-500 dark:text-gray-400 font-semibold">{{ $categoryName2 }} Budget
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
                         var ctx = document.getElementById('myChartExpenseGeneraldoughnut').getContext('2d');
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
                         <canvas id="myChartExpenseGeneralbar"></canvas>
                     </div>

                     <script>
                         var ctx = document.getElementById('myChartExpenseGeneralbar').getContext('2d');
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

                 <!-- END EXPENSE -->

                 <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 mb-5">


                     <canvas id="myChartGeneral5" height="200"></canvas>

                     <script>
                         var ctx = document.getElementById('myChartGeneral5').getContext('2d');

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
                     <canvas id="myDoughnutChart" class="mt-5"></canvas>
                     <script>
                         var ctx = document.getElementById('myDoughnutChart').getContext('2d');
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
                     <canvas id="horizontalBarChart" width="400" height="200"></canvas>

                     <script>
                         var incomeData = @json($incomeTopTen);

                         var labels = incomeData.map(function(entry) {
                             return entry.category_name;
                         });

                         var data = incomeData.map(function(entry) {
                             return entry.total_income;
                         });

                         var ctx = document.getElementById('horizontalBarChart').getContext('2d');
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
                     <canvas id="horizontalBarChartExpenses" width="400" height="200"></canvas>

                     <script>
                         var expensesData = @json($expenseTopTen);

                         var labelsExpenses = expensesData.map(function(entry) {
                             return entry.category_name;
                         });

                         var dataExpenses = expensesData.map(function(entry) {
                             return entry.total_expenses;
                         });

                         var ctxExpenses = document.getElementById('horizontalBarChartExpenses').getContext('2d');
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
