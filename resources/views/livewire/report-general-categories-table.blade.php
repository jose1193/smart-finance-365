   <div x-show="activeTab === '2'">
       <div id="report-table">
           <!--INCLUDE ALERTS MESSAGES-->

           <x-message-success />


           <!-- END INCLUDE ALERTS MESSAGES-->
           <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
               <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">

                   <div wire:ignore>
                       <select id="selectUser2" style="width: 100%" wire:model="selectedUser2"
                           wire:change="updateCategoriesData" wire:ignore>
                           <option value="">{{ __('messages.table_columns_categories.select_a_user') }}</option>

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
                       <select wire:model="selectedCategoryId" style="width: 100%" wire:change="updateCategoriesData"
                           id="selectCategory">
                           <option value="">{{ __('messages.select_a_category') }}</option>
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
                       <select wire:model="selectedYear2" style="width:100%" id="selectYear2"
                           wire:change="updateCategoriesData">
                           <option value="">{{ __('messages.select_a_year') }}</option>

                           @foreach ($years as $year)
                               <option value="{{ $year }}">{{ $year }}</option>
                           @endforeach
                       </select>
                   </div>

               </div>



           </div>

           @if ($showData2)
               <div class="my-10 flex justify-end space-x-2">
                   <x-button wire:click="openModal2">
                       <span class="font-semibold"><i class="fa-solid fa-user-group px-1"></i></i></span>
                       {{ __('messages.send_report') }}
                   </x-button>
                   <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                       wire:click="exportToExcel2" wire:loading.attr="disabled">
                       <span class="font-semibold"><i class="fa-solid fa-file-excel px-1"></i></span>
                       {{ __('messages.download') }}
                   </x-button>
                   <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50"
                       wire:click="resetFields2" wire:loading.attr="disabled">
                       <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                       {{ __('messages.reset_fields') }}
                   </x-button>
               </div>

               @if ($isOpen2)
                   <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
                       <div
                           class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                           <div class="fixed inset-0 transition-opacity">
                               <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                           </div>
                           <!-- This element is to trick the browser into centering the modal contents. -->
                           <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
                           <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                               role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                               <form>
                                   <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                       <div class="">

                                           <label for="exampleFormControlInput1"
                                               class="block text-gray-700 text-sm font-bold mb-2">
                                               {{ __('messages.user_email') }}:</label>
                                           <div wire:ignore>
                                               @if (count($emails) > 0)
                                                   <select multiple id="select2EmailsUser" style="width: 100%"
                                                       wire:model="emails_user2">

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
                                                       wire:click="closeModal2()">
                                                       {{ __('messages.register_and_submit_reports') }}
                                                   </a>

                                               @endif
                                           </div>

                                           <script>
                                               document.addEventListener('livewire:load', function() {
                                                   Livewire.hook('message.sent', () => {
                                                       // Vuelve a aplicar Select2 después de cada actualización de Livewire
                                                       $('#select2EmailsUser').select2({
                                                           width: 'resolve' // need to override the changed default
                                                       });
                                                   });
                                               });

                                               $(document).ready(function() {
                                                   // Inicializa Select2
                                                   $('#select2EmailsUser').select2();

                                                   // Escucha el cambio en Select2 y actualiza Livewire
                                                   $('#select2EmailsUser').on('change', function(e) {
                                                       @this.set('emails_user2', $(this).val());
                                                   });
                                               });
                                           </script>


                                           @error('emails_user2')
                                               <span class="text-red-500">{{ $message }}</span>
                                           @enderror


                                       </div>
                                   </div>
                                   <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                       <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                           <button wire:click.prevent="emailStore2()" wire:loading.attr="disabled"
                                               wire:target="emailStore2" type="button"
                                               class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                               {{ __('messages.send') }}
                                           </button>
                                       </span>
                                       <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                           <button wire:click="closeModal2()" type="button"
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

               <!-- Tables -->
               <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                   <div class="w-full overflow-x-auto">
                       <table class="w-full whitespace-no-wrap" id="tableId2">
                           <thead>
                               <tr
                                   class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
                                   <th class="px-4 py-3">
                                       @if ($userNameSelected2)
                                           {{ $userNameSelected2->name }}
                                       @else
                                           {{ __('messages.user_not_selected') }}
                                       @endif
                                   </th>
                                   <th class="px-4 py-3">
                                       @if ($selectedYear2)
                                           {{ $selectedYear2 }}
                                       @else
                                           {{ __('messages.year_not_selected') }}
                                       @endif
                                   </th>
                                   <th class="px-4 py-3">
                                       <select wire:model="SelectMainCurrencyTypeRender"
                                           wire:change="updateCategoriesData"
                                           class="w-2/4 text-sm dark:text-gray-800 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">

                                           <option value="USD">USD</option>
                                           @foreach ($mainCurrencyTypeRender as $currencyType)
                                               @php
                                                   // Si es 'Blue-ARS', cambiarlo a 'ARS'
                                                   $displayCurrency =
                                                       $currencyType == 'Blue-ARS' ? 'ARS' : $currencyType;
                                               @endphp
                                               <option value="{{ $currencyType }}">{{ $displayCurrency }}</option>
                                           @endforeach

                                       </select>

                                       @php
                                           $currencyType =
                                               $SelectMainCurrencyTypeRender === 'Blue-ARS'
                                                   ? 'ARS'
                                                   : $SelectMainCurrencyTypeRender;
                                       @endphp
                                   </th>

                               </tr>
                               <tr translate="no"
                                   class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                   <th class="px-4 py-3">Nro</th>
                                   <th class="px-4 py-3"> {{ __('messages.label_month_th') }}</th>
                                   <th class="px-4 py-3">
                                       @if ($categoryNameSelected)
                                           {{ $categoryNameSelected->category_name }}
                                       @else
                                           {{ __('messages.category_not_selected') }}
                                       @endif

                                   </th>

                               </tr>
                           </thead>
                           <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">


                               @for ($i = 0; $i < count($ArrayCategories); $i++)
                                   <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                       <td class="px-4 py-3 text-center">{{ $i + 1 }}</td>
                                       <td class="px-4 py-3 text-center">
                                           {{ \Carbon\Carbon::create()->month($i + 1)->translatedFormat('F') }}

                                       </td>

                                       <td class="px-4 py-3 text-center">
                                           {{ number_format($ArrayCategories[$i]['totalCurrency'], 2, '.', ',') }}
                                           {{ $currencyType }}
                                       </td>
                                   </tr>
                               @endfor


                               <!-- Fila adicional para mostrar el nombre del usuario -->
                               <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                   <td class="px-4 py-3 text-center font-semibold" colspan="2">

                                   </td>


                                   <td class="px-4 py-3 text-center font-semibold">
                                       {{ number_format($totalCategoriesRenderCurrency, 2, '.', ',') }}
                                       {{ $currencyType }}

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
           Livewire.on('exportTableToExcel2', function(params) {
               // Lógica para exportar la tabla a Excel (usando table2excel o la biblioteca de tu elección)

               // Quitar el símbolo "$" y la coma "," antes de exportar
               $('#tableId2 td').each(function() {
                   var cellText = $(this).text();
                   // Utilizar una expresión regular para quitar todas las ocurrencias de "$", "," y "USD"
                   var cleanedText = cellText.replace(/[$,]|USD/g, '');
                   $(this).text(cleanedText);
               });

               // Obtener el nombre de usuario de los datos de la tabla
               const username = params.userName;
               const selectedYear2 = params.selectedYear2;
               const categoryNameSelected = params.categoryNameSelected;


               // Formatear la fecha como DD-MM-YYYY
               const formattedDate = new Date().toLocaleDateString('es-ES', {
                   day: '2-digit',
                   month: '2-digit',
                   year: 'numeric'
               });

               // Concatenar el nombre del usuario y la fecha al nombre del archivo
               var filename = "categories-report-" + username.toUpperCase() + "-" + categoryNameSelected +
                   "-" + selectedYear2 +
                   "-" + formattedDate;

               // Exportar la tabla a Excel
               $("#tableId2").table2excel({
                   exclude: ".no-export",
                   name: "Worksheet Name",
                   filename: filename
               });

               // Después de exportar a Excel, dispara el evento para enviar por correo
               Livewire.emit('sendEmailWithExcel2');
           });

           Livewire.on('emailSent2', function() {
               // Lógica para manejar el evento de correo enviado
               // Esto podría ser un mensaje de confirmación al usuario, etc.
           });
       });
   </script>
