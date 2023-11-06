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
         <div id="chart-container" class="my-5" wire:key="chart-{{ $selectedUser }}-{{ $selectedYear }}">

             <div class="grid gap-6 mb-8 md:grid-cols-2">
                 <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                     <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                         Bars
                     </h4>

                     <canvas id="myChartGeneral" height="200"></canvas>
                     <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                         <!-- Chart legend -->
                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                             <span class="font-semibold">
                                 {{ number_format($totalIncome = array_sum($incomeData), 0, '.', ',') }}
                             </span>
                         </div>
                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                             <span class="font-semibold">
                                 {{ number_format($totalExpense = array_sum($expenseData), 0, '.', ',') }}
                             </span>
                         </div>
                     </div>




                 </div>



                 <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                     <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                         Lines
                     </h4>
                     <canvas id="line" height="200"></canvas>
                     <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                         <!-- Chart legend -->
                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                             <span class="font-semibold">{{ $categoryName }}</span>
                         </div>
                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
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

             <script>
                 document.addEventListener('livewire:load', function() {
                     Livewire.on('dataUpdated', function() {
                         // Código para actualizar las gráficas
                         var ctx = document.getElementById('myChartGeneral').getContext('2d');

                         // Resto del código para crear o actualizar la gráfica
                         // ...

                         // Recuerda que aquí debes actualizar los datos de la gráfica según los nuevos datos obtenidos asincrónicamente.
                     });
                 });
             </script>


         </div>
     @endif
 </div>
