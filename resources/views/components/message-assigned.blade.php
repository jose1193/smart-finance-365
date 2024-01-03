@if (session()->has('assigned'))
    <!-- Toast Container -->
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)"
        x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 bg-green-500 text-white py-2 px-4 rounded shadow flex items-center">
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
            stroke="currentColor" class="w-5 h-5 mr-2 text-white">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
            </path>
        </svg>
        <span>{{ session('assigned') }}</span>
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24"
            stroke="currentColor" class="w-4 h-4 ml-4">
            <path d="M6 18L18 6M6 6l12 12">
            </path>
        </svg>
    </div>
@endif


@if (session()->has('removed'))
    <!-- Toast Container -->
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)"
        x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 bg-red-600 text-white py-2 px-4 rounded shadow flex items-center">
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
            stroke="currentColor" class="w-5 h-5 mr-2 text-white">
            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('removed') }}</span>
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24"
            stroke="currentColor" class="w-4 h-4 ml-4">
            <path d="M6 18L18 6M6 6l12 12">
            </path>
        </svg>
    </div>
@endif


@if (session()->has('noAssignments'))
    <!-- Toast Container -->
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)"
        x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 bg-blue-600 text-white py-2 px-4 rounded shadow flex items-center">
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
            stroke="currentColor" class="w-5 h-5 mr-2 text-white">
            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('noAssignments') }}</span>
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24"
            stroke="currentColor" class="w-4 h-4 ml-4">
            <path d="M6 18L18 6M6 6l12 12">
            </path>
        </svg>
    </div>
@endif

@if (session()->has('exist'))
    <!-- Toast Container -->
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)"
        x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 bg-orange-600 text-white py-2 px-4 rounded shadow flex items-center">
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
            stroke="currentColor" class="w-5 h-5 mr-2 text-white">
            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('exist') }}</span>
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24"
            stroke="currentColor" class="w-4 h-4 ml-4">
            <path d="M6 18L18 6M6 6l12 12">
            </path>
        </svg>
    </div>
@endif

@if (session()->has('assignedSubcategory'))
    <!-- Toast Container -->
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)"
        x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 bg-green-500 text-white py-2 px-4 rounded shadow flex items-center">
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
            stroke="currentColor" class="w-5 h-5 mr-2 text-white">
            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z">
            </path>
        </svg>
        <span>{{ session('assignedSubcategory') }}</span>
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24"
            stroke="currentColor" class="w-4 h-4 ml-4">
            <path d="M6 18L18 6M6 6l12 12">
            </path>
        </svg>
    </div>
@endif


@if (session()->has('removedSubcategory'))
    <!-- Toast Container -->
    <div x-data="{ showMessage: true }" x-show="showMessage" x-init="setTimeout(() => showMessage = false, 7000)"
        x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed bottom-5 right-5 bg-red-600 text-white py-2 px-4 rounded shadow flex items-center">
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
            stroke="currentColor" class="w-5 h-5 mr-2 text-white">
            <path d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>{{ session('removedSubcategory') }}</span>
        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" viewBox="0 0 24 24"
            stroke="currentColor" class="w-4 h-4 ml-4">
            <path d="M6 18L18 6M6 6l12 12">
            </path>
        </svg>
    </div>
@endif
