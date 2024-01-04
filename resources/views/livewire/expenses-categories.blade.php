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
                            <i class="fa-solid fa-cash-register mr-3"></i>

                            <x-slot name="title">
                                {{ __('Expenses Categories') }}
                            </x-slot>
                            <a href="{{ route('expenses-categories') }}">
                                <span>Expenses Categories</span></a>
                        </div>

                    </div>
                    @can('manage admin')
                        <div class=" my-7 flex justify-between space-x-2">
                            <x-button wire:click="create()"><span class="font-semibold"> Create New <i
                                        class="fa-regular fa-folder-open"></i> </span>
                            </x-button>
                            <x-input id="name" type="text" wire:model="search" placeholder="Search..." autofocus
                                autocomplete="off" />
                        </div>
                    @endcan
                    <!-- Tables -->
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                        <th class="px-4 py-3">Nro</th>
                                        <th class="px-4 py-3">Item</th>
                                        <th class="px-4 py-3">Category</th>
                                        <th class="px-4 py-3">Assigned Categories </th>
                                        <th class="px-4 py-3">Subcategory</th>
                                        <th class="px-4 py-3">Assigned Subcategory </th>
                                        <th class="px-4 py-3">Date</th>
                                        @can('manage admin')
                                            <th class="px-4 py-3">Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                    @forelse($data as $item)
                                        <tr class="text-gray-700 text-center uppercase dark:text-gray-400">
                                            <td class="px-4 py-3 text-xs text-center">

                                                {{ $loop->iteration }}

                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ $item->main_category_name }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ Str::words($item->category_name, 2, '...') }}
                                            </td>

                                            <td class="px-4 py-3 text-xs">
                                                @if ($item->assignedUsers->count() === 0)
                                                    All Users
                                                @elseif (auth()->user()->hasRole('Admin'))
                                                    {{ $item->assignedUsers->pluck('username')->implode(', ') }}
                                                @elseif ($item->assignedUsers->pluck('id')->contains(auth()->user()->id))
                                                    {{ auth()->user()->username }}
                                                @else
                                                    Not Assigned
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                @if (!empty($item->subcategory_name))
                                                    {{ Str::words($item->subcategory_name, 2, '...') }}
                                                @else
                                                    unavailable
                                                @endif

                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                @php
                                                    $assignedUsernames = [];

                                                    foreach ($item->Subcategory as $subcategory) {
                                                        $assignedUsers = $subcategory->assignedUsersSubcategory;

                                                        if (!$assignedUsers->isEmpty()) {
                                                            if (
                                                                auth()
                                                                    ->user()
                                                                    ->hasRole('Admin')
                                                            ) {
                                                                // Include all assigned usernames for Admin
                                                                $assignedUsernames = array_merge($assignedUsernames, $assignedUsers->pluck('username')->toArray());
                                                            } elseif ($assignedUsers->pluck('id')->contains(auth()->user()->id)) {
                                                                // Include the user's own username for non-Admin users
                                                                $assignedUsernames[] = auth()->user()->username;
                                                            }
                                                        }
                                                    }
                                                @endphp

                                                {{ implode(', ', array_unique($assignedUsernames)) }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                            </td>
                                            @can('manage admin')
                                                <td class="px-4 py-3 text-sm">
                                                    <button wire:click="OpenModalUserAssignment({{ $item->id }})"
                                                        class="relative bg-emerald-600 duration-500 ease-in-out hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded"
                                                        x-data="{ showTooltip: false }" x-on:mouseenter="showTooltip = true"
                                                        x-on:mouseleave="showTooltip = false" x-tooltip="Editar artículo">
                                                        <i class="fa-solid fa-users-line"></i>

                                                        <!-- Tooltip -->
                                                        <div x-show="showTooltip" x-cloak
                                                            class="absolute left-0 bg-gray-800 text-white px-2 py-1 rounded mt-3 z-10">
                                                            Category Assignment
                                                        </div>
                                                    </button>
                                                    <button wire:click="edit({{ $item->id }})"
                                                        class="bg-blue-600 duration-500 ease-in-out hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"><i
                                                            class="fa-solid fa-pen-to-square"></i></button>
                                                    <button wire:click="$emit('deleteData',{{ $item->id }})"
                                                        class="bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white font-bold py-2 px-4 rounded"><i
                                                            class="fa-solid fa-trash"></i></button>


                                                </td>
                                            @endcan
                                        </tr>

                                    @empty
                                        <tr class="text-center">
                                            <td colspan="7">
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
                                                Expenses Category
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
                                                        <label for="exampleFormControlInput1"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                                                        <input type="text" autocomplete="off"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            id="exampleFormControlInput1" required maxlength="40"
                                                            placeholder="Enter Category" wire:model="category_name">
                                                        @error('category_name')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="exampleFormControlInput2"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                                                        <input type="text" autocomplete="off"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            id="exampleFormControlInput1" maxlength="50"
                                                            placeholder="Enter Description"
                                                            wire:model="category_description">
                                                        @error('category_description')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                                            Subcategory:</label>

                                                        @foreach ($subcategory_name as $index => $subcategory)
                                                            <div class="mb-4 flex items-center">
                                                                <input type="text" autocomplete="off"
                                                                    wire:model="subcategory_name.{{ $index }}"
                                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2"
                                                                    placeholder="Enter Subcategory {{ $index + 1 }}">

                                                                @if ($index >= 0)
                                                                    <button
                                                                        class="bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                                                        wire:click.prevent="removeSubcategory({{ $index }})">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>
                                                                @endif

                                                                @error("subcategory_name.{$index}")
                                                                    <span class="text-red-500">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        @endforeach

                                                        <button
                                                            class="relative bg-teal-600 duration-500 ease-in-out hover:bg-teal-700 text-white text-sm font-bold py-2 px-4 rounded"
                                                            wire:click.prevent="addSubcategory">+ Subcategory</button>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="exampleFormControlInput2"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Type
                                                        </label>
                                                        <select wire:model="main_category_id"
                                                            class="block w-full mt-1 text-sm dark:text-gray-700 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                                                            <option value="">

                                                            </option>
                                                            @foreach ($mainCategoriesRender as $item)
                                                                <option value="{{ $item->id }}">
                                                                    {{ $item->title }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @error('main_category_id')
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
                                                        Finish
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

                        <!-- MODAL SUBCATEGORY -->
                        @if ($showModal)
                            <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
                                <div
                                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity">
                                        <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
                                    </div>
                                    <!-- This element is to trick the browser into centering the modal contents. -->
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                        role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                        <div
                                            class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                                            <!--Modal title-->
                                            <div class="text-center"></div>
                                            <h5 class=" text-xl font-medium leading-normal text-gray-700 text-center dark:text-neutral-200"
                                                id="exampleModalLabel">
                                                Assigning Users To <span
                                                    class="text-emerald-700 capitalize">{{ $categoryNameSelected }}</span>
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
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                                            Category:</label>

                                                        <input type="text" autocomplete="off"
                                                            wire:model="categoryNameSelected"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('categoryNameSelected')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="exampleFormControlInput1"
                                                            class="block text-gray-700 text-sm font-bold mb-2 capitalize">
                                                            Users Assigned to
                                                            <span class="text-emerald-600">
                                                                {{ $categoryNameSelected }}</span></label>

                                                        <div wire:ignore>
                                                            <select multiple wire:model="user_id_assign"
                                                                id="selectUserAssign" style="width: 100%">
                                                                <option value="all">All Users</option>
                                                                @foreach ($users->groupBy('name') as $nameUser => $groupedEmails)
                                                                    <optgroup label="{{ $nameUser }}">
                                                                        @foreach ($groupedEmails as $email)
                                                                            <option value="{{ $email->id }}">
                                                                                {{ $email->email }}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <script>
                                                            document.addEventListener('livewire:load', function() {
                                                                Livewire.hook('message.sent', () => {
                                                                    // Vuelve a aplicar Select2 después de cada actualización de Livewire
                                                                    $('#selectUserAssign').select2({
                                                                        width: 'resolve' // need to override the changed default
                                                                    });
                                                                });
                                                            });

                                                            $(document).ready(function() {
                                                                // Inicializa Select2
                                                                $('#selectUserAssign').select2();

                                                                // Escucha el cambio en Select2 y actualiza Livewire
                                                                $('#selectUserAssign').on('change', function(e) {
                                                                    // Obtiene el valor seleccionado
                                                                    var selectedUserIds = $(this).val();
                                                                    // Guarda el estado inicial del Select2 al cargar la página
                                                                    var initialSelectedValues = $('#selectUserAssign').val();
                                                                    if (selectedUserIds && selectedUserIds.includes('all')) {
                                                                        // Muestra un mensaje de confirmación con SweetAlert2
                                                                        Swal.fire({
                                                                            title: 'Confirm',
                                                                            text: 'Are you sure you select all users?',
                                                                            icon: 'warning',
                                                                            showCancelButton: true,
                                                                            confirmButtonText: 'Yes, select all',
                                                                            cancelButtonText: 'Cancel',
                                                                            confirmButtonColor: '#3085d6',
                                                                            cancelButtonColor: '#d33',
                                                                        }).then((result) => {
                                                                            if (result.isConfirmed) {
                                                                                // Confirma la selección y ejecuta la función Livewire
                                                                                @this.set('user_id_assign', ['all']); // Set the value to 'all'
                                                                                @this.call('categoryAssignment');
                                                                                // Reinicia el valor del selectUserAssign
                                                                                $('#selectUserAssign').val(null).trigger('change');

                                                                            } else {
                                                                                // Excluye 'all' del conjunto de valores seleccionados
                                                                                var filteredValues = initialSelectedValues.filter(val => val !== 'all');
                                                                                $('#selectUserAssign').val(filteredValues).trigger('change');
                                                                                // Revierte la selección en Select2 y no ejecuta la acción de Livewire en caso de cancelación
                                                                                initialSelectedValues = selectedUserIds;
                                                                            }
                                                                        });
                                                                    } else {
                                                                        // Actualiza Livewire con los nuevos valores
                                                                        @this.set('user_id_assign', selectedUserIds);

                                                                        // Llama a la función categoryAssignment
                                                                        @this.call('categoryAssignment');
                                                                    }
                                                                });

                                                                // Escucha el evento de eliminación en Select2 y actualiza Livewire
                                                                $('#selectUserAssign').on('select2:unselect', function(e) {
                                                                    // Llama a la función categoryAssignment
                                                                    @this.call('categoryAssignment');
                                                                });
                                                            });
                                                        </script>




                                                        <!--INCLUDE ALERTS MESSAGES-->

                                                        <x-message-assigned />



                                                        <!-- END INCLUDE ALERTS MESSAGES-->


                                                        @error('user_id_assign')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">

                                                        @if ($userAssignments && count($userAssignments) > 0)
                                                            <label
                                                                class="block text-gray-700 text-lg font-bold mb-2 text-end">
                                                                Subcategories:
                                                            </label>


                                                            @foreach ($userAssignments as $index => $assignment)
                                                                <!-- Dentro de tu vista Blade -->
                                                                <div class="flex flex-wrap">
                                                                    <!-- Primer div de la izquierda -->
                                                                    <div
                                                                        class="flex-none w-1/2 border-r border-gray-700 mb-7">
                                                                        <label
                                                                            for="user_id_assignSubcategory_{{ $index }}"
                                                                            class="block text-gray-700 text-sm font-bold mb-2 capitalize">
                                                                            {{ $index + 1 }}) Select Users To assign
                                                                            :

                                                                        </label>


                                                                        <div class="flex items-center">

                                                                            <select x-data="{ borderClass: '' }"
                                                                                x-init="Livewire.on('sessionAssigned', () => {
                                                                                    borderClass = 'border-green-700';
                                                                                    setTimeout(() => {
                                                                                        borderClass = '';
                                                                                    }, 7000);
                                                                                });
                                                                                Livewire.on('sessionRemoved', () => {
                                                                                    borderClass = 'border-red-700';
                                                                                    setTimeout(() => {
                                                                                        borderClass = '';
                                                                                    }, 7000);
                                                                                });"
                                                                                wire:model="selectedUserId.{{ $index }}"
                                                                                id="users_selectCategoryId_{{ $index }}"
                                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2 "
                                                                                :class="borderClass"
                                                                                wire:loading.attr="disabled">

                                                                                <option value="">-- Select a User
                                                                                    --</option>

                                                                                @php
                                                                                    $sortedUserIds = collect($user_id_assign)->sortDesc();
                                                                                    $assignedUserIds = collect($assignment['users'])
                                                                                        ->pluck('id')
                                                                                        ->toArray();
                                                                                    $availableUsersCount = count(
                                                                                        $sortedUserIds->reject(function ($userId) use ($assignedUserIds) {
                                                                                            return $userId === 'all' || in_array($userId, $assignedUserIds);
                                                                                        }),
                                                                                    );
                                                                                @endphp

                                                                                {{-- Show the option "-- Assign All Users --" only if there are available users --}}
                                                                                @if ($availableUsersCount > 0)
                                                                                    <option value="AllUsers">-- Assign
                                                                                        All Users --</option>
                                                                                @endif

                                                                                @foreach ($sortedUserIds as $userId)
                                                                                    @if ($userId !== 'all' && !in_array($userId, $assignedUserIds))
                                                                                        @php
                                                                                            $user = $users->firstWhere('id', $userId);
                                                                                        @endphp
                                                                                        <option
                                                                                            value="{{ $userId }}">
                                                                                            {{ $user->username ?? 'User Unavailable' }}
                                                                                        </option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>




                                                                            <button
                                                                                class="relative bg-teal-600 duration-500 ease-in-out hover:bg-teal-700 text-white text-sm font-bold py-1 px-2 rounded mr-6"
                                                                                wire:click.prevent="AssignToSubCategoryUser('{{ $assignment['subcategory_name'] }}', '{{ $selectedUserId[$index] ?? '' }}')"
                                                                                wire:loading.attr="disabled"
                                                                                :disabled="!$selectedUserId || !isset(
                                                                                        $selectedUserId[$index]) ||
                                                                                    !
                                                                                    $selectedUserId[$index]">
                                                                                <i class="fa-solid fa-arrow-right"></i>
                                                                            </button>

                                                                            @error("selectedUserId.{$index}")
                                                                                <span
                                                                                    class="text-red-500">{{ $message }}</span>
                                                                            @enderror
                                                                        </div>

                                                                        <!-- Contenido del primer div va aquí -->
                                                                    </div>

                                                                    <!-- Segundo div de la derecha -->
                                                                    <div class="flex-none w-1/2 ">
                                                                        <label
                                                                            for="user_id_assignSubcategory_{{ $index }}"
                                                                            id="subcategory_{{ $index }}"
                                                                            class="block text-gray-700 text-sm font-bold mb-2 text-right">
                                                                            <span class="text-teal-700 capitalize">
                                                                                {{ $index + 1 }})
                                                                                {{ $assignment['subcategory_name'] }}</span>

                                                                        </label>
                                                                        <!-- Contenido del segundo div va aquí -->
                                                                        <div class="flex items-center">

                                                                            <!-- Your Blade file with Livewire component -->
                                                                            <select x-data="{ borderClassSubcategory: '' }"
                                                                                x-init="Livewire.on('sessionAssignedSubcategory', () => {
                                                                                    borderClassSubcategory = 'border-green-700';
                                                                                    setTimeout(() => {
                                                                                        borderClassSubcategory = '';
                                                                                    }, 7000);
                                                                                });
                                                                                Livewire.on('sessionRemovedSubcategory', () => {
                                                                                    borderClassSubcategory = 'border-red-700';
                                                                                    setTimeout(() => {
                                                                                        borderClassSubcategory = '';
                                                                                    }, 7000);
                                                                                });
                                                                                Livewire.on('sessionRemoved', () => {
                                                                                    borderClassSubcategory = 'border-red-700';
                                                                                    setTimeout(() => {
                                                                                        borderClassSubcategory = '';
                                                                                    }, 7000);
                                                                                });"
                                                                                wire:model="selectedUserIdDelete.{{ $index }}"
                                                                                data-index="{{ $index }}"
                                                                                id="selectedUserIdDelete.{{ $index }}"
                                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline ml-5 mr-2"
                                                                                :class="borderClassSubcategory"
                                                                                wire:loading.attr="disabled">

                                                                                <option value="">-- Select a
                                                                                    User
                                                                                    --</option>
                                                                                {{-- Mostrar la opción "-- Remove All User --" solo si hay usuarios --}}
                                                                                @if (count($assignment['users']) > 0)
                                                                                    <option value="removedAll">--
                                                                                        Remove All Users --</option>
                                                                                @endif
                                                                                @foreach ($assignment['users'] as $user)
                                                                                    <option
                                                                                        value="{{ is_object($user) ? $user->id : $user['id'] }}">
                                                                                        {{ optional($user)['username'] ?? 'User Unavailable' }}
                                                                                    </option>
                                                                                @endforeach


                                                                            </select>

                                                                            @if (count($assignment['users']) > 0)
                                                                                <button
                                                                                    class="relative bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white text-sm font-bold py-1 px-2 rounded mr-0"
                                                                                    wire:click.prevent="$emit('deleteDataUserSubcategory','{{ $assignment['subcategory_name'] }}', '{{ $selectedUserIdDelete[$index] ?? '' }}')"
                                                                                    wire:loading.attr="disabled"
                                                                                    :disabled="!$selectedUserIdDelete || !
                                                                                        isset(
                                                                                            $selectedUserIdDelete[
                                                                                                $index]) || !
                                                                                        $selectedUserIdDelete[
                                                                                            $index]">
                                                                                    <i class="fa-solid fa-trash"></i>
                                                                                </button>
                                                                            @endif

                                                                        </div>
                                                                        @error("subcategory_name.{$index}")
                                                                            <span
                                                                                class="text-red-500">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                </div>
                                                                <!-- END DIV -->
                                                            @endforeach

                                                        @endif

                                                    </div>


                                                </div>
                                            </div>

                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                    <button type="button"wire:click="closeModalUserAssignment()"
                                                        class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                        Finish
                                                    </button>
                                                </span>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <!-- END MODAL SUBCATEGORY -->


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
                    Livewire.emitTo('expenses-categories', 'delete',
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
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('deleteDataUserSubcategory', function(subcategoryName, selectedUserIdDelete) {
            if (selectedUserIdDelete) {
                showDeleteConfirmation(subcategoryName, selectedUserIdDelete);
            } else {
                showUserNotSelectedAlert();
            }
        });

        function showDeleteConfirmation(subcategoryName, selectedUserIdDelete) {
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
                    deleteItem(subcategoryName, selectedUserIdDelete);
                }
            });
        }

        function deleteItem(subcategoryName, selectedUserIdDelete) {
            Livewire.emitTo('expenses-categories', 'deleteSubcategoryAssignments', subcategoryName,
                selectedUserIdDelete);
            Swal.fire(
                'Deleted!',
                'Your Data has been deleted.',
                'success'
            );
        }

        function showUserNotSelectedAlert() {
            Swal.fire({
                title: 'Please select a user first',
                icon: 'info',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        }
    });
</script>
