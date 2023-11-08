   <div x-show="activeTab === '2'">
       <div id="report-table">
           <!--INCLUDE ALERTS MESSAGES-->

           <x-message-success />


           <!-- END INCLUDE ALERTS MESSAGES-->
           <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
               <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">


                   @if (auth()->user()->hasRole('Admin'))

                       <div wire:ignore>
                           <select id="selectUser2" style="width: 100%" wire:model="selectedUser2"
                               wire:change="updateCategoriesData" wire:ignore>
                               <option value="">Select User</option>

                               @foreach ($users as $user)
                                   <option value="{{ $user->id }}">{{ $user->name }}
                                   </option>
                               @endforeach
                           </select>
                       </div>
                   @else
                       <select wire:model="selectedUser2" wire:change="updateCategoriesData"
                           class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                           <option value="">Select User</option>

                           <option value="{{ auth()->user()->id }}">{{ auth()->user()->name }}
                           </option>


                       </select>

                   @endif


               </div>

               <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">
                   <div wire:ignore>
                       <select wire:model="selectedCategoryId" style="width: 100%" wire:change="updateCategoriesData"
                           id="selectCategory">
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
                       <select wire:model="selectedYear2" style="width:100%" id="selectYear2"
                           wire:change="updateCategoriesData">
                           <option value="">Select Year</option>

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
                       Send Report
                   </x-button>
                   <x-button class="bg-green-600 hover:bg-green-700 shadow-lg hover:shadow-green-500/50"
                       wire:click="exportToExcel2" wire:loading.attr="disabled">
                       <span class="font-semibold"><i class="fa-solid fa-file-excel px-1"></i></span>
                       Download
                   </x-button>
                   <x-button class="bg-red-600 hover:bg-red-700 shadow-lg hover:shadow-red-500/50"
                       wire:click="resetFields2" wire:loading.attr="disabled">
                       <span class="font-semibold"><i class="fa-solid fa-power-off px-1"></i></span>
                       Reset Fields
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
                                               User Email:</label>
                                           <div wire:ignore>
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
                                               Send
                                           </button>
                                       </span>
                                       <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                           <button wire:click="closeModal2()" type="button"
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
                       <table class="w-full whitespace-no-wrap" id="tableId2">
                           <thead>
                               <tr
                                   class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 dark:text-gray-400 dark:bg-gray-800">
                                   <th class="px-4 py-3">
                                       @if ($userNameSelected2)
                                           {{ $userNameSelected2->name }}
                                       @else
                                           User Not Selected
                                       @endif
                                   </th>
                                   <th class="px-4 py-3">
                                       @if ($selectedYear2)
                                           {{ $selectedYear2 }}
                                       @else
                                           Year Not Selected
                                       @endif
                                   </th>
                                   <th class="px-4 py-3">
                                   </th>
                                   <th class="px-4 py-3">
                                   </th>
                               </tr>
                               <tr
                                   class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                   <th class="px-4 py-3">Nro</th>
                                   <th class="px-4 py-3">Mes</th>
                                   <th class="px-4 py-3">
                                       @if ($categoryNameSelected)
                                           {{ $categoryNameSelected->category_name }}
                                       @else
                                           Category Not Selected
                                       @endif
                                   </th>
                                   <th class="px-4 py-3">Total Operation USD</th>
                               </tr>
                           </thead>
                           <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">


                               @for ($i = 0; $i < count($ArrayCategories); $i++)
                                   <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                       <td class="px-4 py-3 text-center">{{ $i + 1 }}</td>
                                       <td class="px-4 py-3 text-center">
                                           {{ \Carbon\Carbon::create()->month($i + 1)->format('F') }}
                                       </td>
                                       <td class="px-4 py-3 text-center">
                                           ${{ number_format($ArrayCategories[$i]['total'], 0, '.', ',') }}
                                       </td>
                                       <td class="px-4 py-3 text-center">
                                           {{ number_format($ArrayCategories[$i]['totalCurrency'], 0, '.', ',') }}$
                                       </td>
                                   </tr>
                               @endfor


                               <!-- Fila adicional para mostrar el nombre del usuario -->
                               <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                   <td class="px-4 py-3 text-center font-semibold">

                                   </td>
                                   <td class="px-4 py-3 text-center font-semibold">

                                   </td>
                                   <td class="px-4 py-3 text-center font-semibold">$
                                       {{ number_format($totalCategoriesRender, 0, '.', ',') }}

                                   </td>
                                   <td class="px-4 py-3 text-center font-semibold">
                                       {{ number_format($totalCategoriesRenderCurrency, 0, '.', ',') }}$

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


           Livewire.on('exportTableToExcel2', function() {
               // Lógica para exportar la tabla a Excel (usando table2excel o la biblioteca de tu elección)

               // Por ejemplo:
               $("#tableId2").table2excel({
                   exclude: ".no-export",
                   name: "Worksheet Name",
                   filename: "categories-report"
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
