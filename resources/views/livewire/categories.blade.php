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
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                </path>
                            </svg>

                            <x-slot name="title">
                                {{ __('Categories') }}
                            </x-slot>
                            <a href="{{ route('categories') }}">
                                <span>Categories</span></a>
                        </div>

                    </div>
                    <!-- Tables -->

                    <div class=' w-full overflow-hidden shadow-xl'>
                        @can('manage admin')
                            <div class="m-2 p-2 mb-5 flex justify-between space-x-2">
                                <x-button wire:click="create()"><span class="font-semibold"> Create New </span>
                                </x-button>
                                <x-input id="name" type="text" wire:model="search" placeholder="Search..." autofocus
                                    autocomplete="off" />
                            </div>
                        @endcan

                        <table
                            class=' my-5 mx-auto  w-full whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden'>
                            <thead class="bg-gray-900 dark:bg-blue-600">
                                <tr class="text-white text-left">
                                    <th class="font-semibold text-sm capitalize px-6 py-4"> Nro </th>
                                    <th class="font-semibold text-sm capitalize px-6 py-4"> Item </th>
                                    <th class="font-semibold text-sm capitalize px-6 py-4"> Category </th>
                                    <th class="font-semibold text-sm capitalize px-6 py-4"> Description </th>
                                    <th class="font-semibold text-sm capitalize px-6 py-4"> </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse($data as $item)
                                    <tr>
                                        <td class="px-6 py-4">

                                            <p class="text-gray-500 text-sm font-semibold tracking-wide">
                                                {{ $loop->iteration }} </p>
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 text-base  font-semibold tracking-wide">
                                            {{ $item->main_category_name }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 text-base  font-semibold tracking-wide">
                                            {{ $item->category_name }}
                                        </td>
                                        <td class="px-6 py-4 text-gray-500 text-base  font-semibold tracking-wide">
                                            {{ $item->category_description }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @can('manage admin')
                                                <button wire:click="edit({{ $item->id }})"
                                                    class="bg-blue-600 duration-500 ease-in-out hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"><i
                                                        class="fa-solid fa-pen-to-square"></i></button>
                                                <button wire:click="delete({{ $item->id }})"
                                                    class="bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white font-bold py-2 px-4 rounded"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="text-center">
                                        <td colspan="5">
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
                        <!-- MODAL -->
                        @if ($isOpen)
                            <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
                                <div
                                    class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                    <div class="fixed inset-0 transition-opacity">
                                        <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
                                    </div>
                                    <!-- This element is to trick the browser into centering the modal contents. -->
                                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>?

                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                        role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                        <div
                                            class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                                            <!--Modal title-->
                                            <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                                                id="exampleModalLabel">
                                                Category
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
                                        <form>
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="">
                                                    <div class="mb-4">
                                                        <label for="exampleFormControlInput1"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Category</label>
                                                        <input type="text" autocomplete="off"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            id="exampleFormControlInput1" required maxlength="20"
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
                                                        <label for="exampleFormControlInput2"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Id
                                                        </label>
                                                        <select wire:model="main_category_id"
                                                            class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-white form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
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
                                                        Save
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

<x-scripts />

<script>
    Livewire.on('deleteData', catId => {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.emitTo('teachers', 'delete', catId)
                Swal.fire(
                    'Deleted!',
                    'Your Data has been deleted.',
                    'success'
                )
            }
        })
    })
</script>
