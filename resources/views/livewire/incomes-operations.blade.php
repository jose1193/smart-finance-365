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
                                {{ __('Income Management') }}
                            </x-slot>
                            <a href="{{ route('incomes') }}">
                                <span>Income Management</span></a>
                        </div>

                    </div>

                    <div class=" my-7 flex justify-between space-x-2">
                        <x-button wire:click="create()"><span class="font-semibold"> Create New <i
                                    class="fa-solid fa-money-bill-wave"></i></span>
                        </x-button>
                        <x-input id="name" type="text" wire:model="search" placeholder="Search..." autofocus
                            autocomplete="off" />
                    </div>

                    <!-- Tables -->
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                        <th class="px-4 py-3">Nro</th>
                                        <th class="px-4 py-3">Category</th>
                                        <th class="px-4 py-3">Description</th>
                                        <th class="px-4 py-3">Amount ARS</th>
                                        <th class="px-4 py-3">Currency Amount</th>
                                        <th class="px-4 py-3">Total Exchange</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Date</th>
                                        <th class="px-4 py-3">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                    @forelse($data as $item)
                                        <tr class="text-gray-700 text-xs text-center  uppercase dark:text-gray-400">
                                            <td class="px-4 py-3 text-center">

                                                {{ $loop->iteration }}

                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ Str::words($item->category_name, 2, '...') }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ Str::words($item->operation_description, 2, '...') }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">

                                                $
                                                {{ $formatted_amount = number_format($item->operation_amount, 0, '.', ',') }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">

                                                $
                                                {{ $formatted_amount = number_format($item->operation_currency, 0, '.', ',') }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">

                                                $
                                                {{ $formatted_amount = number_format($item->operation_currency_total, 0, '.', ',') }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">

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
                                            <td class="px-4 py-3 text-xs">
                                                {{ $item->operation_date }}
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
                                            <td colspan="9">
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
                                            <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                                                id="exampleModalLabel">
                                                Income Management
                                            </h5>
                                            <!--Close button-->
                                            <button type="button" wire:click="closeModal()"
                                                class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
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
                                                        <label for="operation_description"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            Description</label>
                                                        <input type="text" autocomplete="off"
                                                            id="operation_description"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            maxlength="50" placeholder="Enter Income Description"
                                                            wire:model="operation_description">
                                                        @error('operation_description')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="operation_amount"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            Amount ARS</label>


                                                        <input type="text" name="amountField" autocomplete="off"
                                                            id="operation_amount" wire:model="operation_amount"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            placeholder="Enter Income Amount">



                                                        @error('operation_amount')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="operation_currency"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            Currency Amount </label>


                                                        <input type="text" autocomplete="off"
                                                            id="operation_currency" wire:model="operation_currency"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            placeholder="">

                                                        @error('operation_currency')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="operation_currency"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            Total Currency Amount </label>


                                                        <input type="text" name="totalbudget2" autocomplete="off"
                                                            id="operation_currency_total"
                                                            wire:model="operation_currency_total"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            placeholder="">
                                                        @error('operation_currency_total')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                        <script>
                                                            const amountField = document.getElementById('operation_amount');
                                                            const totalBudgetField = document.getElementById('operation_currency_total');
                                                            const dollarChangeValue = {{ $data2['blue']['value_sell'] ?? 0 }}; // Valor predeterminado 0 si no se encuentra

                                                            amountField.addEventListener('input', function() {
                                                                const formattedValue = amountField.value.replace(/[^0-9,.]/g,
                                                                    ''); // Elimina todo excepto dígitos, comas y puntos
                                                                const numericValue = parseFloat(formattedValue.replace(/,/, '').replace(/\./,
                                                                    '.')); // Reemplaza comas por puntos y convierte en número

                                                                if (!isNaN(numericValue)) {
                                                                    const calculatedValue = (numericValue / dollarChangeValue).toFixed(2);
                                                                    totalBudgetField.value = '$' + calculatedValue;
                                                                } else {
                                                                    totalBudgetField.value = ''; // Limpiar el campo si la entrada no es válida
                                                                }
                                                            });
                                                        </script>
                                                    </div>



                                                    <div class="mb-4">
                                                        <label for="operation_date"
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            Date</label>
                                                        <input type="date" wire:model="operation_date"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('operation_date')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="exampleFormControlInput2"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Category
                                                        </label>
                                                        <select wire:model="category_id"
                                                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                                            <option value="">

                                                            </option>
                                                            @foreach ($categoriesRender as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->category_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @error('category_id')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="exampleFormControlInput2"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Status
                                                        </label>
                                                        <select wire:model="operation_status"
                                                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                                            <option value="">

                                                            </option>
                                                            @foreach ($statusOptionsRender as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->status_description }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @error('operation_status')
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
                    Livewire.emitTo('incomes-operations', 'delete',
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
