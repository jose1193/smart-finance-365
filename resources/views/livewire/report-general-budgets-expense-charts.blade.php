 <div x-show="activeTab === '4'">
     <!-- Chart JS -->
     <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
             <div wire:ignore>
                 <select wire:model="selectedUser6" id="selectUserChart6" wire:change="updateBudgetExpenseData"
                     style="width: 100%"
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
         </div>
         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0">
             <div wire:ignore>
                 <select wire:model="selectedMonth2" id="selectMonthChart2" wire:change="updateBudgetExpenseData"
                     style="width: 100%"
                     class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                     wire:ignore>
                     <option value="">Select Month</option>
                     @foreach ($this->months() as $month)
                         <option value="{{ $month['number'] }}">{{ $month['number'] }} -
                             {{ $month['name'] }}</option>
                     @endforeach
                 </select>
             </div>
         </div>


         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
             <div wire:ignore>
                 <select wire:model="selectedYear4" id="selectYearChart5" wire:change="updateBudgetExpenseData"
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


                     <canvas id="myChartGeneral6" height="200"></canvas>
                     <script>
                         var ctx = document.getElementById('myChartGeneral6').getContext('2d');

                         var dataBar = {
                             labels: [
                                 @foreach ($operationsFetchMonths as $item)
                                     "{{ Str::words($item->operation_description, 2, '...') }}",
                                 @endforeach
                             ],
                             datasets: [{
                                 label: "@if ($budgetData){{ $budgetData }} - @endif Expenses ",
                                 backgroundColor: '#7e3af280',
                                 borderColor: 'rgba(124, 58, 237, 1)',
                                 borderWidth: 1, // Establecer el ancho de borde para todas las barras
                                 data: [
                                     @foreach ($operationsFetchMonths as $item)
                                         {{ $item->operation_currency_total }},
                                     @endforeach
                                 ],
                             }]
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
                                         var value = tooltipItem.value;

                                         // Aplicar formato con toLocaleString
                                         if (!isNaN(value)) {
                                             value = Number(value).toLocaleString('en-US') + ' USD ';
                                         }

                                         return value;
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

                     <div class="my-10">
                         <p class="text-xs font-bold text-center text-purple-700 capitalize   dark:text-gray-400 ">
                             @if ($userNameSelected5)
                                 {{ $userNameSelected5->name }}
                             @endif
                             @if ($selectedMonthName2)
                                 - {{ $selectedMonthName2 }}
                             @endif
                             @if ($selectedYear4)
                                 - {{ $selectedYear4 }}
                             @endif
                         </p>
                     </div>
                 </div>

                 <div class="min-w-0 p-4 bg-white rounded-lg capitalize shadow-xs dark:bg-gray-800">

                     <canvas id="topTenChartBudgetExpenses" class="my-10"></canvas>


                     <script>
                         // Función para limitar la longitud de las palabras
                         function limitWords(str, numWords) {
                             var words = str.split(' ');
                             var truncated = words.slice(0, numWords).join(' ');
                             if (words.length > numWords) {
                                 truncated += '...';
                             }
                             return truncated;
                         }

                         // Tomando como ejemplo que $topTenBudgetExpenses contiene los datos obtenidos
                         var topTenBudgetExpenses = @json($this->topTenBudgetExpenses);

                         // Limita la longitud de las descripciones a 2 palabras
                         var labels = topTenBudgetExpenses.map(item => limitWords(item.operation_description, 2));
                         var data = topTenBudgetExpenses.map(item => item.operation_currency_total);

                         var ctx = document.getElementById('topTenChartBudgetExpenses').getContext('2d');
                         var myChart = new Chart(ctx, {
                             type: 'horizontalBar',
                             data: {
                                 labels: labels,
                                 datasets: [{
                                     label: 'Top 10 Budget Expenses ',
                                     data: data,
                                     backgroundColor: 'rgba(20, 184, 166, 0.2)',
                                     borderColor: 'rgba(20, 184, 166, 1)',
                                     borderWidth: 1
                                 }]
                             },
                             options: {
                                 scales: {
                                     xAxes: [{
                                         ticks: {
                                             beginAtZero: true
                                         }
                                     }]
                                 },
                                 tooltips: {
                                     callbacks: {
                                         title: function(tooltipItem, data) {
                                             return data.labels[tooltipItem[0].index];
                                         },
                                         label: function(tooltipItem, data) {
                                             return data.datasets[tooltipItem.datasetIndex].label + ': ' + Number(tooltipItem
                                                 .value).toLocaleString('en-US') + ' USD ';
                                         }
                                     }
                                 },
                             }
                         });
                     </script>

                     <div class="mt-10">
                         <p class="text-xs font-bold text-center text-blue-500 capitalize   dark:text-gray-400 ">
                             @if ($userNameSelected5)
                                 {{ $userNameSelected5->name }}
                             @endif
                             @if ($selectedMonthName2)
                                 - {{ $selectedMonthName2 }}
                             @endif
                             @if ($selectedYear4)
                                 - {{ $selectedYear4 }}
                             @endif
                         </p>
                     </div>
                 </div>

                 <div class="min-w-0 p-4 bg-white rounded-lg capitalize shadow-xs dark:bg-gray-800 mt-5">

                     <canvas id="myChartBudgetMonthlyExpenses"></canvas>


                     <script>
                         var ctx = document.getElementById('myChartBudgetMonthlyExpenses').getContext('2d');
                         var totalMonthlyExpenses = 0; // Variable para almacenar la suma de los valores

                         @foreach ($operationsFetchMonths as $item)
                             totalMonthlyExpenses += {{ $item->operation_currency_total }};
                         @endforeach

                         // Verificar si $budget es nulo
                         var budgetValue = {!! json_encode($budget ? $budget->budget_currency_total : 0) !!};

                         var percentageExpense = budgetValue !== 0 ? (totalMonthlyExpenses / budgetValue * 100).toFixed(0) : null;

                         var myChart = new Chart(ctx, {
                             type: 'doughnut',
                             data: {
                                 labels: [],
                                 datasets: [{
                                     label: '# of Expenses',
                                     data: [totalMonthlyExpenses, budgetValue],
                                     backgroundColor: ['#7e3af280', '#f1f5f980'],
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
                                     duration: 2000,
                                     onComplete: function(animation) {
                                         var ctx = this.chart.ctx;
                                         ctx.textAlign = 'center';
                                         ctx.textBaseline = 'middle';
                                         var centerX = this.chart.width / 2;
                                         var centerY = this.chart.height / 2;

                                         if (percentageExpense !== null) {
                                             ctx.fillStyle = '#7e3af2';
                                             ctx.font = '28px Roboto';
                                             ctx.fillText(`${percentageExpense}%`, centerX,
                                                 centerY); // Mostrar el porcentaje con dos decimales
                                         }

                                         if (percentageExpense !== null) {
                                             ctx.fillStyle = '#808080';
                                             ctx.font = '14px Roboto';
                                             ctx.fillText('of Expense', centerX, centerY + 30);
                                         }
                                     }
                                 },
                                 hover: {
                                     animationDuration: 0
                                 },
                                 tooltips: {
                                     callbacks: {
                                         label: function(tooltipItem, data) {
                                             var label = (tooltipItem.index === 0) ? 'Total Monthly Expenses' : 'Total Budget';
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




         </div>
     @endif
 </div>
