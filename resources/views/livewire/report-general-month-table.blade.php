  <div x-show="activeTab === '3'">
      <!-- REPORT MONTHS TABLE  -->
      <div id="report-table">
          <!--INCLUDE ALERTS MESSAGES-->
          <x-message-success />
          <!-- END INCLUDE ALERTS MESSAGES-->
          <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
              <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">

                  @if (auth()->user()->hasRole('Admin'))

                      <div wire:ignore>
                          <select id="selectUser4" style="width: 100%" wire:model="selectedUser4"
                              wire:change="updateMonthData">
                              <option value="">{{ __('messages.table_columns_categories.select_a_user') }}</option>

                              @foreach ($users as $user)
                                  <option value="{{ $user->id }}">{{ $user->name }}
                                  </option>
                              @endforeach
                          </select>
                      </div>
                  @else
                      <select wire:model="selectedUser4" wire:change="updateMonthData"
                          class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                          <option value="">{{ __('messages.table_columns_categories.select_a_user') }}</option>

                          <option value="{{ auth()->user()->id }}">{{ auth()->user()->name }}
                          </option>


                      </select>

                  @endif
              </div>

              <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0">
                  <div wire:ignore>
                      <select id="selectMonth" wire:model="selectedMonth" wire:change="updateMonthData"
                          style="width: 100%">
                          <option value="">{{ __('messages.select_a_month') }}</option>
                          @foreach ($this->months() as $month)
                              <option value="{{ $month['number'] }}">{{ $month['number'] }} -
                                  {{ trans('messages.month_names.' . $month['name']) }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>

              <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                  <div wire:ignore>
                      <select wire:model="selectedYear3" style="width:100%" id="selectYear3"
                          wire:change="updateMonthData">
                          <option value="">{{ __('messages.select_a_year') }}</option>

                          @foreach ($years as $year)
                              <option value="{{ $year }}">{{ $year }}</option>
                          @endforeach
                      </select>
                  </div>
              </div>


          </div>
          @if ($isOpen4)
              <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
                  <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
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
                                              {{ __('messages.user_email') }}:</label>
                                          <div wire:ignore>
                                              @if (count($emails) > 0)
                                                  <select multiple id="select4EmailsUser" style="width: 100%"
                                                      wire:model="emails_user4">


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
                                              @else
                                                  <a href="{{ route('emails') }}" target="_blank"
                                                      class="text-blue-600 hover:text-blue-700 font-semibold"
                                                      wire:click="closeModal4()">
                                                      {{ __('messages.register_and_submit_reports') }}
                                                  </a>

                                              @endif
                                          </div>

                                          <script>
                                              $(document).ready(function() {
                                                  $('#select4EmailsUser').select2();

                                                  // Escucha el cambio en Select2 y actualiza Livewire para el selectUserAssignSubcategory
                                                  $('#select4EmailsUser').on('change', function(e) {
                                                      const selectedEmails = $(this).val();
                                                      const index = $(this).data('index');
                                                      @this.set('emails_user4', selectedEmails);

                                                      // Add this line to refresh the Livewire component without reloading the page
                                                      @this.call('updateMonthData');
                                                  });
                                              });
                                          </script>

                                          @error('emails_user4')
                                              <span class="text-red-500">{{ $message }}</span>
                                          @enderror
                                      </div>

                                  </div>
                              </div>
                              <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                  <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                      <button wire:click.prevent="emailStore4()" wire:loading.attr="disabled"
                                          wire:target="emailStore4" type="button"
                                          class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                          {{ __('messages.send') }}
                                      </button>
                                  </span>
                                  <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                      <button wire:click="closeModal4()" type="button"
                                          class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                          {{ __('messages.button_cancel') }}
                                      </button>
                                  </span>
                              </div>
                          </form>
                      </div>
                  </div>
              </div>
          @endif
          @if ($showData4)
              <div class="my-10 flex justify-end space-x-2">
                  <x-button wire:click="openModal4">
                      <span class="font-semibold"><i class="fa-solid fa-user-group px-1"></i></i></span>
                      {{ __('messages.send_report') }}
                  </x-button>
                  <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                      wire:click="exportToExcel4" wire:loading.attr="disabled">
                      <span class="font-semibold"><i class="fa-solid fa-file-excel px-1"></i></span>
                      {{ __('messages.download') }}
                  </x-button>
                  <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50"
                      wire:click="resetFields4" wire:loading.attr="disabled">
                      <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                      {{ __('messages.reset_fields') }}
                  </x-button>
              </div>



              <!-- Tables -->
              @if ($date_start && $date_end && $date_start > $date_end)
                  <p class="text-red-700 mt-2 text-center font-semibold">{{ __('messages.start_date_after_end_date') }}
                  </p>
              @endif
              <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                  <div class="w-full overflow-x-auto">
                      <div class="flex items-center  w-full space-x-3 mt-5">
                          <div wire:ignore>
                              <input type="text" id="myDatePicker3" wire:model.lazy="date_start" readonly
                                  wire:change="updateMonthData" placeholder="dd/mm/yyyy" autocomplete="off"
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

                          </div>
                          <div wire:ignore>
                              <input wire:ignore type="text" id="myDatePicker4" readonly wire:model.lazy="date_end"
                                  wire:change="updateMonthData" placeholder="dd/mm/yyyy" autocomplete="off"
                                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                          </div>
                          <div>
                              <select wire:model="main_category_id" wire:change="updateMonthData"
                                  class="w-full text-sm dark:text-gray-800 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                  <option value="">{{ __('messages.all_categories') }}
                                  </option>
                                  @foreach ($mainCategoriesRender as $item)
                                      <option value="{{ $item->id }}">
                                          {{ $item->title }}
                                      </option>
                                  @endforeach
                                  <option value="No Category Income">
                                      {{ __('messages.no_category_income') }}
                                  </option>
                                  <option value="No Category Expense">
                                      {{ __('messages.no_category_expense') }}
                                  </option>
                              </select>
                          </div>
                          <div>
                              <select wire:model="SelectMainCurrencyTypeRender" wire:change="updateMonthData"
                                  class="w-full text-sm dark:text-gray-800 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">

                                  <option value="USD">USD</option>
                                  @foreach ($mainCurrencyTypeRender ?? [] as $currencyType)
                                      @php
                                          // Si es 'Blue-ARS', cambiarlo a 'ARS'
                                          $displayCurrency = $currencyType == 'Blue-ARS' ? 'ARS' : $currencyType;
                                      @endphp
                                      <option value="{{ $currencyType }}">{{ $displayCurrency }}
                                      </option>
                                  @endforeach


                              </select>
                          </div>


                          @php
                              $currencyType =
                                  $SelectMainCurrencyTypeRender === 'Blue-ARS' ? 'ARS' : $SelectMainCurrencyTypeRender;
                          @endphp
                      </div>

                      <!-- PAGINATOR JQUERY START -->
                      <div class="my-5 ">
                          <label for="perPage"
                              class="text-gray-800 dark:text-gray-300 mr-1 ">{{ __('messages.show') }}</label>
                          <select id="per-page"
                              class="bg-white p-2 dark:border-gray-700  dark:text-gray-300 dark:bg-gray-800">
                              <option value="10">10</option>
                              <option value="25">25</option>
                              <option value="50">50</option>
                              <option value="100">100</option>
                              <option value="200">200</option>
                          </select>
                          <label for="perPage"
                              class="text-gray-800 dark:text-gray-300 ml-1 ">{{ __('messages.entries') }}</label>
                      </div>
                      <!-- PAGINATOR JQUERY END -->



                      <table class="w-full whitespace-no-wrap " id="tableId4">
                          <thead>

                              <tr
                                  class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">

                                  <th class="px-4 py-3">
                                      @if ($userNameSelected4)
                                          {{ $userNameSelected4->name }}
                                      @else
                                          {{ __('messages.user_not_selected') }}
                                      @endif
                                  </th>
                                  <th class="px-4 py-3">
                                      @if ($selectedMonthName)
                                          {{ $selectedMonthName }}
                                      @else
                                          {{ __('messages.month_not_selected') }}
                                      @endif
                                  </th>
                                  <th class="px-4 py-3">
                                      @if ($selectedYear3)
                                          {{ $selectedYear3 }}
                                      @else
                                          {{ __('messages.year_not_selected') }}
                                      @endif

                                  </th>
                                  <th class="px-4 py-3">
                                      @if ($date_start)
                                          <p>Date Start:
                                              <span class="text-green-700 ml-2">
                                                  {{ \Carbon\Carbon::parse($date_start)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                              </span>
                                          </p>
                                      @endif

                                      @if ($date_end)
                                          <p>Date End:
                                              <span
                                                  class="{{ $date_start && $date_end && $date_start > $date_end ? 'text-red-700' : 'text-green-700' }} ml-2">
                                                  {{ \Carbon\Carbon::parse($date_end)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                                              </span>
                                          </p>
                                      @endif
                                  </th>
                                  <th class="px-4 py-3" colspan="8">

                                  </th>


                              </tr>
                              <tr
                                  class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                  <th class="px-4 py-3">Nro</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_main_category') }}</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_category') }}</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_subcategory') }}</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_description') }}</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_month') }}</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_date') }}</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_currency') }}</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_operation') }}</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_rate_conv_usd') }}</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_total_in_usd') }}</th>
                                  <th class="px-4 py-3">{{ __('messages.report_month_state') }}</th>

                              </tr>
                          </thead>
                          <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">


                              @foreach ($operationsFetchMonths as $item)
                                  <tr translate="no"
                                      class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                      <td class="px-4 py-3 text-center">
                                          {{ $loop->iteration }}
                                      </td>


                                      <td class="px-4 py-3 text-center">
                                          {{ $item->main_category_title }}</td>
                                      <td class="px-4 py-3 text-center">
                                          {{ $item->category_title }}

                                      </td>
                                      <td class="px-4 py-3 text-center">
                                          {{ $item->subcategory_name ?: 'N/A' }}
                                      </td>
                                      <td class="px-4 py-3 text-center">
                                          {{ $item->operation_description }}
                                      </td>
                                      <td class="px-4 py-3 text-center">
                                          {{ $selectedMonthName }}
                                      </td>
                                      <td class="px-4 py-3 text-center">

                                          @if (app()->getLocale() === 'en')
                                              <span>{{ \Carbon\Carbon::parse($item->operation_date)->translatedFormat('m/d/Y') }}</span>
                                          @elseif(app()->getLocale() === 'pt')
                                              <span>{{ \Carbon\Carbon::parse($item->operation_date)->translatedFormat('d/m/Y') }}</span>
                                          @else
                                              <span>{{ \Carbon\Carbon::parse($item->operation_date)->format('d/m/Y') }}</span>
                                          @endif
                                      </td>

                                      <td class="px-4 py-3 text-center">
                                          {{ $item->operation_currency_type === 'Blue-ARS' ? 'ARS' : $item->operation_currency_type }}
                                      </td>
                                      <td class="px-4 py-3 text-center">

                                          {{ number_format($item->operation_amount, 2, '.', ',') }}
                                      </td>
                                      <td class="px-4 py-3 text-center">

                                          {{ is_numeric($item->operation_currency) ? number_format($item->operation_currency, 2, '.', ',') : $item->operation_currency }}
                                      </td>
                                      <td class="px-4 py-3 text-center">
                                          {{ number_format($item->operation_currency_total, 2, '.', ',') }}
                                          $
                                      </td>
                                      <td class="px-4 py-3 text-center">
                                          @if ($item->operation_status == '1')
                                              <span
                                                  class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                  {{ $item->status_description }}
                                              </span>
                                          @elseif ($item->operation_status == '3')
                                              <span
                                                  class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-700">
                                                  {{ $item->status_description }}
                                              </span>
                                          @elseif ($item->operation_status == '2')
                                              <span
                                                  class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600">
                                                  {{ $item->status_description }}
                                              </span>
                                          @else
                                              <!-- Otro caso por defecto si no coincide con 'admin' ni 'user' -->
                                              <span
                                                  class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-gray-700 dark:text-gray-100">
                                                  {{ $item->status_description }}
                                              </span>
                                          @endif

                                      </td>

                                  </tr>
                              @endforeach

                              <!-- Fila adicional para mostrar el nombre del usuario -->
                              <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                  <td class="px-4 py-3 text-center font-semibold" colspan="8">

                                  </td>
                                  <td class="px-4 py-3 text-center font-semibold">
                                      @if ($currencyType !== 'USD')
                                          {{ number_format($totalMonthAmount, 2, '.', ',') }}
                                          {{ $currencyType }}
                                      @endif

                                  </td>
                                  <td class="px-4 py-3 text-center font-semibold">

                                  </td>
                                  <td class="px-4 py-3 text-center font-semibold">
                                      {{ number_format($totalMonthAmountCurrency, 2, '.', ',') }}
                                      USD
                                  </td>
                                  <td class="px-4 py-3 text-center">
                                  </td>
                              </tr>
                          </tbody>
                      </table>

                      <!-- PAGINATOR JQUERY START -->
                      <div wire:key="updateMonthData-{{ $updateKey }}">
                          <div id="pagination-controls" class="flex items-center justify-between mt-4 my-3">
                              <div id="entries-info" class="entries-info mr-2 text-gray-700 dark:text-gray-400"></div>
                              <div class="flex items-center">
                                  <button id="prev-page"
                                      class="mr-2 px-3 py-1 rounded text-gray-700 dark:text-gray-400">Previous</button>
                                  <div id="page-numbers" class="flex items-center"></div>
                                  <button id="next-page"
                                      class="ml-2 px-3 py-1 rounded text-gray-700 dark:text-gray-400">Next</button>
                              </div>
                          </div>

                          <script>
                              $(document).ready(function() {
                                  var rowsPerPage = 10; // Número de filas por página
                                  var currentPage = 0;

                                  function showPage(page) {
                                      var totalEntries = $('tbody tr:not(:last)')
                                          .length; // Excluir el último tr de los totales o sumarios
                                      var totalPages = Math.ceil(totalEntries / rowsPerPage);

                                      var startEntry = page * rowsPerPage + 1;
                                      var endEntry = Math.min((page + 1) * rowsPerPage, totalEntries);

                                      $('tbody tr:not(:last)').hide().slice(page * rowsPerPage, (page + 1) * rowsPerPage).show();
                                      updateEntriesInfo(startEntry, endEntry, totalEntries);
                                      renderPageNumbers(totalPages);
                                      updatePaginationButtons(page, totalPages);
                                  }

                                  function renderPageNumbers(totalPages) {
                                      var pageNumbersContainer = $('#page-numbers');
                                      pageNumbersContainer.empty();

                                      for (var i = 0; i < totalPages; i++) {
                                          var pageNumber = i + 1;
                                          var buttonClass = (i === currentPage) ? 'bg-blue-600 text-white' : 'text-gray-700';
                                          var pageNumberButton = $('<button class="mx-1 px-3 py-1 rounded page-number-button ' +
                                              buttonClass + '">' + pageNumber + '</button>');

                                          pageNumberButton.on('click', function() {
                                              currentPage = parseInt($(this).text()) - 1;
                                              showPage(currentPage);
                                          });

                                          pageNumbersContainer.append(pageNumberButton);
                                      }
                                  }

                                  function updateEntriesInfo(startEntry, endEntry, totalFilteredEntries) {
                                      var entriesInfo = '';
                                      if (totalFilteredEntries > 0) {
                                          entriesInfo = 'Showing ' + startEntry + ' to ' + endEntry + ' of ' +
                                              totalFilteredEntries + ' entries';
                                      } else {
                                          entriesInfo = 'No matching records found';
                                      }
                                      $('#entries-info').text(entriesInfo);
                                  }

                                  function updatePaginationButtons(page, totalPages) {
                                      if ($('#entries-info').text() === 'No matching records found') {
                                          $('#prev-page').hide();
                                          $('#next-page').hide();
                                      } else {
                                          $('#prev-page').show().prop('disabled', page ===
                                              0); // Deshabilitar "Previous" en la primera página
                                          $('#next-page').show().prop('disabled', page === totalPages -
                                              1); // Deshabilitar "Next" en la última página
                                      }
                                  }

                                  showPage(currentPage);

                                  $('#prev-page').on('click', function() {
                                      if (currentPage > 0) {
                                          currentPage--;
                                          showPage(currentPage);
                                      }
                                  });

                                  $('#next-page').on('click', function() {
                                      var maxPage = Math.floor($('tbody tr').length / rowsPerPage);
                                      if (currentPage < maxPage) {
                                          currentPage++;
                                          showPage(currentPage);
                                      }
                                  });

                                  $('#per-page').on('change', function() {
                                      rowsPerPage = parseInt($(this).val());
                                      currentPage = 0; // Resetear a la primera página
                                      showPage(currentPage);
                                  });
                              });
                          </script>

                      </div>

                      <!-- PAGINATOR JQUERY END -->

                  </div>

              </div>
          @endif
      </div>

      <script>
          document.addEventListener('livewire:load', function() {
              Livewire.on('exportTableToExcel4', function(params) {
                  // Quitar el símbolo "$" y la coma "," antes de exportar
                  $('#tableId4 td').each(function() {
                      var cellText = $(this).text();
                      // Utilizar una expresión regular para quitar todas las ocurrencias de "$" y ","
                      var cleanedText = cellText.replace(/[$,]/g, '');
                      $(this).text(cleanedText);
                  });

                  // Obtener el nombre de usuario de los datos de la tabla
                  const username = params.userName;
                  const selectedYear3 = params.selectedYear3;
                  const selectedMonthName = params.selectedMonthName;

                  // Formatea la fecha como DD-MM-YYYY
                  const formattedDate = new Date().toLocaleDateString('es-ES', {
                      day: '2-digit',
                      month: '2-digit',
                      year: 'numeric'
                  });

                  // Convertir el nombre de usuario a mayúsculas
                  const capitalizedUsername = username.toUpperCase();

                  // Concatenar el nombre del usuario y la fecha al nombre del archivo
                  var filename = "month-report-" + capitalizedUsername + "-" + selectedMonthName + "-" +
                      selectedYear3 + "-" +
                      formattedDate;

                  // Exportar la tabla a Excel
                  $("#tableId4").table2excel({
                      exclude: ".no-export",
                      name: "Worksheet Name",
                      filename: filename
                  });
              });

              Livewire.on('emailSent4', function() {
                  // Lógica para manejar el evento de correo enviado
                  // Esto podría ser un mensaje de confirmación al usuario, etc.
              });
          });
      </script>




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

      <!-- END REPORT MONTHS TABLE  -->
  </div>
