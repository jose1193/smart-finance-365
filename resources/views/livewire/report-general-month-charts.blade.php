 <div x-show="activeTab === '3'">
     <!-- Chart JS -->
     <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
             <div wire:ignore>
                 <select wire:model="selectedUser4" wire:change="updateMonthData" id="selectUserChart4" style="width: 100%"
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
                 <select wire:model="selectedMonth" wire:change="updateMonthData" id="selectMonthChart"
                     style="width: 100%"
                     class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                 <select wire:model="selectedYear3" id="selectYearChart3" wire:change="updateMonthData"
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
         <span id="userInfo3" class="text-xs font-bold text-center text-blue-500 capitalize dark:text-gray-400"
             data-username="{{ $userNameSelected4 ? $userNameSelected4->name : '' }}"
             data-year="{{ $selectedYear3 ? $selectedYear3 : '' }}" data-report="{{ $report_date }}"
             data-month="{{ $selectedMonthNameEs }}">

         </span>
         <!-- END VARIABLES for JSPDF -->
     </div>
     @if ($showChart4)
         <div class="my-10 flex justify-end space-x-2">
             <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                 onclick="downloadImage3()">
                 <span class="font-semibold"><i class="fa-regular fa-image px-1"></i></span>
                 {{ __('messages.download') }}
             </x-button>
             <x-button class="bg-purple-600 hover:bg-purple-700 shadow-lg hover:shadow-purple-500/50"
                 onclick="generatePDF3()">
                 <span class="font-semibold"><i class="fa-regular fa-file-pdf px-1"></i></span>
                 {{ __('messages.convert_to_pdf') }}
             </x-button>
             <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50" wire:click="resetFields4"
                 wire:loading.attr="disabled">
                 <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                 {{ __('messages.reset_fields') }}
             </x-button>
         </div>
         <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">

                 <div wire:ignore>
                     <input type="text" id="myDatePicker3" wire:model.lazy="date_start" wire:change="updateMonthData"
                         placeholder="dd/mm/yyyy" autocomplete="off"
                         class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                 </div>

             </div>

             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <div wire:ignore>
                     <input type="text" id="myDatePicker4" wire:model.lazy="date_end" wire:change="updateMonthData"
                         placeholder="dd/mm/yyyy" autocomplete="off"
                         class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

                 </div>
             </div>

             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <select wire:model="main_category_id" wire:change="updateMonthData"
                     class="w-full text-sm dark:text-gray-800 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                     <option value="">{{ __('messages.all_categories') }}

                     </option>
                     @foreach ($mainCategoriesRender as $item)
                         <option value="{{ $item->id }}">
                             {{ $item->title }}
                         </option>
                     @endforeach
                 </select>
             </div>

             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <select wire:model="SelectMainCurrencyTypeRender" wire:change="updateMonthData"
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
         <div class="p-2">
             <div id="chart-container3" class="my-5"
                 wire:key="{{ $MonthlyChart1 }},{{ $MonthlyChart2 }},{{ $MonthlyChart3 }}">


                 <div class="grid gap-6 mb-8 md:grid-cols-2 " id="content3">
                     <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800 my-5">
                         @php
                             $currencyType =
                                 $SelectMainCurrencyTypeRender === 'Blue-ARS' ? 'ARS' : $SelectMainCurrencyTypeRender;
                         @endphp

                         <canvas id="{{ $MonthlyChart1 }}" height="200"></canvas>
                         <script>
                             var ctx = document.getElementById('{{ $MonthlyChart1 }}').getContext('2d');

                             var dataBar = {
                                 labels: [
                                     @foreach ($operationsFetchMonths as $item)
                                         "{{ Str::words($item->category_title, 2, '...') }}",
                                     @endforeach
                                 ],

                                 datasets: [{
                                     label: "",
                                     backgroundColor: [],
                                     borderColor: [],
                                     data: [],
                                 }]
                             };

                             @foreach ($operationsFetchMonths as $item)
                                 // Determine the amount based on the condition
                                 var amount =
                                     {{ $SelectMainCurrencyTypeRender === 'USD' ? $item->total_currency : $item->total_amount }};

                                 // Add the amount to the data array
                                 dataBar.datasets[0].data.push(amount);

                                 // Set background color based on main_category_id
                                 if ({{ $item->main_category_id }} === 1) {
                                     dataBar.datasets[0].backgroundColor.push("#14B8A6");
                                 } else if ({{ $item->main_category_id }} === 2) {
                                     dataBar.datasets[0].backgroundColor.push("#7e3af2");
                                 } else {
                                     dataBar.datasets[0].backgroundColor.push("#000000"); // Default color for other cases
                                 }

                                 // Set border color based on main_category_id (same logic as background color)
                                 dataBar.datasets[0].borderColor.push(dataBar.datasets[0].backgroundColor[dataBar.datasets[0].backgroundColor
                                     .length - 1]);
                             @endforeach

                             var options = {
                                 title: {
                                     display: true,
                                     text: ' ',
                                     responsive: true,
                                 },
                                 legend: {
                                     display: false,
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

                                             // Utiliza un ternario para cambiar 'Blue-ARS' a 'ARS'
                                             currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                             // Format with toLocaleString and append the currencyType
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


                         <div class="text-center justify-center mt-10 my-3 flex">
                             @php
                                 $selectedCategoryId =
                                     $main_category_id ?? ($operationsFetchMonths->first()->main_category_id ?? null);
                                 $totalCategories = [];

                                 foreach ($mainCategoriesRender as $item) {
                                     $totalCategory = 0;
                                     foreach ($operationsFetchMonths as $operationItem) {
                                         if ($operationItem->main_category_id == $item->id) {
                                             $totalCategory += $operationItem->total_currency;
                                         } else {
                                             $totalCategory += $operationItem->total_amount;
                                         }
                                     }
                                     $totalCategories[$item->id] = $totalCategory;
                                 }
                             @endphp

                             @if (!empty($totalCategories))
                                 @foreach ($mainCategoriesRender as $item)
                                     @if ($totalCategories[$item->id] > 0)
                                         <div style="display: flex; align-items: center; margin-right: 15px;">
                                             <div
                                                 style="background-color: {{ $item->id == 1 ? '#14B8A6' : '#7e3af2' }}; height: 13px; width: 40px; margin-bottom: 5px;">
                                             </div>
                                             <span
                                                 class="-mt-1 ml-2 text-xs font-bold text-center text-gray-800 capitalize dark:text-gray-300">
                                                 <span class="text-gray-500 capitalize dark:text-gray-400">
                                                     {{ $item->title }}</span>

                                             </span>
                                         </div>
                                     @endif
                                 @endforeach

                             @endif


                         </div>


                         <div class="mt-5">
                             <p class="text-xs font-bold text-center text-gray-600 capitalize   dark:text-gray-400 ">
                                 @if ($userNameSelected4)
                                     {{ $userNameSelected4->name }}
                                 @endif
                                 @if ($selectedMonthName)
                                     - {{ $selectedMonthName }}
                                 @endif
                                 @if ($selectedYear3)
                                     - {{ $selectedYear3 }}
                                 @endif
                             </p>
                         </div>

                     </div>


                     <div class="min-w-0 p-4 bg-white rounded-lg capitalize shadow-xs dark:bg-gray-800 my-5">

                         <!-- Agrega un elemento canvas para el gráfico -->
                         <canvas id="{{ $MonthlyChart2 }}"></canvas>
                         <script>
                             // Inicializar totales de cada categoría
                             var totalCategory1 = 0;
                             var totalCategory2 = 0;
                             var amount = 0;


                             @foreach ($operationsFetchMonths as $item)

                                 @if ($SelectMainCurrencyTypeRender && $SelectMainCurrencyTypeRender === 'USD')
                                     amount = {{ $item->total_currency }};
                                 @else
                                     amount = {{ $item->total_amount }};
                                 @endif

                                 @if ($item->main_category_id == 1)
                                     totalCategory1 += amount;
                                 @elseif ($item->main_category_id == 2)
                                     totalCategory2 += amount;
                                 @endif
                             @endforeach
                             // Obtener los nombres de categoría
                             var categoryName = '';
                             var categoryName2 = '';

                             @foreach ($mainCategoriesRender as $category)
                                 @if ($category->id == 1)
                                     categoryName = "{{ $category->title }}";
                                 @elseif ($category->id == 2)
                                     categoryName2 = "{{ $category->title }}";
                                 @endif
                             @endforeach

                             // Verificar si solo hay una categoría presente
                             var isSingleCategory = (totalCategory1 === 0 || totalCategory2 === 0);

                             // Configuración del gráfico de dona
                             var ctx = document.getElementById('{{ $MonthlyChart2 }}').getContext('2d');
                             var labels = [];

                             // Verificar si solo hay una categoría presente
                             if (totalCategory1 > 0 && totalCategory2 > 0) {
                                 // Ambas categorías están presentes
                                 labels = [categoryName + ' ' + totalCategory1.toLocaleString('en-US') + ' $', categoryName2 + ' ' +
                                     totalCategory2.toLocaleString('en-US') + ' $'
                                 ];
                             } else if (totalCategory1 > 0) {
                                 // Solo la primera categoría está presente
                                 labels = [categoryName + ' ' + totalCategory1.toLocaleString('en-US') + ' $'];
                             } else if (totalCategory2 > 0) {
                                 // Solo la segunda categoría está presente
                                 labels = [categoryName2 + ' ' + totalCategory2.toLocaleString('en-US') + ' $'];
                             }

                             var myChart = new Chart(ctx, {
                                 type: 'doughnut',
                                 data: {
                                     labels: "",
                                     datasets: [{
                                         label: '# of ',
                                         data: [totalCategory1, totalCategory2],
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

                                             if (isSingleCategory) {
                                                 // Mostrar solo el total si hay una sola categoría
                                                 var totalText = (totalCategory1 !== 0 ? totalCategory1.toLocaleString('en-US') +
                                                     ' $' : totalCategory2.toLocaleString('en-US') + ' $');
                                                 ctx.fillStyle = '#eab308';
                                                 ctx.font = '17px Roboto';
                                                 ctx.fillText(totalText, centerX, centerY);

                                                 // Agregar el texto "of Income" o "of Expenses" debajo del porcentaje
                                                 ctx.fillStyle = '#808080'; // Gris
                                                 ctx.font = '16px Roboto';
                                                 ctx.fillText((totalCategory1 > totalCategory2) ? 'of ' + categoryName : 'of ' +
                                                     categoryName2,
                                                     centerX, centerY + 30);

                                             } else {
                                                 // Mostrar el porcentaje si hay dos categorías
                                                 var totalPercentage = ((totalCategory2 / totalCategory1) * 100).toFixed(0);
                                                 ctx.fillStyle = '#eab308';
                                                 ctx.font = '28px Roboto';
                                                 ctx.fillText(totalPercentage + '%', centerX, centerY);

                                                 // Agregar el texto "of Income" o "of Expenses" debajo del porcentaje
                                                 ctx.fillStyle = '#808080'; // Gris
                                                 ctx.font = '16px Roboto';
                                                 ctx.fillText((totalCategory1 > totalCategory2) ? 'of ' + categoryName : 'of ' +
                                                     categoryName2,
                                                     centerX, centerY + 30);
                                             }
                                         }
                                     },
                                     hover: {
                                         animationDuration: 0 // Evitar que la animación del porcentaje se reinicie al hacer hover
                                     },
                                     tooltips: {
                                         callbacks: {
                                             label: function(tooltipItem, data) {
                                                 var label = (tooltipItem.index === 0) ? 'Total ' + categoryName :
                                                     'Total ' + categoryName2;
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



                         <div class="text-center justify-center mt-10 my-3 flex">
                             @php
                                 $selectedCategoryId =
                                     $main_category_id ?? ($operationsFetchMonths->first()->main_category_id ?? null);
                                 $totalCategories = [];

                                 foreach ($mainCategoriesRender as $item) {
                                     $totalCategory = 0;
                                     foreach ($operationsFetchMonths as $operationItem) {
                                         // Determine the amount based on the condition
                                         $amount =
                                             $SelectMainCurrencyTypeRender === 'USD'
                                                 ? $operationItem->total_currency
                                                 : $operationItem->total_amount;

                                         if ($operationItem->main_category_id == $item->id) {
                                             $totalCategory += $amount;
                                         }
                                     }
                                     $totalCategories[$item->id] = $totalCategory;
                                 }
                             @endphp


                             @if (!empty($totalCategories))
                                 @foreach ($mainCategoriesRender as $item)
                                     @if ($totalCategories[$item->id] > 0)
                                         <div style="display: flex; align-items: center; margin-right: 15px;">
                                             <div
                                                 style="background-color: {{ $item->id == 1 ? '#14B8A6' : '#7e3af2' }}; height: 13px; width: 40px; margin-bottom: 5px;">
                                             </div>
                                             <span
                                                 class="-mt-1 ml-2 text-xs font-bold text-center text-gray-800 capitalize dark:text-gray-300">
                                                 <span class="text-gray-500 capitalize dark:text-gray-400">
                                                     {{ $item->title }}</span>
                                                 {{ number_format($totalCategories[$item->id], 0) }}
                                                 {{ $currencyType }}
                                             </span>
                                         </div>
                                     @endif
                                 @endforeach

                             @endif

                             @if ($budget)
                                 <div style="display: flex; align-items: center; margin-right: 15px;">
                                     <div
                                         style="background-color: #22c55e; height: 13px; width: 40px; margin-bottom: 5px;">
                                     </div>
                                     <span
                                         class="-mt-1 ml-2 text-xs font-bold text-center text-gray-800 capitalize dark:text-gray-300">
                                         <span class="text-gray-500 capitalize dark:text-gray-400">
                                             Budget</span>
                                         @if (is_numeric($budgetData))
                                             {{ number_format($budgetData, 0, '.', ',') }} {{ $currencyType }}
                                         @else
                                             {{ $budgetData }}
                                         @endif
                                     </span>

                                 </div>
                             @endif
                         </div>

                         <div class="mt-5">
                             <p class="text-xs font-bold text-center text-gray-600 capitalize   dark:text-gray-400 ">
                                 @if ($userNameSelected4)
                                     {{ $userNameSelected4->name }}
                                 @endif
                                 @if ($selectedMonthName)
                                     - {{ $selectedMonthName }}
                                 @endif
                                 @if ($selectedYear3)
                                     - {{ $selectedYear3 }}
                                 @endif
                             </p>
                         </div>

                     </div>

                     <div class="min-w-0 p-4 bg-white rounded-lg capitalize shadow-xs dark:bg-gray-800 my-5">

                         <canvas id="{{ $MonthlyChart3 }}" class="my-10"></canvas>


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

                             // Tomando como ejemplo que $topTenOperations contiene los datos obtenidos
                             var topTenOperations = @json($this->topTenOperations);

                             // Limita la longitud de las descripciones a 2 palabras
                             var labels = topTenOperations.map(item => limitWords(item.category_title, 2));

                             var data = topTenOperations.map(function(item) {
                                 // Determinar la cantidad según la condición
                                 var amount =
                                     {{ $SelectMainCurrencyTypeRender === 'USD' ? 'item.total_currency' : 'item.total_amount' }};
                                 return amount;
                             });


                             var ctx = document.getElementById('{{ $MonthlyChart3 }}').getContext('2d');
                             var myChart = new Chart(ctx, {
                                 type: 'horizontalBar',
                                 data: {
                                     labels: labels,
                                     datasets: [{
                                         label: 'Top 10 Operations  {{ $currencyType }}',
                                         data: data,
                                         backgroundColor: [
                                             @foreach ($topTenOperations as $item)
                                                 @if ($item->main_category_id == 1)
                                                     "#14B8A6",
                                                 @elseif ($item->main_category_id == 2)
                                                     "#7e3af2",
                                                 @else
                                                     "#000000", // Color por defecto para otros casos
                                                 @endif
                                             @endforeach
                                         ],
                                         borderColor: [
                                             @foreach ($topTenOperations as $item)
                                                 @if ($item->main_category_id == 1)
                                                     "#14B8A6",
                                                 @elseif ($item->main_category_id == 2)
                                                     "#7e3af2",
                                                 @else
                                                     "#000000", // Color por defecto para otros casos
                                                 @endif
                                             @endforeach
                                         ],
                                         borderWidth: 1
                                     }]
                                 },
                                 options: {
                                     legend: {
                                         display: true,
                                         position: 'top',
                                         align: 'center',
                                         labels: {
                                             boxWidth: 0, // Sin caja alrededor del texto
                                             usePointStyle: false,
                                             fontColor: 'black',
                                             fontSize: 14,
                                         },
                                         onClick: function() {
                                             // Evita que se oculte la leyenda al hacer clic en ella
                                         }
                                     },
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

                         <div class="text-center justify-center mt-10 my-3 flex">
                             @php
                                 $selectedCategoryId =
                                     $main_category_id ?? ($operationsFetchMonths->first()->main_category_id ?? null);
                                 $totalCategories = [];

                                 foreach ($mainCategoriesRender as $item) {
                                     $totalCategory = 0;
                                     foreach ($operationsFetchMonths as $operationItem) {
                                         // Determine the amount based on the condition
                                         $amount =
                                             $SelectMainCurrencyTypeRender === 'USD'
                                                 ? $operationItem->operation_currency_total
                                                 : $operationItem->operation_amount;

                                         if ($operationItem->main_category_id == $item->id) {
                                             $totalCategory += $amount;
                                         }
                                     }
                                     $totalCategories[$item->id] = $totalCategory;
                                 }
                             @endphp

                             @if (!empty($totalCategories))
                                 @foreach ($mainCategoriesRender as $item)
                                     @if ($totalCategories[$item->id] > 0)
                                         <div style="display: flex; align-items: center; margin-right: 15px;">
                                             <div
                                                 style="background-color: {{ $item->id == 1 ? '#14B8A6' : '#7e3af2' }}; height: 13px; width: 40px; margin-bottom: 5px;">
                                             </div>
                                             <span
                                                 class="-mt-1 ml-2 text-xs font-bold text-center text-gray-800 capitalize dark:text-gray-300">
                                                 <span class="text-gray-500 capitalize dark:text-gray-400">
                                                     {{ $item->title }}</span>

                                             </span>
                                         </div>
                                     @endif
                                 @endforeach

                             @endif





                         </div>



                         <div class="mt-5">
                             <p class="text-xs font-bold text-center text-gray-600 capitalize   dark:text-gray-400 ">
                                 @if ($userNameSelected4)
                                     {{ $userNameSelected4->name }}
                                 @endif
                                 @if ($selectedMonthName)
                                     - {{ $selectedMonthName }}
                                 @endif
                                 @if ($selectedYear3)
                                     - {{ $selectedYear3 }}
                                 @endif
                             </p>
                         </div>
                     </div>

                 </div>



             </div>
         </div>
     @endif
 </div>
 <script>
     document.addEventListener('livewire:load', function() {
         Livewire.on('initializeFlatpickr', function() {
             flatpickr("#myDatePicker3", {
                 locale: "es",
                 altInput: true,
                 altFormat: "j F, Y",
                 dateFormat: "Y-m-d", // Set to the format you expect the backend to receive
                 allowInput: true,
                 onClose: function(selectedDates1, dateStr1, instance1) {
                     @this.set('date_start', dateStr1);
                 }
             });

             flatpickr("#myDatePicker4", {
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
     function generatePDF3() {
         // Obtén los valores de los atributos de datos
         const userInfoElement = document.getElementById('userInfo3');
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
             html2canvas(document.getElementById('content3')).then((canvas) => {
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
                 const imgHeight = 170;
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
                 doc.text('Monthly General Report ', textLeftMargin, textBelowLogoY);

                 doc.setTextColor(textColor);
                 doc.text(' ' + userNameSelected + ' ' + capitalizedSelectedMonthName + ' ' + selectedYear,
                     textLeftMargin + doc
                     .getTextWidth(
                         'Monthly General Report '), textBelowLogoY);


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
                     `Monthly-General-Report-Chart-${userNameSelected}-${capitalizedSelectedMonthName}-${formattedDate}.pdf`;

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
     function downloadImage3() {
         const userInfoElement = document.getElementById('userInfo3');
         const userNameSelected = userInfoElement.getAttribute('data-username');
         const reportDate = userInfoElement.getAttribute('data-report');
         const selectedMonthName = userInfoElement.getAttribute('data-month');
         // Verifica si el mes está seleccionada
         if (selectedMonthName) {
             // Usa html2canvas para convertir el contenido del div en una imagen
             html2canvas(document.getElementById('content3')).then((canvas) => {


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
                     `Monthly-General-Report-Image-${userNameSelected}-${formattedDate}.jpg`; // Cambia a '.png' si prefieres PNG
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
