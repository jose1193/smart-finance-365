 <div x-show="activeTab === '3'">
     <!-- Chart JS -->
     <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
         <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
             <div wire:ignore>
                 <select wire:model="selectedUser4" wire:change="updateMonthData" id="selectUserChart4" style="width: 100%"
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
                 <select wire:model="selectedMonth" wire:change="updateMonthData" id="selectMonthChart"
                     style="width: 100%"
                     class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                 <select wire:model="selectedYear3" id="selectYearChart3" wire:change="updateMonthData"
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
     @if ($showChart4)
         <div class="my-10 flex justify-end space-x-2">
             <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                 wire:click="exportToExcel" wire:loading.attr="disabled">
                 <span class="font-semibold"><i class="fa-regular fa-image px-1"></i></span>
                 Download
             </x-button>
             <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50" wire:click="resetFields4"
                 wire:loading.attr="disabled">
                 <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                 Reset Fields
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
                     <option value="">All Categories
                     </option>
                     @foreach ($mainCategoriesRender as $item)
                         <option value="{{ $item->id }}">
                             {{ $item->title }}
                         </option>
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
         <div id="chart-container3" class="my-5"
             wire:key="chart-{{ $selectedUser4 }}-{{ $selectedMonth }}-{{ $selectedYear3 }}-{{ uniqid() }}">


             <div class="grid gap-6 mb-8 md:grid-cols-2">
                 <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                     <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                         Bars
                     </h4>

                     <canvas id="myChartGeneral4" height="200"></canvas>

                 </div>



             </div>

             <script>
                 var ctx = document.getElementById('myChartGeneral4').getContext('2d');

                 var dataBar = {
                     labels: [

                         @foreach ($operationsFetchMonths as $item)
                             "{{ Str::words($item->category_title, 2, '...') }}",
                         @endforeach
                     ],

                     datasets: [{
                             label: "",
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
             </script>

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
