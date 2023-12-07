 <div x-show="activeTab === '5'">
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
         <div id="chart-container5" class="my-5"
             wire:key="chart-{{ $selectedUser5 }}-{{ $selectedYear4 }}-{{ uniqid() }}">

             <div class="grid gap-6 mb-8 md:grid-cols-2">
                 <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                     <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                         Bars
                     </h4>

                     <canvas id="myChartGeneral5" height="200"></canvas>


                 </div>

             </div>
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
                         }, {
                             label: "{{ $categoryName }}",
                             backgroundColor: "#0694a2",
                             borderColor: "#0694a2",
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
