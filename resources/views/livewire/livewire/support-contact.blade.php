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
                                {{ __('Support Contact') }}
                            </x-slot>
                            <a href="{{ route('support-contact') }}">
                                <span>Support Contact</span></a>
                        </div>

                    </div>
                    <!-- SHOW MESSAGE -->
                    @if ($selectedMessage)
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
                                            Details Message
                                        </h5>
                                        <!--Close button-->
                                        <button type="button" wire:click="closeModal()"
                                            class="box-content rounded-none border-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none"
                                            data-te-modal-dismiss aria-label="Close">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
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
                                                        Subject</label>


                                                    <input type="text" autocomplete="off" wire:model="subjectShow"
                                                        readonly
                                                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                        placeholder="Enter Subject">

                                                </div>

                                                <div class="mb-4">
                                                    <label class="block text-gray-700 text-sm font-bold mb-2">
                                                        Message</label>

                                                    <p class="text-base font-medium text-gray-600">
                                                        {!! $selectedMessageShow !!}</p>

                                                </div>


                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                    @endif
                    <!-- END SHOW MESSAGE -->
                    <div class=" my-7 flex justify-between space-x-2">
                        <x-button wire:click="create()"><span class="font-semibold"> New Message <i
                                    class="fa-solid fa-envelope-open-text"></i></span>
                        </x-button>
                        <x-input id="name" type="text" wire:model="search" placeholder="Search..." autofocus
                            autocomplete="off" class="dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" />
                    </div>

                    <div class="flex justify-end mb-5">
                        @if (count($checkedSelected) >= 1)
                            <button wire:click="confirmDelete"
                                class="bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete Multiple ({{ count($checkedSelected) }})
                            </button>
                        @endif
                    </div>
                    <!-- Tables -->
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap ">
                                <thead>
                                    <tr
                                        class="text-xs font-bold tracking-wide text-center text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">
                                        <th class="px-4 py-3">Nro</th>
                                        <th class="px-4 py-3">From</th>
                                        <th class="px-4 py-3">To</th>
                                        <th class="px-4 py-3">Email</th>
                                        <th class="px-4 py-3">Subject</th>
                                        <th class="px-4 py-3">Message</th>
                                        <th class="px-4 py-3 ">Action</th>
                                        @can('manage admin')
                                            <th class="px-4 py-3">
                                                @if (!$data->isEmpty())
                                                    <input type="checkbox" wire:model="selectAll" id="select-all">
                                                @endif
                                            </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                    @forelse($data as $item)
                                        <tr class="text-gray-700 text-xs text-center uppercase dark:text-gray-400">
                                            <td class="px-4 py-3 text-center">

                                                {{ $loop->iteration }}

                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ $item->name_from }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ $item->name }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ $item->email }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ Str::words($item->subject, 2, '...') }}

                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ Str::words($item->subject, 3, '...') }}
                                            </td>

                                            <td class="px-4 py-3 text-sm">
                                                <button wire:click="showMessage({{ $item->id }})"
                                                    class="bg-blue-600 duration-500 ease-in-out hover:bg-blue-700 text-white font-bold py-2 px-4 mr-1  rounded"><i
                                                        class="fa-solid fa-eye"></i></button>

                                                <button wire:click="$emit('deleteData',{{ $item->id }})"
                                                    class="bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white font-bold py-2 px-4 rounded"><i
                                                        class="fa-solid fa-trash"></i></button>

                                            </td>
                                            @can('manage admin')
                                                <td class="px-4 py-3 text-sm">
                                                    <input type="checkbox" wire:model="checkedSelected"
                                                        value="{{ $item->id }}" id="checkbox-{{ $item->id }}">

                                                </td>
                                            @endcan
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

                                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                        role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                        <div
                                            class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                                            <!--Modal title-->
                                            <div class="text-center"></div>
                                            <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                                                id="exampleModalLabel">
                                                Support Contact Form
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
                                                            Name</label>
                                                        @if (auth()->user()->hasRole('Admin'))
                                                            <input type="text" autocomplete="off"
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                maxlength="20" placeholder="Enter Name" readonly
                                                                wire:model="name"
                                                                @if (auth()->user()->hasRole('Admin')) wire:change="updatedEmail($event.target.value)" @endif>
                                                        @else
                                                            <input type="text" autocomplete="off"
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                maxlength="20" placeholder="Enter Name"
                                                                value="{{ auth()->user()->name }}" readonly>
                                                        @endif
                                                        @error('name')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                                            Email</label>

                                                        @if (auth()->user()->hasRole('User'))
                                                            <input type="text" autocomplete="off"
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                maxlength="20" placeholder="Enter Name"
                                                                value="{{ auth()->user()->email }}" readonly>
                                                            <input type="email" autocomplete="off"
                                                                wire:model="email"
                                                                class="hidden shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                                placeholder="Enter Email" readonly>
                                                        @else
                                                            <div wire:ignore>
                                                                <select wire:model="email" id="select2EMailUserId"
                                                                    style="width: 100%">
                                                                    <option value=""></option>

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
                                                        @endif
                                                        <script>
                                                            document.addEventListener('livewire:load', function() {
                                                                Livewire.hook('message.sent', () => {
                                                                    // Vuelve a aplicar Select2 después de cada actualización de Livewire
                                                                    $('#select2EMailUserId').select2({
                                                                        width: 'resolve' // need to override the changed default
                                                                    });
                                                                });
                                                            });

                                                            $(document).ready(function() {
                                                                // Inicializa Select2
                                                                $('#select2EMailUserId').select2();

                                                                // Escucha el cambio en Select2 y actualiza Livewire
                                                                $('#select2EMailUserId').on('change', function(e) {
                                                                    @this.set('email', $(this).val());
                                                                });
                                                            });
                                                        </script>
                                                        @error('email')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>


                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                                            Subject</label>


                                                        <input type="text" autocomplete="off" wire:model="subject"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                            placeholder="Enter Subject">
                                                        @error('subject')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label class="block text-gray-700 text-sm font-bold mb-2">
                                                            Message</label>


                                                        <textarea rows="3" wire:model="message"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                                                        @error('message')
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
                                                        Send
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
                    Livewire.emitTo('support-contact', 'delete',
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
        Livewire.on('showConfirmation', () => {
            Swal.fire({
                title: 'Are you sure you want to delete these items?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('support-contact',
                        'deleteMultiple'); // Envía el Id al método delete
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
