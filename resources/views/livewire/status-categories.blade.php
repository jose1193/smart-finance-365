<div :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- MENU SIDEBAR -->
        <x-menu-sidebar />
        <!-- END MENU SIDEBAR -->
        <div class="flex flex-col flex-1 w-full">

            <!-- HEADER -->
            <x-header-dashboard />
            <!-- END HEADER -->
            <!-- PANEL MAIN  -->
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
                                {{ __('messages.options_categories') }}

                            </x-slot>
                            <a href="{{ route('options-categories') }}">
                                <span>{{ __('messages.options_categories') }}
                                </span></a>
                        </div>

                    </div>

                    <div class=" my-7 flex justify-between space-x-2">
                        <x-button wire:click="create()"><span class="font-semibold">{{ __('messages.create_new') }} <i
                                    class="fa-solid fa-envelope-open-text"></i></span>
                        </x-button>
                        <x-input id="name" type="text" wire:model="search"
                            placeholder="{{ __('messages.inpur_search') }}" autofocus autocomplete="off" />
                    </div>

                    <!-- Tables -->
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                        <th class="px-4 py-3">Nro</th>
                                        <th class="px-4 py-3">{{ __('messages.table_columns_categories.category') }}
                                        </th>
                                        <th class="px-4 py-3">
                                            {{ __('messages.option_description') }}</th>
                                        <th class="px-4 py-3">{{ __('messages.table_columns_categories.date') }}</th>
                                        <th class="px-4 py-3">{{ __('messages.table_columns_categories.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y text-center dark:divide-gray-700 dark:bg-gray-800">
                                    @forelse($data as $item)
                                        <tr class="text-gray-700 text-xs  uppercase dark:text-gray-400" translate="no">
                                            <td class="px-4 py-3 text-center">

                                                {{ $loop->iteration }}

                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ $item->title }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ __('messages.status_options.' . $item->status_description) }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                @if (app()->getLocale() === 'en')
                                                    <span>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('m/d/Y') }}</span>
                                                @elseif(app()->getLocale() === 'pt')
                                                    <span>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d/m/Y') }}</span>
                                                @else
                                                    <span>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</span>
                                                @endif

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
                                            <td colspan="5">
                                                <div class="grid justify-items-center w-full mt-5">
                                                    <div class="text-center bg-red-100 rounded-lg py-5 w-full px-6 mb-4 text-base text-red-700 "
                                                        role="alert">
                                                        {{ __('messages.no_data_records') }}
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

                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                        role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                        <div
                                            class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                                            <!--Modal title-->
                                            <div class="text-center"></div>
                                            <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                                                id="exampleModalLabel">
                                                {{ __('messages.options_categories') }}

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
                                                        <label for=""
                                                            class="block text-gray-700 text-sm font-bold mb-2">
                                                            {{ __('messages.select_main_category') }}
                                                        </label>
                                                        <select wire:model="main_category_id"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                            <option value="">
                                                                {{ __('messages.select_a_category') }}</option>
                                                            @if ($mainCategories)
                                                                @foreach ($mainCategories as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->title }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                        @error('main_category_id')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                                            {{ __('messages.option_description2') }}</label>


                                                        <input type="text" autocomplete="off"
                                                            wire:model="status_description"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            placeholder="Enter Description">
                                                        @error('status_description')
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
                                                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
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


                </div>
            </main>


            <!-- END PANEL MAIN  -->

        </div>
    </div>


</div>






<script>
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('deleteData', function(id) {
            Swal.fire({
                title: "{{ __('messages.delete_confirmation_title2') }}",
                text: "{{ __('messages.delete_confirmation_text') }}",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "{{ __('messages.delete_confirmation_confirm_button') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('status-categories', 'delete',
                        id); // Envía el Id al método delete
                    Swal.fire(
                        '{!! __('messages.delete_success_title2') !!}',
                        '{{ __('messages.delete_confirmation_text_all') }}',
                        'success'
                    );
                }
            });
        });
    });
</script>
