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
                            <i class="fa-solid fa-blog mr-3"></i>

                            <x-slot name="title">
                                {{ __('messages.posts_data') }}

                            </x-slot>
                            <a href="{{ route('posts') }}">
                                <span>{{ __('messages.posts_data') }}
                                </span></a>
                        </div>

                    </div>
                    @can('manage admin')
                        <div class=" my-7 flex justify-between space-x-2">
                            <x-button wire:click="create()">+ {{ __('messages.create_new') }} </x-button>
                            <x-input id="name" type="text" wire:model="search"
                                placeholder="{{ __('messages.inpur_search') }}" autofocus autocomplete="off"
                                class="dark:border-gray-600 dark:bg-gray-700 focus:border-blue-400 focus:outline-none focus:shadow-outline-blue dark:text-gray-300 dark:focus:shadow-outline-blue " />
                        </div>
                    @endcan


                    <div class="mb-3">
                        <label for="perPage"
                            class="text-gray-800 dark:text-gray-300 mr-1 ">{{ __('messages.show') }}</label>
                        <select wire:model="perPage" id="perPage"
                            class="bg-white p-2 dark:border-gray-700  dark:text-gray-300 dark:bg-gray-800">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label for="perPage"
                            class="text-gray-800 dark:text-gray-300 ml-1 ">{{ __('messages.entries') }}</label>
                    </div>

                    <div class="flex justify-end mb-5">
                        @if (count($checkedSelected) >= 1)
                            <button wire:click="confirmDelete"
                                class="bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('messages.delete_multiple') }} ({{ count($checkedSelected) }})
                            </button>
                        @endif
                    </div>
                    <!-- Tables -->
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="text-xs font-semibold tracking-wide text-center text-white uppercase border-b dark:border-gray-700 bg-blue-600 dark:text-gray-400 dark:bg-gray-800">
                                        <th class="px-4 py-3" wire:click="sortBy('posts.id')">Nro
                                            @if ($sortBy === 'posts.id')
                                                @if ($sortDirection === 'asc')
                                                    <i class="fa-solid fa-arrow-up"></i>
                                                @else
                                                    <i class="fa-solid fa-arrow-down"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-4 py-2">{{ __('messages.category') }}</th>
                                        <th class="px-4 py-2">{{ __('messages.image') }}</th>
                                        <th class="px-4 py-2">{{ __('messages.title') }}</th>
                                        <th class="px-4 py-2">{{ __('messages.content') }}</th>
                                        <th class="px-4 py-3" wire:click="sortBy('posts.id')">{{ __('messages.date') }}
                                            @if ($sortBy === 'posts.id')
                                                @if ($sortDirection === 'asc')
                                                    <i class="fa-solid fa-arrow-up"></i>
                                                @else
                                                    <i class="fa-solid fa-arrow-down"></i>
                                                @endif
                                            @endif
                                        </th>
                                        <th class="px-4 py-2">{{ __('messages.status') }}</th>
                                        @can('manage admin')
                                            <th class="px-4 py-3">{{ __('messages.action') }}</th>
                                            <th class="px-4 py-3">
                                                @if (!$posts->isEmpty())
                                                    <input type="checkbox" wire:model="selectAll" id="select-all">
                                                @endif
                                            </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800 text-center">
                                    @forelse($posts as $post)
                                        <tr class="text-gray-700  uppercase dark:text-gray-400" translate="no">
                                            <td class="px-4 py-3 text-center">

                                                {{ $loop->iteration }}

                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                {{ $post->category->first()->blog_category_name }}


                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                @if ($post->post_image)
                                                    @if (strpos($post->post_image, 'videos') !== false)
                                                        {{-- Si es un video, mostrar el reproductor de video --}}
                                                        <video class="w-32 h-32  object-cover rounded border" controls>
                                                            <source src="{{ Storage::url($post->post_image) }}"
                                                                type="video/mp4">
                                                            Tu navegador no soporta el elemento de video.
                                                        </video>
                                                    @else
                                                        {{-- Si es una imagen, mostrar la imagen --}}
                                                        <img class="w-24 h-24  object-cover rounded border"
                                                            src="{{ Storage::url($post->post_image) }}" />
                                                    @endif
                                                @endif
                                            </td>

                                            <td class="px-4 py-3 text-xs">
                                                {{ Str::words($post->post_title, 6, '...') }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ Str::words($post->post_content, 6, '...') }}
                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ \Carbon\Carbon::createFromFormat('l, F jS Y, H:i A', $post->post_date)->format('d/m/Y') }}

                                            </td>
                                            <td class="px-4 py-3 text-xs">
                                                {{ $post->post_status }}

                                            </td>

                                            @can('manage admin')
                                                <td class="px-4 py-3 text-sm">
                                                    <a href="{{ route('posts.show', ['postId' => $post->post_title_slug]) }}"
                                                        class="bg-purple-600 transition duration-500 ease-in-out hover:bg-purple-700 text-white font-bold inline-flex items-center p-3 px-4 py-2.5 mr-0.5  rounded text-base">
                                                        <i class="fa-solid fa-eye "></i>
                                                    </a>



                                                    <button wire:click="edit({{ $post->id }})"
                                                        class="bg-blue-600 duration-500 ease-in-out hover:bg-blue-700 text-white font-bold p-3 py-2 px-4 rounded"><i
                                                            class="fa-solid fa-pen-to-square"></i></button>
                                                    <button
                                                        wire:click="$emit('deleteData', {{ $post->id }}, '{{ $post->post_title }}')"
                                                        class="bg-red-600 duration-500 ease-in-out hover:bg-red-700 text-white font-bold py-2 px-4 rounded"><i
                                                            class="fa-solid fa-trash"></i></button>

                                                <td class="px-4 py-3 text-sm">
                                                    <input type="checkbox" wire:model="checkedSelected"
                                                        value="{{ $post->id }}" id="checkbox-{{ $post->id }}">

                                                </td>
                                                </td>
                                            @endcan
                                        </tr>

                                    @empty
                                        <tr class="text-center">
                                            <td colspan="9">
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
                            <div class="m-2 p-2">{{ $posts->links() }}</div>
                        </div>
                        <!-- MODAL -->
                        @if ($isModalOpen)
                            <div>

                                <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400">
                                    <div
                                        class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div class="fixed inset-0 transition-opacity">
                                            <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
                                        </div>
                                        <!-- This element is to trick the browser into centering the modal contents. -->
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>

                                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle w-full max-w-2xl sm:w-full"
                                            role="dialog" aria-modal="true" aria-labelledby="modal-headline">

                                            <div
                                                class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-2 border-neutral-100 border-opacity-100 p-4 dark:border-opacity-50">
                                                <!--Modal title-->
                                                <div class="text-center"></div>
                                                <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200"
                                                    id="exampleModalLabel">
                                                    {{ __('messages.posts_data') }}
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
                                            <form enctype="multipart/form-data" wire:submit.prevent="store"
                                                autocomplete="off">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">

                                                    <div class="mb-4">
                                                        <label for="post_image"
                                                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.image') }}:</label>

                                                        <div wire:ignore>


                                                            <div
                                                                class=" mx-auto bg-white rounded-lg shadow-md overflow-hidden items-center">
                                                                <div class="px-4 py-6">
                                                                    <!-- Preload the image if available -->
                                                                    @if ($oldImage)

                                                                        <div class="mb-5">
                                                                            {{-- Esto centra el contenido horizontal y verticalmente --}}
                                                                            @if (strpos($oldImage, '.jpg') !== false || strpos($oldImage, '.jpeg') !== false || strpos($oldImage, '.png') !== false)
                                                                                {{-- If it's an image, show the image preview --}}
                                                                                <div class="text-center">
                                                                                    {{-- Agrega la clase text-center para centrar el contenido --}}
                                                                                    <label
                                                                                        class="text-red-600 font-bold">
                                                                                        {{ __('messages.current_image') }}</label>
                                                                                </div>
                                                                                <div
                                                                                    class="flex justify-center items-center">
                                                                                    {{-- Añade las clases para centrar --}}
                                                                                    <img src="{{ asset('storage/' . $oldImage) }}"
                                                                                        class="w-32 h-32 object-cover rounded-lg shadow-xl dark:shadow-gray-800"
                                                                                        alt="Image Preview">
                                                                                </div>
                                                                            @elseif (strpos($oldImage, '.mp4') !== false)
                                                                                {{-- If it's a video, show a video icon and add a link to view the video --}}
                                                                                <div>
                                                                                    <span class="text-gray-500">Video
                                                                                        Preview:</span>
                                                                                    <i class="fas fa-video mx-2"></i>
                                                                                    <a href="{{ asset('storage/' . $oldImage) }}"
                                                                                        target="_blank"
                                                                                        rel="noopener noreferrer">View
                                                                                        Video</a>
                                                                                </div>
                                                                            @endif
                                                                        </div>

                                                                    @endif



                                                                    <div id="image-preview"
                                                                        class="max-w-sm p-6 mb-4 bg-gray-100 border-dashed border-2 border-gray-400 rounded-lg items-center mx-auto text-center cursor-pointer">
                                                                        <input id="upload" type="file"
                                                                            wire:model="newImage" class="hidden"
                                                                            accept="image/*" />
                                                                        <label for="upload" class="cursor-pointer">
                                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                                fill="none" viewBox="0 0 24 24"
                                                                                stroke-width="1.5"
                                                                                stroke="currentColor"
                                                                                class="w-8 h-8 text-gray-700 mx-auto mb-4">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                                                            </svg>
                                                                            <h5
                                                                                class="mb-2 text-xl font-bold tracking-tight text-gray-700">
                                                                                Upload picture</h5>
                                                                            <p
                                                                                class="font-normal text-sm text-gray-400 md:px-6">
                                                                                Choose photo size should be less than <b
                                                                                    class="text-gray-600">2mb</b></p>
                                                                            <p
                                                                                class="font-normal text-sm text-gray-400 md:px-6">
                                                                                and should be in <b
                                                                                    class="text-gray-600">JPG, PNG, or
                                                                                    GIF</b> format.</p>
                                                                            <span id="filename"
                                                                                class="text-gray-500 bg-gray-200 z-50"></span>
                                                                        </label>
                                                                    </div>

                                                                </div>
                                                            </div>


                                                            <script>
                                                                const uploadInput = document.getElementById('upload');
                                                                const filenameLabel = document.getElementById('filename');
                                                                const imagePreview = document.getElementById('image-preview');

                                                                // Check if the event listener has been added before
                                                                let isEventListenerAdded = false;

                                                                uploadInput.addEventListener('change', (event) => {
                                                                    const file = event.target.files[0];

                                                                    if (file) {
                                                                        filenameLabel.textContent = file.name;

                                                                        const reader = new FileReader();
                                                                        reader.onload = (e) => {
                                                                            imagePreview.innerHTML =
                                                                                `<img src="${e.target.result}" class="max-h-48 rounded-lg mx-auto" alt="Image preview" />`;
                                                                            imagePreview.classList.remove('border-dashed', 'border-2', 'border-gray-400');

                                                                            // Add event listener for image preview only once
                                                                            if (!isEventListenerAdded) {
                                                                                imagePreview.addEventListener('click', () => {
                                                                                    uploadInput.click();
                                                                                });

                                                                                isEventListenerAdded = true;
                                                                            }
                                                                        };
                                                                        reader.readAsDataURL(file);
                                                                    } else {
                                                                        filenameLabel.textContent = '';
                                                                        imagePreview.innerHTML =
                                                                            `<div class="bg-gray-200 h-48 rounded-lg flex items-center justify-center text-gray-500">No image preview</div>`;
                                                                        imagePreview.classList.add('border-dashed', 'border-2', 'border-gray-400');

                                                                        // Remove the event listener when there's no image
                                                                        imagePreview.removeEventListener('click', () => {
                                                                            uploadInput.click();
                                                                        });

                                                                        isEventListenerAdded = false;
                                                                    }
                                                                });

                                                                uploadInput.addEventListener('click', (event) => {
                                                                    event.stopPropagation();
                                                                });
                                                            </script>


                                                        </div>
                                                        @error('newImage')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-4">
                                                        <label for="post_title"
                                                            class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.title') }}:</label>
                                                        <input type="text" wire:model="post_title"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('post_title')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-4">
                                                        <div wire:ignore>
                                                            <label for="post_content"
                                                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.content') }}:</label>
                                                            <textarea wire:model="post_content"
                                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                                                            @error('post_content')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>



                                                    <div class="mb-4 lg:flex lg:justify-between">
                                                        <div class="lg:w-1/2 lg:mr-4">
                                                            <label for="category_id"
                                                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.category') }}:</label>
                                                            <select data-te-select-init id="category_id"
                                                                wire:model="category_id" name="category_id"
                                                                class="text-sm shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                                <option value=""></option>
                                                                @foreach ($categories as $category)
                                                                    <option value="{{ $category->id }}">
                                                                        {{ $category->blog_category_name }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('category_id')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                        <div class="mt-4 lg:mt-0 lg:w-1/2">
                                                            <label for="post_status"
                                                                class="block text-gray-700 text-sm font-bold mb-2">{{ __('messages.status') }}:</label>
                                                            <select data-te-select-init id="post_status"
                                                                wire:model="post_status"
                                                                class="text-sm shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                                <option value=""></option>
                                                                <option value="ACTIVE">ACTIVE</option>
                                                                <option value="INACTIVE">INACTIVE</option>
                                                            </select>
                                                            @error('post_status')
                                                                <span class="text-red-500">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>



                                                    <div class="mb-4">
                                                        <label for="meta_title"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Meta
                                                            Title:</label>
                                                        <input type="text" wire:model="meta_title"
                                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                                        @error('meta_title')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-4">
                                                        <label for="meta_description"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Meta
                                                            Description:</label>
                                                        <textarea rows="3" wire:model.lazy="meta_description"
                                                            class="shadow-sm focus:ring-indigo-500 appearance-none bg-white border
                                     py-2 px-3 text-base leading-normal transition duration-150 ease-in-out focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                                        @error('meta_description')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>


                                                    <div class="mb-4">
                                                        <label for="meta_title"
                                                            class="block text-gray-700 text-sm font-bold mb-2">Meta
                                                            Keywords:</label>
                                                        <textarea rows="3" wire:model.lazy="meta_keywords"
                                                            class="shadow-sm focus:ring-indigo-500 appearance-none bg-white border
                                     py-2 px-3 text-base leading-normal transition duration-150 ease-in-out focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                                        @error('meta_keywords')
                                                            <span class="text-red-500">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                    <!-- Agregar más campos si es necesario -->
                                                </div>
                                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                    <button type="submit" wire:loading.attr="disabled"
                                                        wire:target="store"
                                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                        {{ __('messages.button_register') }}
                                                    </button>
                                                    <button type="button" wire:click="closeModal"
                                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                        {{ __('messages.button_cancel') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
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
        Livewire.on('deleteData', function(id, post_title) {
            Swal.fire({
                title: 'Are you sure you want to delete ' +
                    '<span style="color:#9333ea">' + post_title + '</span>' + '?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.emitTo('post-crud', 'delete',
                        id); // Envía el Id al método delete
                    Swal.fire(
                        'Deleted!',
                        'Your Data ' + post_title + ' has been deleted.',
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
                    Livewire.emitTo('post-crud',
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

<script src="https://cdn.tiny.cloud/1/ledg98ovyfojczv2t6zjn48qwwczcqqth3g8ofwis9tuxh5t/tinymce/5/tinymce.min.js"
    referrerpolicy="origin"></script>

<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('modalOpenedTextarea', function() {
            tinymce.init({
                selector: 'textarea',
                plugins: '', // Deshabilitamos todos los plugins
                toolbar: true, // Deshabilitamos la barra de herramientas
                menubar: false, // Deshabilitamos el menú de formato
                branding: false, // Deshabilitamos el branding de TinyMCE
                height: 200, // Altura del área de texto
                resize: false // Deshabilitamos la opción de redimensionar
            });
        });
    });
</script>
