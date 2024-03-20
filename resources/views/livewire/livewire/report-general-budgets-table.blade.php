 <div x-show="activeTab === '1'">
     <div id="report-table">
         <!--INCLUDE ALERTS MESSAGES-->
         <x-message-success />
         <!-- END INCLUDE ALERTS MESSAGES-->
         <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
             <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">

                 <div wire:ignore>
                     <select id="selectUser5" style="width: 100%" wire:model="selectedUser5" wire:change="updateDataBudget"
                         wire:ignore>
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
                     <select wire:model="selectedYear4" style="width:100%" id="selectYear4"
                         wire:change="updateDataBudget">
                         <option value="">Select Year</option>

                         @foreach ($years as $year)
                             <option value="{{ $year }}">{{ $year }}</option>
                         @endforeach
                     </select>
                 </div>
             </div>


         </div>
         @if ($showData)
             <div class="my-10 flex justify-end space-x-2">
                 <x-button wire:click="openModal">
                     <span class="font-semibold"><i class="fa-solid fa-user-group px-1"></i></i></span>
                     Send Report
                 </x-button>
                 <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                     wire:click="exportToExcel5" wire:loading.attr="disabled">
                     <span class="font-semibold"><i class="fa-solid fa-file-excel px-1"></i></span>
                     Download
                 </x-button>
                 <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50"
                     wire:click="resetFields5" wire:loading.attr="disabled">
                     <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                     Reset Fields
                 </x-button>
             </div>

             @if ($isOpen)
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
                                             <div wire:ignore>
                                                 @if (count($emails) > 0)
                                                     <select multiple id="selectEmailsUser" style="width: 100%"
                                                         wire:model="emails_user5">


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
                                                         wire:click="closeModal5()">
                                                         Click here to register your email and submit reports
                                                     </a>

                                                 @endif
                                             </div>
                                             <script>
                                                 document.addEventListener('livewire:load', function() {
                                                     Livewire.hook('message.sent', () => {
                                                         // Vuelve a aplicar Select2 después de cada actualización de Livewire
                                                         $('#selectEmailsUser').select2({
                                                             width: 'resolve' // need to override the changed default
                                                         });
                                                     });
                                                 });

                                                 $(document).ready(function() {
                                                     // Inicializa Select2
                                                     $('#selectEmailsUser').select2();

                                                     // Escucha el cambio en Select2 y actualiza Livewire
                                                     $('#selectEmailsUser').on('change', function(e) {
                                                         @this.set('emails_user5', $(this).val());
                                                     });
                                                 });
                                             </script>
                                             @error('emails_user5')
                                                 <span class="text-red-500">{{ $message }}</span>
                                             @enderror
                                         </div>

                                     </div>
                                 </div>
                                 <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                     <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                         <button wire:click.prevent="emailStore5()" wire:loading.attr="disabled"
                                             wire:target="emailStore5" type="button"
                                             class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                             Send
                                         </button>
                                     </span>
                                     <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                         <button wire:click="closeModal5()" type="button"
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
             @if ($date_start && $date_end && $date_start > $date_end)
                 <p class="text-red-700 mt-2 text-center font-semibold">Error: La fecha de inicio no puede
                     ser posterior a
                     la fecha de finalización.</p>
             @endif
             <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                 <div class="w-full overflow-x-auto">
                     <div class="flex items-center  w-full space-x-3 mt-5">
                         <div wire:ignore>
                             <input wire:ignore type="text" id="myDatePicker5" wire:model.lazy="date_start"
                                 wire:change="updateDataBudget" placeholder="dd/mm/yyyy" autocomplete="off"
                                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

                         </div>
                         <div wire:ignore>
                             <input wire:ignore type="text" id="myDatePicker6" wire:model.lazy="date_end"
                                 wire:change="updateDataBudget" placeholder="dd/mm/yyyy" autocomplete="off"
                                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

                         </div>
                         <div>
                             <select wire:model="SelectMainCurrencyTypeRender" wire:change="updateDataBudget"
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



                         @php
                             $currencyType = $SelectMainCurrencyTypeRender === 'Blue-ARS' ? 'ARS' : $SelectMainCurrencyTypeRender;
                         @endphp
                     </div>
                     <table class="w-full whitespace-no-wrap" id="tableId">
                         <thead>
                             <tr
                                 class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">

                                 <th class="px-4 py-3 " colspan="12">


                                 </th>



                             </tr>
                             <tr
                                 class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
                                 <th class="px-4 py-3">
                                     @if ($userNameSelected)
                                         {{ $userNameSelected->name }}
                                     @else
                                         User Not Selected
                                     @endif
                                 </th>
                                 <th class="px-4 py-3">
                                     @if ($selectedYear4)
                                         {{ $selectedYear4 }}
                                     @else
                                         Year Not Selected
                                     @endif
                                 </th>
                                 <th class="px-4 py-3">
                                     {{ $currencyType }}
                                 </th>
                                 <th class="px-4 py-3" colspan="5">
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

                             </tr>
                             <tr
                                 class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                 <th class="px-4 py-3">Nro</th>
                                 <th class="px-4 py-3">Mes</th>
                                 <th class="px-4 py-3">Budget</th>
                                 <th class="px-4 py-3">{{ $categoryName }} </th>
                                 <th class="px-4 py-3">{{ $categoryName2 }} </th>
                                 <th class="px-4 py-3">% Budget </th>
                                 <th class="px-4 py-3">Savings </th>
                                 <th class="px-4 py-3">% Save </th>
                             </tr>
                         </thead>
                         <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                             @php
                                 $totalSavings = 0;
                             @endphp

                             @for ($i = 1; $i <= 12; $i++)
                                 <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                     <td class="px-4 py-3 text-center"> {{ $i }}</td>
                                     <td class="px-4 py-3 text-center">
                                         {{ \Carbon\Carbon::create()->month($i)->format('F') }}</td>

                                     <td class="px-4 py-3 text-center">
                                         {{ number_format($budgetDataCurrency[$i - 1], 2, '.', ',') }}
                                     </td>

                                     <td class="px-4 py-3 text-center">
                                         {{ number_format($incomeDataCurrency[$i - 1], 2, '.', ',') }}
                                     </td>

                                     <!-- Inside the loop, in the table body -->
                                     <td class="px-4 py-3 text-center"
                                         style="{{ $expenseDataCurrency[$i - 1] > $budgetDataCurrency[$i - 1] ? 'color: red;' : '' }}">
                                         {{ number_format($expenseDataCurrency[$i - 1], 2, '.', ',') }}

                                     </td>

                                     <td class="px-4 py-3 text-center"
                                         style="{{ $budgetDataCurrency[$i - 1] > 0 && $expenseDataCurrency[$i - 1] / $budgetDataCurrency[$i - 1] > 100 ? 'color: red;' : '' }}">
                                         @if ($budgetDataCurrency[$i - 1] > 0)
                                             {{ number_format(($expenseDataCurrency[$i - 1] / $budgetDataCurrency[$i - 1]) * 100, 2) }}%
                                         @else
                                             N/A
                                         @endif
                                     </td>

                                     <td class="px-4 py-3 text-center"
                                         style="{{ $incomeDataCurrency[$i - 1] - $expenseDataCurrency[$i - 1] < 0 ? 'color: red;' : '' }}">
                                         {{ number_format($incomeDataCurrency[$i - 1] - $expenseDataCurrency[$i - 1], 2) }}

                                     </td>


                                     <td class="px-4 py-3 text-center"
                                         style="{{ $incomeDataCurrency[$i - 1] > 0 && ($incomeDataCurrency[$i - 1] - $expenseDataCurrency[$i - 1]) / $incomeDataCurrency[$i - 1] < 0 ? 'color: red;' : '' }}">
                                         @if ($incomeDataCurrency[$i - 1] > 0)
                                             {{ number_format((($incomeDataCurrency[$i - 1] - $expenseDataCurrency[$i - 1]) / $incomeDataCurrency[$i - 1]) * 100, 2) }}%
                                         @else
                                             N/A
                                         @endif
                                     </td>
                                 </tr>
                                 @php
                                     $totalSavings += $incomeDataCurrency[$i - 1] - $expenseDataCurrency[$i - 1];
                                 @endphp
                             @endfor

                             <!-- Fila adicional para mostrar el nombre del usuario -->
                             <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                 <td class="px-4 py-3 text-center font-semibold">

                                 </td>
                                 <td class="px-4 py-3 text-center font-semibold">
                                     Total {{ $currencyType }}
                                 </td>
                                 <td class="px-4 py-3 text-center font-semibold">
                                     {{ number_format($totalBudgetCurrency, 2, '.', ',') }} {{ $currencyType }}

                                 </td>
                                 <td class="px-4 py-3 text-center font-semibold">
                                     {{ number_format($totalIncomeCurrency, 2, '.', ',') }} {{ $currencyType }}
                                 </td>

                                 <td class="px-4 py-3 text-center font-semibold">
                                     {{ number_format($totalExpenseCurrency, 2, '.', ',') }} {{ $currencyType }}

                                 </td>
                                 <td class="px-4 py-3 text-center font-semibold">


                                 </td>
                                 <td class="px-4 py-3 text-center font-semibold"
                                     style="{{ $totalSavings < 0 ? 'color: red;' : '' }}">
                                     {{ number_format($totalSavings, 2, '.', ',') }} {{ $currencyType }}
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

 </div>

 <script>
     document.addEventListener('livewire:load', function() {
         Livewire.on('exportTableToExcel5', function(params) {
             // Lógica para exportar la tabla a Excel (usando table2excel o la biblioteca de tu elección)

             // Quitar el símbolo "$" y la coma "," antes de exportar
             $('#tableId td').each(function() {
                 var cellText = $(this).text();
                 // Utilizar una expresión regular para quitar todas las ocurrencias de "$" y ","
                 var cleanedText = cellText.replace(/[$,]/g, '');
                 $(this).text(cleanedText);
             });

             // Formatea la fecha como DD-MM-YYYY
             const formattedDate = new Date().toLocaleDateString('es-ES', {
                 day: '2-digit',
                 month: '2-digit',
                 year: 'numeric'
             });

             // Obtener el nombre de usuario de los datos de la tabla
             const username = params.userName;
             const selectedYear4 = params.selectedYear4;

             // Convertir el nombre de usuario a mayúsculas
             const capitalizedUsername = username.toUpperCase();

             // Concatenar el nombre del usuario y la fecha al nombre del archivo
             var filename = "general-report-budget-" + capitalizedUsername + "-" + selectedYear4 + "-" +
                 formattedDate;

             // Exportar la tabla a Excel
             $("#tableId").table2excel({
                 exclude: ".no-export",
                 name: "Worksheet Name",
                 filename: filename
             });

             // Después de exportar a Excel, dispara el evento para enviar por correo
             Livewire.emit('sendEmailWithExcel');
         });

         Livewire.on('emailSent', function() {
             // Lógica para manejar el evento de correo enviado
             // Esto podría ser un mensaje de confirmación al usuario, etc.
         });
     });
 </script>


 <script>
     document.addEventListener('livewire:load', function() {
         Livewire.on('initializeFlatpickr2', function() {
             flatpickr("#myDatePicker5", {
                 locale: "es",
                 altInput: true,
                 altFormat: "j F, Y",
                 dateFormat: "Y-m-d", // Set to the format you expect the backend to receive
                 allowInput: true,
                 onClose: function(selectedDates1, dateStr1, instance1) {
                     @this.set('date_start', dateStr1);
                 }
             });

             flatpickr("#myDatePicker6", {
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
