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


            <main class="h-full overflow-y-auto">
                <div class="container px-6 mx-auto grid">

                    <!-- CTA -->
                    <div
                        class="mt-5 flex items-center justify-between p-4 mb-8 text-sm font-semibold text-white bg-blue-500 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple">
                        <div class="flex items-center">
                            <i class="fa-solid fa-money-bills mr-3"></i>

                            <x-slot name="title">
                                {{ __('messages.budget_expense_management') }}

                            </x-slot>
                            <a href="">
                                <span>{{ __('messages.budget_expense_management') }}
                                </span></a>
                        </div>

                    </div>

                    <div class="flex flex-col space-y-2 md:space-y-0 md:flex-row md:items-center my-10">
                        <div class="w-full px-3 md:w-1/3 mb-3 sm:mb-0 ">

                            <div wire:ignore>
                                <select style="width: 100%" id="selectUser8" wire:model="selectedUser8"
                                    wire:change="updateDataExpense">
                                    <option value="">{{ __('messages.table_columns_categories.select_a_user') }}
                                    </option>

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



                    </div>

                    <!--INCLUDE ALERTS MESSAGES-->

                    <x-message-success />


                    <!-- END INCLUDE ALERTS MESSAGES-->
                    @if ($showData)
                        <div class=" my-7 flex justify-between space-x-2">
                            <x-button wire:click="create()"><span class="font-semibold"> {{ __('messages.create_new') }}
                                    <i class="fa-solid fa-money-bill-wave"></i></span>
                            </x-button>

                        </div>
                        <div class="flex justify-end mb-5">
                            @if (count($checkedSelected) >= 1)
                                <button wire:click="confirmDelete"
                                    class="bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    {{ __('messages.delete_multiple') }} ({{ count($checkedSelected) }})
                                </button>
                            @endif
                        </div>
                        <!-- PAGINATOR JQUERY START -->
                        <div class="flex items-center justify-between -mt-5">
                            <div class="my-5 ">
                                <label for="perPage" class="text-gray-800 dark:text-gray-300 mr-1 ">
                                    {{ __('messages.show') }} </label>
                                <select id="per-page"
                                    class="bg-white p-2 dark:border-gray-700  dark:text-gray-300 dark:bg-gray-800">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                    <option value="200">200</option>
                                </select>
                                <label for="perPage" class="text-gray-800 dark:text-gray-300 ml-1 ">
                                    {{ __('messages.entries') }} </label>
                            </div>
                            <div class="mt-4">

                                <input type="text" id="search-input" placeholder="{{ __('messages.inpur_search') }}"
                                    class="block w-full  text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input">
                            </div>
                        </div>
                        <!-- PAGINATOR JQUERY END -->
                        <!-- Tables -->
                        <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                            <div class="w-full overflow-x-auto">
                                <table class="w-full whitespace-no-wrap"
                                    wire:key="miTablaKey-{{ $selectedUser8 }}-{{ uniqid() }}" id="miTabla">
                                    <thead>
                                        <tr
                                            class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                            <th class="px-4 py-3" wire:click="sortBy('operations.id')">Id
                                                @if ($sortBy === 'operations.id')
                                                    @if ($sortDirection === 'asc')
                                                        <i class="fa-solid fa-arrow-up"></i>
                                                    @else
                                                        <i class="fa-solid fa-arrow-down"></i>
                                                    @endif
                                                @endif
                                            </th>
                                            <th class="px-4 py-3">{{ __('messages.budget') }}</th>
                                            <th class="px-4 py-3">{{ __('messages.operations_username') }}</th>
                                            <th class="px-4 py-3">{{ __('messages.operations_category') }}</th>
                                            <th class="px-4 py-3">{{ __('messages.operations_subcategory') }}</th>
                                            <th class="px-4 py-3">{{ __('messages.operations_description') }}</th>
                                            <th class="px-4 py-3">{{ __('messages.operations_currency') }}</th>
                                            <th class="px-4 py-3">{{ __('messages.operations_operation') }}</th>
                                            <th class="px-4 py-3">{{ __('messages.operations_rate_conv_usd') }}</th>
                                            <th class="px-4 py-3">{{ __('messages.operations_total_in_usd') }}</th>
                                            <th class="px-4 py-3">{{ __('messages.operations_status') }}</th>
                                            <th class="px-4 py-3" wire:click="sortBy('operations.id')">
                                                {{ __('messages.operations_date') }} @if ($sortBy === 'operations.id')
                                                    @if ($sortDirection === 'asc')
                                                        <i class="fa-solid fa-arrow-up"></i>
                                                    @else
                                                        <i class="fa-solid fa-arrow-down"></i>
                                                    @endif
                                                @endif
                                            </th>
                                            <th class="px-4 py-3">{{ __('messages.action') }}</th>
                                            <th class="px-4 py-3">
                                                @if (!$data->isEmpty())
                                                    <input type="checkbox" wire:model="selectAll" id="select-all">
                                                @endif
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                        @forelse($data as $item)
                                            <tr translate="no"
                                                class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                                <td class="px-4 py-3 text-center">

                                                    {{ $item->id }}

                                                </td>
                                                <td class="px-4 py-3 text-xs">
                                                    {{ isset($item->date)? \Carbon\Carbon::parse($item->date)->locale('es')->isoFormat('MMMM [de] YYYY') . ' - ': '' }}
                                                    {{ isset($item->budget_currency_total) && $item->budget_currency_total != 0 ? number_format($item->budget_currency_total, 0, '.', ',') . ' $' : 'N/A' }}

                                                </td>
                                                <td class="px-4 py-3 text-xs">
                                                    {{ $item->username }}

                                                </td>
                                                <td class="px-4 py-3 text-xs">
                                                    {{ $item->category_name }}
                                                </td>
                                                <td class="px-4 py-3 text-xs">
                                                    {{ $item->display_name }}

                                                </td>
                                                <td class="px-4 py-3 text-xs">
                                                    {{ $item->operation_description }}
                                                </td>
                                                <td class="px-4 py-3 text-xs">
                                                    {{ $item->operation_currency_type === 'Blue-ARS' ? 'ARS' : $item->operation_currency_type }}

                                                </td>
                                                <td class="px-4 py-3 text-xs">

                                                    {{ number_format($item->operation_amount, 2, '.', ',') }}

                                                </td>
                                                <td class="px-4 py-3 text-xs">


                                                    {{ is_numeric($item->operation_currency) ? number_format($item->operation_currency, 2, '.', ',') : $item->operation_currency }}
                                                </td>
                                                <td class="px-4 py-3 text-xs">

                                                    {{ number_format($item->operation_currency_total, 2, '.', ',') }}
                                                    $
                                                </td>
                                                <td class="px-4 py-3 text-xs">

                                                    @if ($item->operation_status == '1')
                                                        <span
                                                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                            {{ $item->status_description }}
                                                        </span>
                                                    @elseif ($item->operation_status == '3')
                                                        <span
                                                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:text-red-100 dark:bg-red-700">
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
                                                            class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                                            {{ $item->status_description }}
                                                        </span>
                                                    @endif


                                                </td>
                                                <td class="px-4 py-3 text-xs">

                                                    @if (app()->getLocale() === 'en')
                                                        <span>{{ \Carbon\Carbon::parse($item->operation_date)->translatedFormat('m/d/Y') }}</span>
                                                    @elseif(app()->getLocale() === 'pt')
                                                        <span>{{ \Carbon\Carbon::parse($item->operation_date)->translatedFormat('d/m/Y') }}</span>
                                                    @else
                                                        <span>{{ \Carbon\Carbon::parse($item->operation_date)->format('d/m/Y') }}</span>
                                                    @endif


                                                </td>
                                                <td class="px-4 py-3 text-sm">

                                                    <button wire:click="edit({{ $item->id }})"
                                                        class="bg-blue-600 duration-500 ease-in-out hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"><i
                                                            class="fa-solid fa-pen-to-square"></i></button>
                                                    <button
                                                        wire:click="$emit('deleteData', {{ $item->id }}, '{{ $item->operation_description }}')"
                                                        class="bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white font-bold py-2 px-4 rounded"><i
                                                            class="fa-solid fa-trash"></i></button>

                                                </td>
                                                <td class="px-4 py-3 text-sm">
                                                    <input type="checkbox" wire:model="checkedSelected"
                                                        value="{{ $item->id }}"
                                                        id="checkbox-{{ $item->id }}">

                                                </td>
                                            </tr>

                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                                <!-- PAGINATOR JQUERY START -->
                                <div wire:key="updateMonthData-{{ $updateKey }}">
                                    <div id="pagination-controls" class="flex items-center justify-between mt-4 my-3">
                                        <div id="entries-info"
                                            class="entries-info mr-2 text-gray-700 dark:text-gray-400"></div>
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
                                                var searchTerm = $('#search-input').val().toLowerCase(); // Obtener el término de búsqueda
                                                var filteredRows = $('tbody tr').filter(function() {
                                                    return $(this).text().toLowerCase().indexOf(searchTerm) > -1;
                                                });

                                                var totalFilteredEntries = filteredRows.length;
                                                var totalPages = Math.ceil(totalFilteredEntries / rowsPerPage);

                                                var startEntry = page * rowsPerPage + 1;
                                                var endEntry = Math.min((page + 1) * rowsPerPage, totalFilteredEntries);

                                                $('tbody tr').hide();
                                                filteredRows.slice(page * rowsPerPage, (page + 1) * rowsPerPage).show();
                                                updateEntriesInfo(startEntry, endEntry, totalFilteredEntries);
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
                                                        totalFilteredEntries + ' entries (filtered from ' + $('tbody tr').length +
                                                        ' total entries)';
                                                    $('#prev-page, #next-page').show(); // Mostrar botones de paginación
                                                } else {
                                                    entriesInfo = 'Showing 0 to 0 of 0 entries (filtered from ' + $('tbody tr').length +
                                                        ' total entries)';
                                                    $('#prev-page, #next-page').hide(); // Ocultar botones de paginación
                                                }
                                                $('#entries-info').text(entriesInfo);
                                            }

                                            function updatePaginationButtons(page, totalPages) {
                                                $('#prev-page').prop('disabled', page === 0); // Deshabilitar "Previous" en la primera página
                                                $('#next-page').prop('disabled', page === totalPages -
                                                    1); // Deshabilitar "Next" en la última página
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

                                            $('#search-input').on('input', function() {
                                                currentPage = 0; // Resetear a la primera página al realizar una nueva búsqueda
                                                showPage(currentPage);
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
                                                    {{ __('messages.budget_expense_management') }}

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
                                                            <label for="operation_description"
                                                                class="block text-gray-700 text-sm font-bold mb-2">
                                                                {{ __('messages.modal_operations_user') }}</label>

                                                            <div wire:ignore>
                                                                <select id="users_select"
                                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                    wire:model="user_selected"
                                                                    wire:change="updateCategoryUser">
                                                                    <option></option>
                                                                    @foreach ($users->groupBy('email') as $nameUser => $groupedEmails)
                                                                        <optgroup label="{{ $nameUser }}">
                                                                            @foreach ($groupedEmails as $email)
                                                                                <option value="{{ $email->id }}">
                                                                                    {{ $email->username }}</option>
                                                                            @endforeach
                                                                        </optgroup>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <script>
                                                                $(document).ready(function() {
                                                                    $('#users_select').select2();

                                                                    // Escucha el cambio en Select2 y actualiza Livewire para el selectUserAssignSubcategory
                                                                    $('#users_select').on('change', function(e) {
                                                                        const selectedEmails = $(this).val();
                                                                        const index = $(this).data('index');
                                                                        @this.set('user_selected', selectedEmails);
                                                                        @this.call('updateCategoryUser');
                                                                        @this.call('updateBudgetUser');
                                                                    });
                                                                });
                                                            </script>

                                                            @error('user_selected')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-4">
                                                            <label for="exampleFormControlInput1"
                                                                class="block text-gray-700 text-sm font-bold mb-2">
                                                                {{ __('messages.select_a_budget') }}
                                                                :</label>

                                                            <select
                                                                class="shadow capitalize appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                style="width: 100%" wire:model="budget_id">
                                                                <option></option>
                                                                <option value="na">N/A</option>
                                                                @if ($this->selectedUser8 && $budgets->count() > 0)
                                                                    @foreach ($budgets->groupBy('budget_date') as $date => $groupedBudgets)
                                                                        @php
                                                                            $formattedDate = \Carbon\Carbon::parse(
                                                                                $date,
                                                                            )
                                                                                ->locale('es')
                                                                                ->isoFormat('MMMM [de] YYYY');
                                                                        @endphp

                                                                        @foreach ($groupedBudgets as $budget)
                                                                            <option value="{{ $budget->id }}"
                                                                                @if ($budget->id == $budget_id) selected @endif>
                                                                                {{ $formattedDate }} -
                                                                                {{ $budget->budget_currency_total }} $
                                                                            </option>
                                                                        @endforeach
                                                                    @endforeach

                                                                @endif

                                                            </select>




                                                            <script>
                                                                document.addEventListener('livewire:load', function() {
                                                                    Livewire.hook('message.sent', () => {
                                                                        // Vuelve a aplicar Select2 después de cada actualización de Livewire
                                                                        $('#budget_id_select').select2({
                                                                            width: 'resolve' // need to override the changed default
                                                                        });
                                                                    });
                                                                });

                                                                $(document).ready(function() {
                                                                    // Inicializa Select2
                                                                    $('#budget_id_select').select2();

                                                                    // Escucha el cambio en Select2 y actualiza Livewire
                                                                    $('#budget_id_select').on('change', function(e) {
                                                                        @this.set('budget_id', $(this).val());
                                                                    });
                                                                });
                                                            </script>
                                                            @error('budget_id')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-4">
                                                            <label for="operation_description"
                                                                class="block text-gray-700 text-sm font-bold mb-2">
                                                                {{ __('messages.modal_operations_description') }}</label>
                                                            <input type="text" autocomplete="off"
                                                                id="operation_description"
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                maxlength="50"
                                                                placeholder="{{ __('messages.enter_expense_description') }}"
                                                                wire:model="operation_description">
                                                            @error('operation_description')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="mb-4">
                                                            <label for="operation_amount"
                                                                class="block text-gray-700 text-sm font-bold mb-2">
                                                                {{ __('messages.modal_operations_conversion_from') }}</label>
                                                            <div wire:ignore>
                                                                <select wire:model="selectedCurrencyFrom"
                                                                    wire:change="showSelectedCurrency"
                                                                    id="selectedCurrencyFrom" style="width: 100%;">
                                                                    <option value="">
                                                                        {{ __('messages.modal_operations_select_a_option') }}
                                                                    </option>
                                                                    <option value="Blue-ARS">
                                                                        {{ __('messages.modal_operations_argentine_peso') }}
                                                                    </option>
                                                                    @if ($listCurrencies)
                                                                        @foreach ($listCurrencies['currencies'] as $codigo => $nombre)
                                                                            <option value="{{ $codigo }}">
                                                                                {{ $nombre }}
                                                                                ({{ $codigo }})
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
                                                            <label for="operation_amount"
                                                                class="block text-gray-700 text-sm font-bold mb-2">
                                                                {{ __('messages.modal_operations_operation_with') }}
                                                                <span
                                                                    class="text-blue-700">{{ $this->selectedCurrencyFromARS }}</label>


                                                            <div class="flex items-center relative">
                                                                <input type="text" name="amountField"
                                                                    autocomplete="off" id="operation_amount"
                                                                    wire:model="operation_amount"
                                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pr-8"
                                                                    placeholder="{{ __('messages.enter_income_transaction_amount') }}
">

                                                                <span
                                                                    class="absolute right-0 top-0 mt-2 mr-2 text-gray-500">{{ $this->selectedCurrencyFromARS }}</span>
                                                            </div>

                                                            @error('operation_amount')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>


                                                        <div class="mb-4">
                                                            <label for="operation_currency"
                                                                class="block text-gray-700 text-sm font-bold mb-2">
                                                                {{ __('messages.modal_operations_rate_conv_usd') }}
                                                            </label>

                                                            <input type="text" autocomplete="off"
                                                                id="operation_currency"
                                                                wire:model="operation_currency"
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                placeholder="">

                                                            <input type="text" hidden
                                                                wire:model="operation_currency_type" readonly>
                                                            @error('operation_currency_type')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                            @error('operation_currency')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-4 relative">
                                                            <label for="operation_currency"
                                                                class="block text-gray-700 text-sm font-bold mb-2">
                                                                {{ __('messages.modal_operations_total_in_usd') }}
                                                            </label>

                                                            <div class="flex items-center relative">
                                                                <span
                                                                    class="absolute left-0 top-0 mt-2 ml-2 text-gray-500">$</span>

                                                                <input type="text" name="totalbudget2"
                                                                    autocomplete="off" id="operation_currency_total"
                                                                    readonly wire:model="operation_currency_total"
                                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pl-8"
                                                                    placeholder="">


                                                            </div>

                                                            @error('operation_currency_total')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>


                                                        <div class="mb-4">
                                                            <label for="operation_date"
                                                                class="block text-gray-700 text-sm font-bold mb-2">
                                                                {{ __('messages.modal_operations_date') }}</label>
                                                            <div wire:ignore>
                                                                <input type="text" id="myDatePicker" readonly
                                                                    wire:model="operation_date"
                                                                    placeholder="dd/mm/yyyy" autocomplete="off"
                                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                            </div>
                                                            @error('operation_date')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-4">
                                                            <label for="exampleFormControlInput2"
                                                                class="block text-gray-700 text-sm font-bold mb-2">
                                                                {{ __('messages.modal_operations_expense_category') }}

                                                            </label>

                                                            <select wire:model="category_id"
                                                                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                                                                style="width: 100%; max-height: 200px; overflow-y: auto;">
                                                                <option value=""></option>
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
                                                        @if ($subcategoryMessage)
                                                            <p class="text-gray-600 mb-3">{{ $subcategoryMessage }}
                                                            </p>
                                                        @endif
                                                        <div id="subcategory-container" class="my-5"
                                                            wire:key="subcategory-{{ $category_id }}">

                                                            @if ($showSubcategories)
                                                                <div class="mb-4">
                                                                    <label for="exampleFormControlInput2"
                                                                        class="block text-gray-700 text-sm font-bold mb-2">
                                                                        {{ __('messages.modal_operations_subcategory') }}</label>

                                                                    <div wire:ignore>
                                                                        <select wire:model="registeredSubcategoryItem"
                                                                            id="select2SubcategoryId"
                                                                            style="width: 100%;">
                                                                            <option value=""></option>
                                                                            <option value="N/A">N/A</option>
                                                                            {{-- Display Assigned Subcategories --}}
                                                                            @if (is_array($subcategory_id) && count($subcategory_id) > 0)
                                                                                @php
                                                                                    $subcategories = \App\Models\Subcategory::whereIn(
                                                                                        'id',
                                                                                        $subcategory_id,
                                                                                    )->get();
                                                                                @endphp

                                                                                {{-- Find the selected subcategory --}}
                                                                                @php
                                                                                    $selectedSubcategory = $subcategories
                                                                                        ->where(
                                                                                            'id',
                                                                                            $registeredSubcategoryItem,
                                                                                        )
                                                                                        ->first();
                                                                                @endphp

                                                                                {{-- Display the selected subcategory first --}}
                                                                                @if ($selectedSubcategory)
                                                                                    <option
                                                                                        value="{{ $selectedSubcategory->id }}">
                                                                                        {{ $selectedSubcategory->subcategory_name }}
                                                                                    </option>
                                                                                @endif

                                                                                {{-- Display the rest of the subcategories --}}
                                                                                @foreach ($subcategories as $subcategory)
                                                                                    @if ($subcategory->id !== $registeredSubcategoryItem)
                                                                                        <option
                                                                                            value="{{ $subcategory->id }}">
                                                                                            {{ $subcategory->subcategory_name }}
                                                                                        </option>
                                                                                    @endif
                                                                                @endforeach
                                                                            @endif
                                                                        </select>
                                                                        <script>
                                                                            $(document).ready(function() {
                                                                                $('#select2SubcategoryId').select2();

                                                                                // Escucha el cambio en Select2 y actualiza Livewire para el selectUserAssignSubcategory
                                                                                $('#select2SubcategoryId').on('change', function(e) {
                                                                                    const selectedData = $(this).val();
                                                                                    const index = $(this).data('index');
                                                                                    @this.set('subcategory_id', selectedData);
                                                                                    @this.call('updateSubCategoryUser');
                                                                                });
                                                                            });
                                                                        </script>
                                                                        <input type="hidden" readonly
                                                                            wire:model="registeredSubcategoryItem"
                                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">

                                                                    </div>
                                                                    @if ($subcategoryMessage)
                                                                        <p class="text-gray-500 mb-3">
                                                                            {{ $subcategoryMessage }}</p>
                                                                    @endif
                                                                </div>
                                                            @endif

                                                            <script>
                                                                document.addEventListener('livewire:load', function() {
                                                                    Livewire.hook('message.sent', () => {
                                                                        // Vuelve a aplicar Select2 después de cada actualización de Livewire
                                                                        applySelect2('#select2CategoryId, #select2SubcategoryId, #selectedCurrencyFrom');
                                                                    });
                                                                });

                                                                $(document).ready(function() {
                                                                    // Inicializa Select2 para category_id
                                                                    initializeSelect2('#select2CategoryId', function(e) {
                                                                        @this.set('category_id', $(this).val());
                                                                    });

                                                                    // Muestra el select2 de subcategorías al seleccionar una categoría
                                                                    @if ($showSubcategories)
                                                                        initializeSelect2('#select2SubcategoryId', function(e) {
                                                                            @this.set('subcategory_id', $(this).val());
                                                                        });
                                                                    @endif

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
                                                                        width: 'resolve' // need to override the changed default
                                                                    });
                                                                }
                                                            </script>
                                                        </div>

                                                        <div class="mb-4">
                                                            <label for="exampleFormControlInput2"
                                                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.modal_operations_state') }}
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
                                                            {{ __('messages.button_register') }}
                                                        </button>
                                                    </span>
                                                    <span
                                                        class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                                        <button wire:click="closeModal()" type="button"
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
                            <!-- MODAL -->
                        </div>

                    @endif
                </div>
            </main>


            <!-- END PANEL MAIN CATEGORIES -->

        </div>
    </div>


</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('deleteData', function(id, description) {
            console.log('Evento "deleteData" emitido con ID: ' + id);
            Swal.fire({
                title: '{!! __('messages.delete_confirmation_title') !!} ' +
                    '<span style="color:#9333ea">' + description + '</span>' + '?',
                text: "{{ __('messages.delete_confirmation_text') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __('messages.delete_confirmation_confirm_button') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('operations-admin', 'delete',
                        id); // Envía el Id al método delete
                    Swal.fire(
                        '{!! __('messages.delete_success_title') !!}',
                        '{{ __('messages.delete_success_message_your_data') }}' +
                        description +
                        ' {{ __('messages.delete_success_message_your_data_has_been') }}',
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
                dateFormat: "d/m/Y",
                onClose: function(selectedDates, dateStr, instance) {
                    // Actualiza Livewire con la nueva fecha cuando se selecciona una fecha
                    @this.set('operation_date', dateStr);
                    console.log(dateStr);
                }
            });

        });
    });
</script>




<script>
    document.addEventListener('livewire:load', function() {
        $(document).ready(function() {
            $('#miTabla').DataTable();
        });
        Livewire.on('reinitDataTable', function() {
            $('#miTabla').DataTable().destroy();
            $('#miTabla').DataTable();
        });
    });
</script>

<script>
    $(document).ready(function() {
        Livewire.on('modalOpenedAutonumeric3', function() {
            $('#operation_amount').mask('#.##0,00', {
                reverse: true
            });
        });
    });
</script>

<script>
    $(document).ready(function() {
        Livewire.on('modalOpenedAutonumeric4', function() {
            $('#operation_currency').mask('#.##0,00', {
                reverse: true
            });
        });
    });
</script>



<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('showConfirmation', () => {
            Swal.fire({
                title: "{{ __('messages.delete_confirmation_text_all_items') }}",
                text: "{{ __('messages.delete_confirmation_text') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('messages.delete_confirmation_confirm_button') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('operations-admin',
                        'deleteMultiple'); // Envía el Id al método delete
                    Swal.fire(
                        '{!! __('messages.delete_success_title') !!}',
                        '{{ __('messages.delete_confirmation_text_all') }}',
                        'success'
                    );

                }
            });
        });
    });
</script>
