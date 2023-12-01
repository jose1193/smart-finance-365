 <div x-show="activeTab === '1'">
     <!-- Chart JS -->
     <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">

             <div wire:ignore>
                 <select wire:model="selectedUser" wire:change="updateChartData" id="selectUserChart" style="width: 100%"
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
                 <select wire:model="selectedYear" id="selectYearChart" wire:change="updateChartData"
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
     @if ($showChart)
         <div class="my-10 flex justify-end space-x-2">
             <!-- Agrega las bibliotecas de html2canvas y jsPDF -->
             <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
             <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

             <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50" id="downloadButton">
                 <span class="font-semibold"><i class="fa-regular fa-image px-1"></i></span>
                 Download
             </x-button>

             <script>
                 document.addEventListener('DOMContentLoaded', function() {
                     // Obtén el canvas de Chart.js
                     var canvas = document.getElementById('myChartGeneral');

                     // Agrega un event listener al botón de descarga
                     document.getElementById('downloadButton').addEventListener('click', function() {
                         // Usa html2canvas para capturar el contenido del canvas como una imagen
                         html2canvas(canvas).then(function(canvasImage) {
                             // Convierte la imagen de canvas a formato JPEG
                             var imgData = canvasImage.toDataURL('image/jpeg');

                             // Crea una nueva instancia de jsPDF
                             var pdf = new jsPDF();

                             // Agrega la imagen al documento PDF
                             pdf.addImage(imgData, 'JPEG', 10, 10);

                             // Descarga el documento PDF
                             pdf.save('chart_download.pdf');
                         });
                     });
                 });
             </script>

             <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50" wire:click="resetFields1"
                 wire:loading.attr="disabled">
                 <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                 Reset Fields
             </x-button>
         </div>
         <div id="chart-container" class="my-5"
             wire:key="chart-{{ $selectedUser }}-{{ $selectedYear }}-{{ uniqid() }}">

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
                                 {{ number_format($totalIncome = array_sum($incomeData), 0, '.', ',') }} $
                             </span>
                         </div>
                         <div class="flex items-center">
                             <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                             <span class="font-semibold">
                                 {{ number_format($totalExpense = array_sum($expenseData), 0, '.', ',') }} $
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

         </div>
     @endif


 </div>
