 <div x-show="activeTab === '6'">
     <!-- Chart JS -->
     <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
             <select wire:model="selectedUser6" wire:change="updateBudgetExpenseData"
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
             <select wire:model="selectedMonth2" wire:change="updateBudgetExpenseData"
                 class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                 <option value="">Select Month</option>
                 @foreach ($this->months() as $month)
                     <option value="{{ $month['number'] }}">{{ $month['number'] }} -
                         {{ $month['name'] }}</option>
                 @endforeach
             </select>
         </div>

         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
             <select wire:model="selectedYear4" wire:change="updateBudgetExpenseData"
                 class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                 <option value="">Select Year</option>
                 @foreach ($years as $year)
                     <option value="{{ $year }}">{{ $year }}</option>
                 @endforeach
             </select>
         </div>


     </div>
     @if ($showChart6)
         <div class="my-10 flex justify-end space-x-2">
             <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                 wire:click="exportToExcel" wire:loading.attr="disabled">
                 <span class="font-semibold"><i class="fa-regular fa-image px-1"></i></span>
                 Download
             </x-button>
             <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50" wire:click="resetFields6"
                 wire:loading.attr="disabled">
                 <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                 Reset Fields
             </x-button>
         </div>
         <div id="chart-container6" class="my-5"
             wire:key="chart-{{ $selectedUser6 }}-{{ $selectedMonth2 }}-{{ $selectedYear4 }}-{{ uniqid() }}">


             <div class="grid gap-6 mb-8 md:grid-cols-2">
                 <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                     <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                         Bars
                     </h4>

                     <canvas id="myChartGeneral6" height="200"></canvas>
                     <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                         <!-- Chart legend -->
                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-blue-600 rounded-full"></span>
                             <span class="font-semibold">
                                 @if ($userNameSelected5)
                                     {{ $userNameSelected5->name }}
                                 @else
                                 @endif
                             </span>
                         </div>
                         <div class="flex items-center">
                             <span class="font-semibold text-gray-700 dark:text-gray-300 px-3">Total Month</span>
                             <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                             <span class="font-semibold">
                                 {{ number_format($totalMonthAmountCurrency, 0, '.', ',') }} $
                             </span>
                         </div>
                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                             <span class="font-semibold"> <span class="font-semibold">
                                     @if ($selectedMonthName2)
                                         {{ $selectedMonthName2 }}
                                     @endif
                                 </span></span>
                         </div>
                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-green-600 rounded-full"></span>
                             <span class="font-semibold"> {{ $selectedYear4 }}</span>
                         </div>
                     </div>


                 </div>


                 <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                     <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                         Lines
                     </h4>
                     <canvas id="line6" height="200"></canvas>
                     <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                         <!-- Chart legend -->

                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-blue-600 rounded-full"></span>
                             <span class="font-semibold">
                                 @if ($userNameSelected5)
                                     {{ $userNameSelected5->name }}
                                 @else
                                 @endif
                             </span>
                         </div>
                         <div class="flex items-center">
                             <span class="font-semibold text-gray-700 dark:text-gray-300 px-3">Total Month</span>
                             <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                             <span class="font-semibold">
                                 {{ number_format($totalMonthAmountCurrency, 0, '.', ',') }} $

                             </span>
                         </div>
                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                             <span class="font-semibold">
                                 @if ($selectedMonthName2)
                                     {{ $selectedMonthName2 }}
                                 @endif
                             </span>
                         </div>
                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-green-600 rounded-full"></span>
                             <span class="font-semibold"> {{ $selectedYear4 }}</span>
                         </div>
                     </div>

                 </div>
             </div>
             @if ($budget)
                 <p>Presupuesto para el mes {{ $budget->budget_currency_total }}:</p>
                 <ul>
                     <li>User ID: {{ $budget->user_id }}</li>
                     <!-- Otros campos del presupuesto -->
                 </ul>
             @else
                 <p>No se encontró un presupuesto para el mes y usuario seleccionados.</p>
             @endif



             <script>
                 var ctx = document.getElementById('myChartGeneral6').getContext('2d');

                 var dataBar = {
                     labels: [

                         @foreach ($operationsFetchMonths as $item)
                             "{{ Str::words($item->category_title, 2, '...') }}",
                         @endforeach
                     ],

                     datasets: [{
                             label: "{!! $budgetData !!}",
                             backgroundColor: "#7C3AED",
                             borderColor: "#7C3AED",
                             data: [
                                 @foreach ($operationsFetchMonths as $item)
                                     {{ $item->operation_currency_total }},
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

                             @foreach ($operationsFetchMonths as $item)
                                 "{{ Str::words($item->category_title, 2, '...') }}",
                             @endforeach

                         ],
                         datasets: [{
                                 label: "{{ $categoryName }}",
                                 backgroundColor: "#0694a2",
                                 borderColor: "#0694a2",
                                 data: [
                                     @foreach ($operationsFetchMonths as $item)
                                         {{ $item->operation_currency_total }},
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
                 const lineCtx = document.getElementById("line6");
                 window.myLine = new Chart(lineCtx, lineConfig);
             </script>

         </div>

     @endif
 </div>
