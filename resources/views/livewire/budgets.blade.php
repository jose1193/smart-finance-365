<div :class="{ 'theme-dark': dark }" x-data="data()" lang="en">



    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- MENU SIDEBAR -->
        <x-menu-sidebar />
        <!-- END MENU SIDEBAR -->
        <div class="flex flex-col flex-1 w-full">

            <!-- HEADER -->
            <x-header-dashboard />
            <!-- END HEADER -->

            <!-- PANEL MAIN CATEGORIES -->
            <!--INCLUDE ALERTS MESSAGES-->

            <x-message-success />


            <!-- END INCLUDE ALERTS MESSAGES-->

            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid">

                    <!-- CTA -->
                    <div
                        class="mt-5 flex items-center justify-between p-4 mb-8 text-sm font-semibold text-white bg-blue-500 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple">
                        <div class="flex items-center">
                            <i class="fa-solid fa-money-bills mr-3"></i>

                            <x-slot name="title">
                                {{ __('Budget Management') }}
                            </x-slot>
                            <a href="{{ route('budgets') }}">
                                <span>Budget Management</span></a>
                        </div>

                    </div>

                    <div class=" my-7 flex justify-between space-x-2">
                        <x-button wire:click="create()"><span class="font-semibold"> Create New <i
                                    class="fa-solid fa-money-bill-wave"></i></span>
                        </x-button>
                        <x-input id="name" type="text" wire:model="search" placeholder="Search..." autofocus
                            autocomplete="off" />
                    </div>
                    <!-- resources/views/tu-vista.blade.php -->


                    <!-- Tables -->
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                        <th class="px-4 py-3">Nro</th>
                                        @if (auth()->user()->hasRole('Admin'))
                                            <th class="px-4 py-3">User</th>
                                        @endif
                                        <th class="px-4 py-3">Budget</th>
                                        <th class="px-4 py-3">Currency</th>
                                        <th class="px-4 py-3">Rate CONV/USD</th>
                                        <th class="px-4 py-3">Total In USD</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

                                    @forelse($data as $item)
                                        <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                            <td class="px-4 py-3 text-center">

                                                {{ $loop->iteration }}

                                            </td>
                                            @if (auth()->user()->hasRole('Admin'))
                                                <td class="px-4 py-3 text-center">
                                                    {{ $item->name }}
                                                </td>
                                            @endif
                                            <td class="px-4 py-3 text-xs">
                                                {{ number_format($item->budget_operation, 2, '.', ',') }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ $item->budget_currency_type === 'Blue-ARS' ? 'ARS' : $item->budget_currency_type }}


                                            </td>
                                            <td class="px-4 py-3 text-xs">

                                                {{ is_numeric($item->budget_currency) ? number_format($item->budget_currency, 2, '.', ',') : $item->budget_currency }}

                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ number_format($item->budget_currency_total, 2, '.', ',') }}

                                                $

                                            </td>

                                            <td class="px-4 py-3 text-xs">
                                                {{ \Carbon\Carbon::parse($item->budget_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">

                                                <button wire:click="edit({{ $item->id }})"
                                                    class="bg-blue-600 duration-500 ease-in-out hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"><i
                                                        class="fa-solid fa-pen-to-square"></i></button>
                                                <button wire:click="$emit('deleteData',{{ $item->id }})"
                                                    class="bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white font-bold py-2 px-4 rounded"><i
                                                        class="fa-solid fa-trash"></i></button>

                                            </td>
                                        </tr>

                                    @empty
                                        <tr class="text-center">
                                            <td colspan="8">
                                                <div class="grid justify-items-center w-full mt-5">
                                                    <div class="text-center bg-red-100 rounded-lg py-5 w-full px-6 mb-4 text-base text-red-700 "
                                                        role="alert">
                                                        No Data Records
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="m-2 p-2">{{ $data->links() }}</div>
                        </div>
                        <!-- MODAL -->
                        @if ($isOpen)
                            <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
                                <div
                                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity">
                                        <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
                                    </div>
                                    <!-- This element is to trick the browser into centering the modal contents. -->
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full sm:max-w-lg sm:w-full"
                                        role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                        <div
                                            class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                                            <!--Modal title-->
                                            <div class="text-center"></div>
                                            <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                                                id="exampleModalLabel">
                                                Budget Management
                                            </h5>
                                            <!--Close button-->
                                            <button type="button" wire:click="closeModal()"
                                                class="p-0.5 bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white rounded-full box-content  border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                                                data-te-modal-dismiss aria-label="Close">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="h-6 w-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                        <form autocomplete="off">
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="">

                                                    <div class="mb-4">
                                                        <label for="exampleFormControlInput1"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            User Email:</label>
                                                        @if (auth()->user()->hasRole('Admin'))
                                                            <div wire:ignore>
                                                                <select id="select4EmailsUser" style="width: 100%"
                                                                    wire:model="user_id">

                                                                    @foreach ($users->groupBy('name') as $nameUser => $groupedEmails)
                                                                        <optgroup label="{{ $nameUser }}">
                                                                            @foreach ($groupedEmails as $email)
                                                                                <option value="{{ $email->id }}"
                                                                                    @if ($user_id == $email->id) selected @endif>
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
                                                                        $('#select4EmailsUser').select2({
                                                                            width: 'resolve' // need to override the changed default
                                                                        });
                                                                    });
                                                                });

                                                                $(document).ready(function() {
                                                                    // Inicializa Select2
                                                                    $('#select4EmailsUser').select2();

                                                                    // Escucha el cambio en Select2 y actualiza Livewire
                                                                    $('#select4EmailsUser').on('change', function(e) {
                                                                        @this.set('user_id', $(this).val());
                                                                    });
                                                                });
                                                            </script>
                                                        @else
                                                            <select wire:model="user_id"
                                                                class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                <option value="{{ auth()->user()->id }}">
                                                                    {{ auth()->user()->name }}
                                                                </option>
                                                            </select>

                                                        @endif
                                                        @error('user_id')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="selectedCurrencyFrom"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            Select Conversion From</label>
                                                        <div wire:ignore>
                                                            <select wire:model="selectedCurrencyFrom"
                                                                wire:change="showSelectedCurrency"
                                                                id="selectedCurrencyFrom" style="width: 100%;">
                                                                <option value="">Select Option</option>
                                                                <option value="Blue-ARS">Argentine Peso (ARS)
                                                                </option>
                                                                @if ($listCurrencies)
                                                                    @foreach ($listCurrencies['currencies'] as $codigo => $nombre)
                                                                        <option value="{{ $codigo }}">
                                                                            {{ $nombre }} ({{ $codigo }})
                                                                        </option>
                                                                    @endforeach
                                                                @endif
                                                            </select>

                                                            @error('selectedCurrencyFrom')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="budget_operation"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            Operation With <span
                                                                class="text-blue-700">{{ $this->selectedCurrencyFrom }}</label>


                                                        <div class="flex items-center relative">
                                                            <input type="text" autocomplete="off" id="montoInput"
                                                                wire:model="budget_operation"
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pr-8"
                                                                placeholder="Enter Budget Operation">

                                                            <span
                                                                class="absolute right-0 top-0 mt-2 mr-2 text-gray-500">{{ $this->selectedCurrencyFrom }}</span>
                                                        </div>

                                                        @error('budget_operation')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <script>
                                                        document.addEventListener('livewire:load', function() {
                                                            Livewire.hook('message.sent', () => {
                                                                // Vuelve a aplicar Select2 después de cada actualización de Livewire
                                                                applySelect2('#selectedCurrencyFrom');
                                                            });
                                                        });

                                                        $(document).ready(function() {
                                                            // Inicializa Select2 para selectedCurrencyFrom
                                                            initializeSelect2('#selectedCurrencyFrom', function(e) {
                                                                @this.set('selectedCurrencyFrom', $(this).val());
                                                                @this.call('showSelectedCurrency');
                                                            });
                                                        });

                                                        function initializeSelect2(selector, onChangeCallback) {
                                                            $(selector).select2();

                                                            // Escucha el cambio en Select2 y ejecuta la devolución de llamada de cambio
                                                            $(selector).on('change', onChangeCallback);
                                                        }

                                                        function applySelect2(selector) {
                                                            $(selector).select2({
                                                                width: 'resolve' // necesitas anular el valor predeterminado cambiado
                                                            });
                                                        }
                                                    </script>
                                                    <div class="mb-4">
                                                        <label for="budget_currency"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            Rate CONV/USD </label>

                                                        <input type="text" autocomplete="off" id="budget_currency"
                                                            wire:model="budget_currency" readonly
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            placeholder="">

                                                        <input type="text" hidden wire:model="budget_currency_type"
                                                            readonly>
                                                        @error('budget_currency_type')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                        @error('budget_currency')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4 relative">
                                                        <label for="budget_currency"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            Total in USD
                                                        </label>

                                                        <div class="flex items-center relative">
                                                            <span
                                                                class="absolute left-0 top-0 mt-2 ml-2 text-gray-500">$</span>

                                                            <input type="text" autocomplete="off"
                                                                id="budget_currency_total" readonly
                                                                wire:model="budget_currency_total"
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pl-8"
                                                                placeholder="">


                                                        </div>

                                                        @error('budget_currency_total')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>


                                                    <div class="mb-4">
                                                        <label for="budget_date"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            Date</label>
                                                        <div wire:ignore>
                                                            <input type="text" id="myDatePicker" readonly
                                                                wire:model="budget_date" placeholder="dd/mm/yyyy"
                                                                autocomplete="off"
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        </div>
                                                        @error('budget_date')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                    <button type="button" wire:click.prevent="store()"
                                                        wire:loading.attr="disabled" wire:target="store"
                                                        class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                        Register
                                                    </button>
                                                </span>
                                                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                                    <button wire:click="closeModal()" type="button"
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
                        <!-- MODAL -->
                    </div>


                </div>
            </main>


            <!-- END PANEL MAIN CATEGORIES -->

        </div>
    </div>


</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('deleteData', function(id) {
            console.log('Evento "deleteData" emitido con ID: ' + id);
            Swal.fire({
                title: 'Are you sure you want to delete this item?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('budgets', 'delete',
                        id); // Envía el Id al método delete
                    Swal.fire(
                        'Deleted!',
                        'Your Data has been deleted.',
                        'success'
                    );
                }
            });
        });
    });
</script>



<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('modalOpened', function() {
            flatpickr("#myDatePicker", {
                locale: "es",
                allowInput: true,
                altInput: true,
                altFormat: "l, F j, Y",
                dateFormat: "d/m/Y", // Configura el formato de fecha deseado
                allowInput: true,
                onClose: function(selectedDates, dateStr, instance) {
                    // Actualiza Livewire con la nueva fecha cuando se selecciona una fecha
                    @this.set('budget_date', dateStr);
                }
            });

        });
    });
</script>

<script>
    $(document).ready(function() {
        Livewire.on('modalOpenedAutonumeric', function() {
            $('#montoInput').mask('#.##0,00', {
                reverse: true
            });
        });
    });
</script>
