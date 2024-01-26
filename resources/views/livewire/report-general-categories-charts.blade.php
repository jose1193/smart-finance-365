 <div x-show="activeTab === '2'">
     <!-- REPORT GENERAL CATEGORIES TABLE  -->
     <div id="between-dates-chart-table">
         <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <div wire:ignore>
                     <select wire:model="selectedUser2" wire:change="updateCategoriesData" id="selectUserChart2"
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

             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <div wire:ignore>
                     <select wire:model="selectedCategoryId" id="selectCategoryChart" wire:change="updateCategoriesData"
                         style="width: 100%"
                         class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                         <option value="">Select Category</option>
                         @foreach ($categoriesRender as $formattedCategory)
                             <optgroup label="{{ $formattedCategory['mainCategoryTitle'] }}">
                                 @foreach ($formattedCategory['categories'] as $category)
                                     <option value="{{ $category['id'] }}">
                                         {{ $category['category_name'] }}
                                     </option>
                                 @endforeach
                             </optgroup>
                         @endforeach
                     </select>
                 </div>
             </div>

             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                 <div wire:ignore>
                     <select wire:model="selectedYear2" id="selectYearChart2" wire:change="updateCategoriesData"
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

         @if ($showChart2)
             <div class="my-10 flex justify-end space-x-2">

                 <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                     wire:click="exportToExcel" wire:loading.attr="disabled">
                     <span class="font-semibold"><i class="fa-regular fa-image px-1"></i></span>
                     Download
                 </x-button>
                 <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50"
                     wire:click="resetFields2" wire:loading.attr="disabled">
                     <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                     Reset Fields
                 </x-button>
             </div>
             <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">


                 <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                     <select wire:model="SelectMainCurrencyTypeRender" wire:change="updateCategoriesData"
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
             <div id="chart-container2" class="my-5"
                 wire:key="chart-{{ $selectedUser2 }}-{{ $selectedCategoryId }}-{{ $selectedYear2 }}-{{ uniqid() }}">

                 <div class="grid gap-6 mb-8 md:grid-cols-2">
                     <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                         <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                             @if ($userNameSelected2)
                                 {{ $userNameSelected2->name }}
                             @else
                                 User Not Selected
                             @endif
                         </h4>

                         <canvas id="myChartGeneral2" height="200"></canvas>

                     </div>

                 </div>
                 <script>
                     @if ($categoryNameSelected)
                         var userName = "{{ $categoryNameSelected->category_name }}";
                     @else
                         var userName = "";
                     @endif
                     var ctx = document.getElementById('myChartGeneral2').getContext('2d');

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
                                     var datasetLabel = data.datasets[tooltipItem.datasetIndex].label;
                                     var value = tooltipItem.value;
                                     var currencyType = '{{ $SelectMainCurrencyTypeRender }}';

                                     // Utiliza un ternario para cambiar 'Blue-ARS' a 'ARS'
                                     currencyType = (currencyType === 'Blue-ARS') ? ' ARS' : currencyType;

                                     // Aplicar formato con toLocaleString
                                     if (!isNaN(value)) {
                                         value = Number(value).toLocaleString('en-US') + ' ' + currencyType;
                                     }

                                     return datasetLabel + ': ' + value;
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
         @endif
     </div>
     <!-- END REPORT GENERAL CATEGORIES TABLE  -->
 </div>
