  <div x-show="activeTab === '4'">
      <!-- REPORT MONTHS TABLE  -->
      <div id="report-table">
          <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
              <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                  <select wire:model="selectedUser4" wire:change="updateMonthData"
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
                  <select wire:model="selectedMonth" wire:change="updateMonthData"
                      class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                      <option value="">Select Month</option>
                      @foreach ($this->months() as $month)
                          <option value="{{ $month['number'] }}">{{ $month['number'] }} -
                              {{ $month['name'] }}</option>
                      @endforeach
                  </select>
              </div>


              <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                  <select wire:model="selectedYear3" wire:change="updateMonthData"
                      class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                      <option value="">Select Year</option>
                      @foreach ($years as $year)
                          <option value="{{ $year }}">{{ $year }}</option>
                      @endforeach
                  </select>
              </div>


          </div>
          @if ($showData4)
              <div class="my-10 flex justify-end space-x-2">
                  <x-button wire:click="openModal4">
                      <span class="font-semibold"><i class="fa-solid fa-user-group px-1"></i></i></span>
                      Send Report
                  </x-button>
                  <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                      wire:click="exportToExcel4" wire:loading.attr="disabled">
                      <span class="font-semibold"><i class="fa-solid fa-file-excel px-1"></i></span>
                      Download
                  </x-button>
                  <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50"
                      wire:click="resetFields4" wire:loading.attr="disabled">
                      <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                      Reset Fields
                  </x-button>
              </div>

              @if ($isOpen4)
                  <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
                      <div
                          class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                          <div class="fixed inset-0 transition-opacity">
                              <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                          </div>
                          <!-- This element is to trick the browser into centering the modal contents. -->
                          <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>?
                          <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                              role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                              <form>
                                  <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                      <div class="">
                                          <div class="mb-4">
                                              <label for="exampleFormControlInput1"
                                                  class="block text-gray-700 text-sm font-bold mb-2">
                                                  User Email:</label>
                                              <select wire:model="emails_user4"
                                                  class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                                  <option value=""></option>

                                                  @foreach ($emails->groupBy('name') as $nameUser => $groupedEmails)
                                                      <optgroup label="{{ $nameUser }}">
                                                          @foreach ($groupedEmails as $email)
                                                              <option value="{{ $email->email }}">
                                                                  {{ $email->email }}
                                                              </option>
                                                          @endforeach
                                                      </optgroup>
                                                  @endforeach
                                              </select>

                                              @error('emails_user4')
                                                  <span class="text-red-500">{{ $message }}</span>
                                              @enderror
                                          </div>

                                      </div>
                                  </div>
                                  <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                      <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                          <button wire:click.prevent="emailStore4()" type="button"
                                              class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                              Send
                                          </button>
                                      </span>
                                      <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                          <button wire:click="closeModal4()" type="button"
                                              class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                              Cancel
                                          </button>
                                      </span>
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
              @endif

              <!-- Tables -->
              <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                  <div class="w-full overflow-x-auto">
                      <table class="w-full whitespace-no-wrap" id="tableId4">
                          <thead>
                              <tr
                                  class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                  <th class="px-4 py-3">Nro</th>
                                  <th class="px-4 py-3">Main Category</th>
                                  <th class="px-4 py-3">Category</th>
                                  <th class="px-4 py-3">Description</th>
                                  <th class="px-4 py-3">Operation ARS</th>
                                  <th class="px-4 py-3">Currency</th>
                                  <th class="px-4 py-3">Total Currency</th>
                                  <th class="px-4 py-3">Estatus</th>
                              </tr>
                          </thead>
                          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">


                              @foreach ($operationsFetchMonths as $item)
                                  <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                      <td class="px-4 py-3 text-center">
                                          {{ $loop->iteration }}
                                      </td>


                                      <td class="px-4 py-3 text-center">
                                          {{ $item->main_category_title }}</td>
                                      <td class="px-4 py-3 text-center">
                                          {{ $item->category_title }}
                                      </td>
                                      <td class="px-4 py-3 text-center">
                                          {{ Str::words($item->operation_description, 2, '...') }}

                                      </td>
                                      <td class="px-4 py-3 text-center">
                                          $
                                          {{ $formatted_amount = number_format($item->operation_amount, 0, '.', ',') }}
                                      </td>
                                      <td class="px-4 py-3 text-center">
                                          $
                                          {{ $formatted_amount = number_format($item->operation_currency, 0, '.', ',') }}
                                      </td>
                                      <td class="px-4 py-3 text-center">
                                          {{ $formatted_amount = number_format($item->operation_currency_total, 0, '.', ',') }}
                                          $</td>
                                      <td class="px-4 py-3 text-center">
                                          @if ($item->operation_status === '1')
                                              <span
                                                  class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                  {{ $item->status_description }}
                                              </span>
                                          @elseif ($item->operation_status === '3')
                                              <span
                                                  class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                                  {{ $item->status_description }}
                                              </span>
                                          @elseif ($item->operation_status === '2')
                                              <span
                                                  class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600">
                                                  {{ $item->status_description }}
                                              </span>
                                          @else
                                              <!-- Otro caso por defecto si no coincide con 'admin' ni 'user' -->
                                              <span
                                                  class="px-2 py-1 font-semibold leading-tight text-white bg-red-700 rounded-full dark:bg-gray-700 dark:text-gray-100">
                                                  {{ $item->status_description }}
                                              </span>
                                          @endif
                                      </td>
                                  </tr>
                              @endforeach

                              <!-- Fila adicional para mostrar el nombre del usuario -->
                              <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                  <td class="px-4 py-3 text-center font-semibold">

                                  </td>
                                  <td class="px-4 py-3 text-center font-semibold">
                                      @if ($userNameSelected4)
                                          {{ $userNameSelected4->name }}
                                      @else
                                      @endif
                                  </td>
                                  <td class="px-4 py-3 text-center font-semibold">
                                      @if ($selectedMonthName)
                                          {{ $selectedMonthName }}
                                      @endif

                                  </td>
                                  <td class="px-4 py-3 text-center font-semibold">
                                      {{ $selectedYear3 }}</td>
                                  <td class="px-4 py-3 text-center font-semibold">$
                                      {{ $formatted_amount = number_format($totalMonthAmount, 0, '.', ',') }}
                                  </td>
                                  <td class="px-4 py-3 text-center font-semibold">

                                  </td>
                                  <td class="px-4 py-3 text-center font-semibold">
                                      {{ $formatted_amount = number_format($totalMonthAmountCurrency, 0, '.', ',') }}
                                      $
                                  </td>
                                  <td class="px-4 py-3 text-center font-semibold">

                                  </td>
                              </tr>
                          </tbody>
                      </table>
                  </div>

              </div>
          @endif
      </div>
      <script>
          document.addEventListener('livewire:load', function() {

              Livewire.on('exportTableToExcel4', function() {
                  // Lógica para exportar la tabla a Excel (usando table2excel o la biblioteca de tu elección)

                  // Por ejemplo:
                  $("#tableId4").table2excel({
                      exclude: ".no-export",
                      name: "Worksheet Name",
                      filename: "month-report"
                  });


              });

              Livewire.on('emailSent4', function() {
                  // Lógica para manejar el evento de correo enviado
                  // Esto podría ser un mensaje de confirmación al usuario, etc.
              });
          });
      </script>

      <!-- END REPORT MONTHS TABLE  -->
  </div>