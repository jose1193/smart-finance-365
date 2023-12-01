  <div x-show="activeTab === '3'">
      <!-- REPORT GENERAL BETWEEN DATE TABLE  -->
      <div id="between-dates-chart-table">
          <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10 ">
              <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                  <div wire:ignore>
                      <select wire:model="selectedUser3" id="selectUserChart3" wire:change="updateBetweenData"
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


              <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 " wire:ignore>
                  <input type="text" id="myDatePicker" readonly wire:model="date_start"
                      wire:change="updateBetweenData" placeholder="dd/mm/yyyy" autocomplete="off"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">


              </div>

              <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 " wire:ignore>
                  <input type="text" id="myDatePicker2" placeholder="dd/mm/yyyy" autocomplete="off" readonly
                      wire:model="date_end" wire:change="updateBetweenData"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              </div>

          </div>
          @if ($date_start)
              <p>Date Start:
                  <span class="text-green-700 ml-2 font-semibold">
                      {{ \Carbon\Carbon::parse($date_start)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                  </span>
              </p>
          @endif

          @if ($date_end)
              <p>Date End:
                  <span class="text-green-700 ml-2 font-semibold">
                      {{ \Carbon\Carbon::parse($date_end)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                  </span>
              </p>
          @endif
          @if ($date_start && $date_end && $date_start > $date_end)
              <p class="text-red-700 mt-2 text-center font-semibold">Error: La fecha de inicio no puede
                  ser posterior a
                  la fecha de finalización.</p>
          @endif
          @if ($showChart3)
              <div class="my-10 flex justify-end space-x-2">

                  <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                      wire:click="exportToExcel" wire:loading.attr="disabled">
                      <span class="font-semibold"><i class="fa-regular fa-image px-1"></i></span>
                      Download
                  </x-button>
                  <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50"
                      wire:click="resetFields3" wire:loading.attr="disabled">
                      <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                      Reset Fields
                  </x-button>
              </div>
              <div id="chart-container3" class="my-5"
                  wire:key="chart-{{ $selectedUser3 }}-{{ $date_start }}-{{ $date_end }}-{{ uniqid() }}">

                  <div class="grid gap-6 mb-8 md:grid-cols-2">
                      <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                          <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                              Bars
                          </h4>

                          <canvas id="myChartGeneral3" height="200"></canvas>
                          <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                              <!-- Chart legend -->
                              <div class="flex items-center">
                                  <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                  <span
                                      class="font-semibold">{{ $totalIncomeFormatted = number_format(array_sum($incomeData3), 0, '.', ',') }}
                                      $</span>
                              </div>
                              <div class="flex items-center">
                                  <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                  <span
                                      class="font-semibold">{{ $totalExpenseFormatted = number_format(array_sum($expenseData3), 0, '.', ',') }}
                                      $</span>
                              </div>
                          </div>




                      </div>



                      <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                          <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                              Lines
                          </h4>
                          <canvas id="line3" height="200"></canvas>
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
                      var ctx = document.getElementById('myChartGeneral3').getContext('2d');

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
                                  data: @json($incomeData3), // Datos de ingresos

                              },
                              {
                                  label: "{{ $categoryName2 }}",
                                  backgroundColor: "#7e3af2",
                                  borderColor: "#7e3af2",
                                  data: @json($expenseData3), // Datos de ingresos
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
                                      data: @json($incomeData3), // Datos de ingresos
                                      fill: false,
                                  },
                                  {
                                      label: "{{ $categoryName2 }}",
                                      fill: false,
                                      backgroundColor: "#7e3af2",
                                      borderColor: "#7e3af2",
                                      data: @json($expenseData3), // Datos de gastos
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
                      const lineCtx = document.getElementById("line3");
                      window.myLine = new Chart(lineCtx, lineConfig);
                  </script>

              </div>
              <script>
                  document.addEventListener('livewire:load', function() {
                      Livewire.on('dataUpdated', function() {
                          console.log('dataUpdated'); // Verifica si esta función se ejecuta
                          // Resto del código para actualizar las gráficas
                      });
                  });
              </script>
          @endif

      </div>
      <!-- END REPORT GENERAL BETWEEN DATE TABLE  -->
  </div>
  <script>
      document.addEventListener('livewire:load', function() {

          flatpickr("#myDatePicker", {
              locale: "es",
              altInput: true,
              altFormat: "j F, Y",
              dateFormat: "Y-m-d", // Set to the format you expect the backend to receive
              allowInput: true,
              onClose: function(selectedDates1, dateStr1, instance1) {
                  @this.set('date_start', dateStr1);
              }
          });

          flatpickr("#myDatePicker2", {
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
  </script>
