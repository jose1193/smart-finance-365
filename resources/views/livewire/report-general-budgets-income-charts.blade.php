 <div x-show="activeTab === '4'">
     <!-- Chart JS -->
     <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
             <div wire:ignore>
                 <select wire:model="selectedUser10" id="selectUserChart7" wire:change="updateBudgetIncomeData"
                     style="width: 100%"
                     class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                     <option value="">{{ __('messages.table_columns_categories.select_a_user') }}</option>
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
                 <select wire:model="selectedMonth3" id="selectMonthChart3" wire:change="updateBudgetIncomeData"
                     style="width: 100%"
                     class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                     wire:ignore>
                     <option value="">{{ __('messages.select_a_month') }}</option>
                     @foreach ($this->months() as $month)
                         <option value="{{ $month['number'] }}">{{ $month['number'] }} -
                             {{ $month['name'] }}</option>
                     @endforeach
                 </select>
             </div>
         </div>


         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
             <div wire:ignore>
                 <select wire:model="selectedYear7" id="selectYearChart7" wire:change="updateBudgetIncomeData"
                     style="width: 100%"
                     class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                     <option value="">{{ __('messages.select_a_year') }}</option>
                     @foreach ($years as $year)
                         <option value="{{ $year }}">{{ $year }}</option>
                     @endforeach
                 </select>
             </div>
         </div>

         <!-- VARIABLES for JSPDF -->
         <span id="userInfo4" class="text-xs font-bold text-center text-blue-500 capitalize dark:text-gray-400"
             data-username="{{ $userNameSelected7 ? $userNameSelected7->name : '' }}"
             data-year="{{ $selectedYear7 ? $selectedYear7 : '' }}" data-report="{{ $report_date }}"
             data-month="{{ $selectedMonthNameEs }}">

         </span>
         <!-- END VARIABLES for JSPDF -->
     </div>
     @if ($showChart6)
         <div class="my-10 flex justify-end space-x-2">
             <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                 onclick="downloadImage4()">
                 <span class="font-semibold"><i class="fa-regular fa-image px-1"></i></span>
                 {{ __('messages.download') }}
             </x-button>
             <x-button class="bg-purple-600 hover:bg-purple-700 shadow-lg hover:shadow-purple-500/50"
                 onclick="generatePDF5()">
                 <span class="font-semibold"><i class="fa-regular fa-file-pdf px-1"></i></span>
                 {{ __('messages.convert_to_pdf') }}
             </x-button>
             <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50" wire:click="resetFields6"
                 wire:loading.attr="disabled">
                 <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                 {{ __('messages.reset_fields') }}
             </x-button>
         </div>
         <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">


             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <select wire:model="SelectMainCurrencyTypeRender" wire:change="updateBudgetIncomeData"
                     class="w-full text-sm dark:text-gray-800 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">

                     <option value="USD">USD</option>
                     @foreach ($mainCurrencyTypeRender ?? [] as $currencyType)
                         @php
                             // Si es 'Blue-ARS', cambiarlo a 'ARS'
                             $displayCurrency = $currencyType == 'Blue-ARS' ? 'ARS' : $currencyType;
                         @endphp
                         <option value="{{ $currencyType }}">{{ $displayCurrency }}</option>
                     @endforeach

                 </select>

             </div>
         </div>
         <div id="content4" class="p-2">
             <div id="chart-container6" class="my-5"
                 wire:key="{{ $BudgeIncomeChart1 }},{{ $BudgeIncomeChart2 }},{{ $BudgeIncomeChart3 }}">

                 @php
                     $currencyType =
                         $SelectMainCurrencyTypeRender === 'Blue-ARS' ? 'ARS' : $SelectMainCurrencyTypeRender;
                 @endphp

                 <div class="grid gap-6 mb-8 md:grid-cols-2">
                     <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">


                         <canvas id="{{ $BudgeIncomeChart1 }}" height="200"></canvas>
                         @php
                             $data = [];

                             foreach ($operationsFetchMonths as $item) {
                                 $amount =
                                     $SelectMainCurrencyTypeRender === 'USD'
                                         ? $item->total_currency
                                         : $item->total_amount;

                                 $data[] = $amount;
                             }
                         @endphp
                         <script>
                             var ctx = document.getElementById('{{ $BudgeIncomeChart1 }}').getContext('2d');

                             var dataBar = {
                                 labels: [
                                     @foreach ($operationsFetchMonths as $item)
                                         "{{ Str::words($item->category_title, 2, '...') }}",
                                     @endforeach
                                 ],
                                 datasets: [{
                                     label: "@if ($budget) Monthly Budget {{ $budget }} - @endif Expenses {{ $currencyType }} ",
                                     backgroundColor: '#7e3af280',
                                     borderColor: 'rgba(124, 58, 237, 1)',
                                     borderWidth: 1, // Establecer el ancho de borde para todas las barras
                                     data: @json($data),
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
                                             var currencyType = '{{ $SelectMainCurrencyTypeRender }}';

                                             // Aplicar un ternario para cambiar 'Blue-ARS' a 'ARS'
                                             currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                             // Aplicar formato con toLocaleString y agregar el tipo de moneda si la condición es verdadera
                                             if (!isNaN(value)) {
                                                 value = Number(value).toLocaleString('en-US') + ' ' + currencyType;
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
                                 @if ($userNameSelected7)
                                     {{ $userNameSelected7->name }}
                                 @endif
                                 @if ($selectedMonthName3)
                                     - {{ $selectedMonthName3 }}
                                 @endif
                                 @if ($selectedYear7)
                                     - {{ $selectedYear7 }}
                                 @endif
                             </p>
                         </div>
                     </div>

                     <div class="min-w-0 p-4 bg-white rounded-lg capitalize shadow-xs dark:bg-gray-800">

                         <canvas id="{{ $BudgeIncomeChart2 }}" class="my-10"></canvas>


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

                             // Tomando como ejemplo que $topTenBudgetIncomes contiene los datos obtenidos
                             var topTenBudgetIncomes = @json($this->topTenBudgetIncomes);

                             // Limita la longitud de las descripciones a 2 palabras
                             var labels = topTenBudgetIncomes.map(item => limitWords(item.category_title, 2));

                             var data = topTenBudgetIncomes.map(function(item) {
                                 // Determinar la cantidad según la condición
                                 var amount =
                                     {{ $SelectMainCurrencyTypeRender === 'USD' ? 'item.total_currency' : 'item.total_amount' }};
                                 return amount;
                             });
                             var ctx = document.getElementById('{{ $BudgeIncomeChart2 }}').getContext('2d');
                             var myChart = new Chart(ctx, {
                                 type: 'horizontalBar',
                                 data: {
                                     labels: labels,
                                     datasets: [{
                                         label: 'Top 10 Budget Expenses {{ $currencyType }} ',
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
                                                 // Mostrar la etiqueta del eje x (category_name)
                                                 return data.labels[tooltipItem[0].index];
                                             },
                                             label: function(tooltipItem, data) {
                                                 var value = tooltipItem.value;
                                                 var currencyType = '{{ $SelectMainCurrencyTypeRender }}';

                                                 // Utiliza un ternario para cambiar 'Blue-ARS' a 'ARS'
                                                 currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                                 // Format with toLocaleString and append the currencyType
                                                 if (!isNaN(value)) {
                                                     value = Number(value).toLocaleString('en-US') + ' ' + currencyType;
                                                 }

                                                 // Mostrar el valor en el tooltip
                                                 return data.datasets[tooltipItem.datasetIndex].label + ': ' + value;
                                             }
                                         }
                                     },
                                 }
                             });
                         </script>

                         <div class="mt-10">
                             <p class="text-xs font-bold text-center text-blue-500 capitalize   dark:text-gray-400 ">
                                 @if ($userNameSelected7)
                                     {{ $userNameSelected7->name }}
                                 @endif
                                 @if ($selectedMonthName3)
                                     - {{ $selectedMonthName3 }}
                                 @endif
                                 @if ($selectedYear7)
                                     - {{ $selectedYear7 }}
                                 @endif
                             </p>
                         </div>
                     </div>

                     <div class="min-w-0 p-4 bg-white rounded-lg capitalize shadow-xs dark:bg-gray-800 mt-5">

                         <canvas id="{{ $BudgeIncomeChart3 }}"></canvas>


                         <script>
                             var ctx = document.getElementById('{{ $BudgeIncomeChart3 }}').getContext('2d');
                             var totalMonthlyExpenses = 0;

                             @foreach ($operationsFetchMonths as $item)


                                 @if ($SelectMainCurrencyTypeRender && $SelectMainCurrencyTypeRender === 'USD')
                                     totalMonthlyExpenses += {{ $item->total_currency }};
                                 @else
                                     totalMonthlyExpenses += {{ $item->total_amount }};
                                 @endif
                             @endforeach


                             var budgetValue = {!! $budget ? json_encode(str_replace(',', '', $budget)) : '0' !!};
                             budgetValue = parseFloat(budgetValue);
                             var percentageExpense = budgetValue !== null && budgetValue !== 0 ?
                                 (totalMonthlyExpenses / budgetValue * 100).toFixed(0) :
                                 null;

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

                                                 var currencyType = '{{ $SelectMainCurrencyTypeRender }}';

                                                 currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                                 return label + ': ' + value + ' ' + currencyType;
                                             }
                                         }
                                     }


                                 }
                             });
                         </script>

                         <div class="mt-10">
                             <p class="text-xs font-bold text-center text-blue-500 capitalize   dark:text-gray-400 ">
                                 @if ($userNameSelected7)
                                 @else
                                     {{ __('messages.please_select_user') }} -
                                 @endif

                                 @if ($selectedMonthName3)
                                 @else
                                     {{ __('messages.please_select_month') }}
                                 @endif

                                 @if ($selectedYear7)
                                 @else
                                     - {{ __('messages.please_select_year') }}
                                 @endif

                             </p>
                         </div>
                     </div>
                 </div>

             </div>
         </div>
     @endif
 </div>

 <!--  JSPDF HTML2CANVAS -->
 <script type="text/javascript">
     function generatePDF5() {
         // Obtén los valores de los atributos de datos
         const userInfoElement = document.getElementById('userInfo4');
         const userNameSelected = userInfoElement.getAttribute('data-username');
         const selectedYear = userInfoElement.getAttribute('data-year');
         const reportDate = userInfoElement.getAttribute('data-report');
         const selectedMonthName = userInfoElement.getAttribute('data-month');
         // Verifica si el mes está seleccionada
         if (selectedMonthName) {
             const {
                 jsPDF
             } = window.jspdf;
             const doc = new jsPDF();

             // Usa html2canvas para convertir el contenido del div en una imagen
             html2canvas(document.getElementById('content4')).then((canvas) => {
                 const imgData = canvas.toDataURL('image/png');

                 // Calcula las coordenadas para centrar el logo en el PDF
                 const pdfWidth = doc.internal.pageSize.width;
                 const logoWidth = 31;
                 const logoHeight = 22;
                 const logoX = (pdfWidth - logoWidth) / 2;
                 const logoY = 10;
                 doc.addImage('{{ asset('img/logo.png') }}', 'PNG', logoX, logoY, logoWidth, logoHeight);

                 // Calcula las coordenadas para centrar la imagen en el PDF
                 const imgWidth = 200;
                 const imgHeight = 150;
                 const x = (pdfWidth - imgWidth) / 2;
                 const y = 60;


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
                 const capitalizedSelectedMonthName = capitalize(selectedMonthName);


                 // Agrega el texto al PDF con colores diferentes
                 doc.setFontSize(fontSize);
                 doc.setTextColor(generalReportColor);
                 doc.text('Income Monthly Report  ', textLeftMargin, textBelowLogoY);

                 doc.setTextColor(textColor);
                 doc.text(' ' + ' ' + userNameSelected + ' ' + capitalizedSelectedMonthName + ' ' + selectedYear,
                     textLeftMargin + doc
                     .getTextWidth(
                         'Income Monthly Report'), textBelowLogoY);


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
                 const fileName =
                     `Income-Monthly-Report-Chart-${userNameSelected}-${capitalizedSelectedMonthName}-${formattedDate}.pdf`;

                 // Guarda el PDF con el nombre generado
                 doc.save(fileName);
             });
         } else {
             // Si el mes no está seleccionada, muestra un mensaje o realiza alguna otra acción
             alert("Por favor, selecciona una mes antes de generar el PDF.");
         }
     }
 </script>

 <script type="text/javascript">
     function downloadImage4() {
         const userInfoElement = document.getElementById('userInfo4');
         const userNameSelected = userInfoElement.getAttribute('data-username');
         const reportDate = userInfoElement.getAttribute('data-report');
         const selectedMonthName = userInfoElement.getAttribute('data-month');
         // Verifica si el mes está seleccionada
         if (selectedMonthName) {
             // Usa html2canvas para convertir el contenido del div en una imagen
             html2canvas(document.getElementById('content4')).then((canvas) => {


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
                     `Income-Monthly-Report-Image-${userNameSelected}-${formattedDate}.jpg`; // Cambia a '.png' si prefieres PNG
                 link.href = imgData;
                 link.download = fileName;
                 document.body.appendChild(link);
                 link.click();
                 document.body.removeChild(link);
             });
         } else {
             // Si la categoría no está seleccionada, muestra un mensaje o realiza alguna otra acción
             alert("Por favor, selecciona una mes antes de descargar la imagen.");
         }
     }
 </script>
 <!-- END JSPDF HTML2CANVAS -->
