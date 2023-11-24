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
                                {{ __('Income Categories') }}
                            </x-slot>
                            <a href="{{ route('expenses-categories') }}">
                                <span>Income Categories</span></a>
                        </div>

                    </div>
                    @can('manage admin')
                        <div class=" my-7 flex justify-between space-x-2">
                            <x-button wire:click="create()"><span class="font-semibold"> Create New <i
                                        class="fa-regular fa-folder-open"></i></span>
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
                                        <tr class="text-gray-700 text-center  uppercase dark:text-gray-400">
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

                                                @foreach ($item->Subcategory as $subcategory)
                                                    @if ($subcategory->assignedUsersSubcategory->isEmpty())
                                                    @elseif (auth()->user()->hasRole('Admin'))
                                                        {{ $subcategory->assignedUsersSubcategory->pluck('username')->implode(', ') }}
                                                    @elseif ($subcategory->assignedUsersSubcategory->pluck('id')->contains(auth()->user()->id))
                                                        {{ auth()->user()->username }}
                                                    @else
                                                    @endif
                                                @endforeach


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
                                                Income Category
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
                                            <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                                                id="exampleModalLabel">
                                                Assigning Users {{ $categoryNameSelected }}
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
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            User Assign To Category</label>
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
                                                                    @this.set('user_id_assign', $(this).val());
                                                                });
                                                            });
                                                        </script>
                                                        @error('user_id_assign')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                                            Subcategories:</label>
                                                        <!-- Dentro de tu vista Blade -->


                                                        @foreach ($userAssignments as $index => $assignment)
                                                            <div class="mb-4">
                                                                <input type="text" autocomplete="off"
                                                                    wire:model="userAssignments.{{ $index }}.subcategory_name"
                                                                    id="subcategory_{{ $index }}"
                                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                    placeholder="Enter Subcategory" readonly>
                                                                @error("subcategory_name.{$index}")
                                                                    <span class="text-red-500">{{ $message }}</span>
                                                                @enderror

                                                            </div>

                                                            <div class="mb-4">
                                                                <label
                                                                    for="user_id_assignSubcategory_{{ $index }}"
                                                                    class="block text-gray-700 text-sm font-bold mb-2">
                                                                    Users Assign To <span class="text-emerald-700">
                                                                        {{ $assignment['subcategory_name'] }}:</span>

                                                                </label>
                                                                <div wire:ignore>
                                                                    @php
                                                                        // Verificar si el índice existe en el array antes de acceder a él
                                                                        $selectedUsers = isset($this->user_id_assignSubcategory[$index]) ? (array) $this->user_id_assignSubcategory[$index] : [];
                                                                    @endphp
                                                                    <select multiple
                                                                        wire:model="userAssignments.{{ $index }}.user_id_assignSubcategory"
                                                                        class="selectUserAssignSubcategory"
                                                                        data-index="{{ $index }}"
                                                                        style="width: 100%">

                                                                        <option value="all">All Users</option>
                                                                        @foreach ($users->groupBy('name') as $nameUser => $groupedEmails)
                                                                            <optgroup label="{{ $nameUser }}">
                                                                                @foreach ($groupedEmails as $email)
                                                                                    <option
                                                                                        value="{{ $email->id }}">
                                                                                        {{ $email->email }}
                                                                                    </option>
                                                                                @endforeach
                                                                            </optgroup>
                                                                        @endforeach
                                                                    </select>

                                                                </div>
                                                            </div>
                                                        @endforeach


                                                        <script>
                                                            document.addEventListener('livewire:load', function() {
                                                                Livewire.hook('message.sent', () => {
                                                                    // Vuelve a aplicar Select2 después de cada actualización de Livewire para el selectUserAssign
                                                                    $('#selectUserAssign').select2({
                                                                        width: 'resolve'
                                                                    });

                                                                    // Vuelve a aplicar Select2 después de cada actualización de Livewire para el selectUserAssignSubcategory
                                                                    $('.selectUserAssignSubcategory').select2({
                                                                        width: 'resolve'
                                                                    });
                                                                });
                                                            });

                                                            $(document).ready(function() {
                                                                // Inicializa Select2 para el selectUserAssign
                                                                $('#selectUserAssign').select2();

                                                                // Inicializa Select2 para el selectUserAssignSubcategory
                                                                $('.selectUserAssignSubcategory').select2();

                                                                // Escucha el cambio en Select2 y actualiza Livewire para el selectUserAssign
                                                                $('#selectUserAssign').on('change', function(e) {
                                                                    @this.set('user_id_assign', $(this).val());
                                                                });

                                                                // Escucha el cambio en Select2 y actualiza Livewire para el selectUserAssignSubcategory
                                                                $('.selectUserAssignSubcategory').on('change', function(e) {
                                                                    const index = $(this).data('index');
                                                                    @this.set('user_id_assignSubcategory.' + index, $(this).val());
                                                                });
                                                            });
                                                        </script>


                                                    </div>


                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                                    <button type="button" wire:click.prevent="categoryAssignment()"
                                                        wire:loading.attr="disabled" wire:target="categoryAssignment"
                                                        class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                        Finish
                                                    </button>
                                                </span>
                                                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                                                    <button wire:click="closeModalUserAssignment()" type="button"
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
                    Livewire.emitTo('income-categories', 'delete',
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
