 <!-- Desktop sidebar -->
 <aside class="z-20 hidden w-64 overflow-y-auto bg-white dark:bg-gray-800 md:block flex-shrink-0">
     <div class="py-4 text-gray-500 dark:text-gray-400">
         <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="{{ route('dashboard') }}">
             <!-- Logo -->


             <x-application-mark class="block h-10 w-auto" />

         </a>
         <h1
             class="text-center text-base font-bold leading-none tracking-tighter text-indigo-500 md:text-base lg:text-sm">
             {{ __('messages.welcome') }},
             {{ Auth::user()->username }} </h1>
         <ul class="mt-6">
             <li class="relative px-6 py-3">
                 <span class="absolute inset-y-0 left-0 w-1 bg-blue-600 rounded-tr-lg rounded-br-lg"
                     aria-hidden="true"></span>
                 <a class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                     href="{{ route('dashboard') }}">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path
                             d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                         </path>
                     </svg>
                     <span class="ml-4"> {{ __('messages.dashboard') }}</span>
                 </a>
             </li>
         </ul>
         <ul>
             <!--<li class="relative px-6 py-3">
                 <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     href="{{ route('categories') }}">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path
                             d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                         </path>
                     </svg>
                     <span class="ml-4">Categories</span>
                 </a>
             </li>-->
             <li class="relative px-6 py-3">
                 <button
                     class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     @click="togglePagesMenu5" aria-haspopup="true">
                     <span class="inline-flex items-center">

                         <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="1em"
                             viewBox="0 0 640 512">
                             <path
                                 d="M326.7 403.7c-22.1 8-45.9 12.3-70.7 12.3s-48.7-4.4-70.7-12.3c-.3-.1-.5-.2-.8-.3c-30-11-56.8-28.7-78.6-51.4C70 314.6 48 263.9 48 208C48 93.1 141.1 0 256 0S464 93.1 464 208c0 55.9-22 106.6-57.9 144c-1 1-2 2.1-3 3.1c-21.4 21.4-47.4 38.1-76.3 48.6zM256 91.9c-11.1 0-20.1 9-20.1 20.1v6c-5.6 1.2-10.9 2.9-15.9 5.1c-15 6.8-27.9 19.4-31.1 37.7c-1.8 10.2-.8 20 3.4 29c4.2 8.8 10.7 15 17.3 19.5c11.6 7.9 26.9 12.5 38.6 16l2.2 .7c13.9 4.2 23.4 7.4 29.3 11.7c2.5 1.8 3.4 3.2 3.7 4c.3 .8 .9 2.6 .2 6.7c-.6 3.5-2.5 6.4-8 8.8c-6.1 2.6-16 3.9-28.8 1.9c-6-1-16.7-4.6-26.2-7.9l0 0 0 0 0 0c-2.2-.7-4.3-1.5-6.4-2.1c-10.5-3.5-21.8 2.2-25.3 12.7s2.2 21.8 12.7 25.3c1.2 .4 2.7 .9 4.4 1.5c7.9 2.7 20.3 6.9 29.8 9.1V304c0 11.1 9 20.1 20.1 20.1s20.1-9 20.1-20.1v-5.5c5.3-1 10.5-2.5 15.4-4.6c15.7-6.7 28.4-19.7 31.6-38.7c1.8-10.4 1-20.3-3-29.4c-3.9-9-10.2-15.6-16.9-20.5c-12.2-8.8-28.3-13.7-40.4-17.4l-.8-.2c-14.2-4.3-23.8-7.3-29.9-11.4c-2.6-1.8-3.4-3-3.6-3.5c-.2-.3-.7-1.6-.1-5c.3-1.9 1.9-5.2 8.2-8.1c6.4-2.9 16.4-4.5 28.6-2.6c4.3 .7 17.9 3.3 21.7 4.3c10.7 2.8 21.6-3.5 24.5-14.2s-3.5-21.6-14.2-24.5c-4.4-1.2-14.4-3.2-21-4.4V112c0-11.1-9-20.1-20.1-20.1zM48 352H64c19.5 25.9 44 47.7 72.2 64H64v32H256 448V416H375.8c28.2-16.3 52.8-38.1 72.2-64h16c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V400c0-26.5 21.5-48 48-48z" />
                         </svg>
                         <span class="ml-4">{{ __('messages.rates_coins') }}</span>
                     </span>
                     <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                         <path fill-rule="evenodd"
                             d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                             clip-rule="evenodd"></path>
                     </svg>
                 </button>
                 <template x-if="isPagesMenuOpen5">
                     <ul x-transition:enter="transition-all ease-in-out duration-300"
                         x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                         x-transition:leave="transition-all ease-in-out duration-300"
                         x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                         class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                         aria-label="submenu">

                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full" href="{{ route('currency') }}">
                                 {{ __('messages.menu_currency_exchange_rates') }}
                             </a>
                         </li>
                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full" href="{{ route('calculator') }}">
                                 {{ __('messages.calculator') }}
                             </a>
                         </li>

                     </ul>
                 </template>
             </li>
             <li class="relative px-6 py-3">
                 <button
                     class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     @click="togglePagesMenu" aria-haspopup="true">
                     <span class="inline-flex items-center">
                         <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                             stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                             <path
                                 d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                             </path>
                         </svg>
                         <span class="ml-4">{{ __('messages.menu_categories') }}</span>
                     </span>
                     <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                         <path fill-rule="evenodd"
                             d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                             clip-rule="evenodd"></path>
                     </svg>
                 </button>
                 <template x-if="isPagesMenuOpen">
                     <ul x-transition:enter="transition-all ease-in-out duration-300"
                         x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                         x-transition:leave="transition-all ease-in-out duration-300"
                         x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                         class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                         aria-label="submenu">

                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full" href="{{ route('income-categories') }}">
                                 {{ __('messages.income') }}
                             </a>
                         </li>
                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full" href="{{ route('expenses-categories') }}">
                                 {{ __('messages.expenses') }}
                             </a>
                         </li>
                         @can('manage admin')
                             <li
                                 class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                 <a class="w-full" href="{{ route('options-categories') }}">
                                     {{ __('messages.options_categories') }}
                                 </a>
                             </li>
                         @endcan
                     </ul>
                 </template>
             </li>

             <li class="relative px-6 py-3">
                 <button
                     class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     @click="togglePagesMenu2" aria-haspopup="true">
                     <span class="inline-flex items-center">
                         <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                             stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                             <path
                                 d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122">
                             </path>
                         </svg>
                         <span class="ml-4">
                             @role('User')
                                 {{ __('messages.management') }}
                             @else
                                 {{ __('messages.management_users') }}
                             @endrole
                         </span>
                     </span>
                     <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                         <path fill-rule="evenodd"
                             d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                             clip-rule="evenodd"></path>
                     </svg>
                 </button>
                 <template x-if="isPagesMenuOpen2">
                     <ul x-transition:enter="transition-all ease-in-out duration-300"
                         x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                         x-transition:leave="transition-all ease-in-out duration-300"
                         x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                         class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                         aria-label="submenu">
                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full" href="{{ route('budgets') }}">
                                 {{ __('messages.budget') }}
                             </a>
                         </li>

                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full"
                                 @role('User')
            href="{{ route('incomes') }}"
        @else
            href="{{ route('incomes-admin') }}"
        @endrole>
                                 {{ __('messages.income') }}
                             </a>
                         </li>


                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full"
                                 @role('User')
            href="{{ route('expense') }}"
        @else
            href="{{ route('expenses-admin') }}"
        @endrole>
                                 {{ __('messages.expenses') }}
                             </a>
                         </li>

                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full" href="{{ route('emails') }}">
                                 {{ __('messages.email_send_reports') }}
                             </a>
                         </li>
                         @can('manage admin')
                             <li
                                 class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                 <a class="w-full" href="{{ route('admin-email-support') }}">
                                     {{ __('messages.email_admin_support') }}
                                 </a>
                             </li>
                         @endcan

                     </ul>
                 </template>
             </li>

             <li class="relative px-6 py-3">
                 <button
                     class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     @click="togglePagesMenu8" aria-haspopup="true">
                     <span class="inline-flex items-center">
                         <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 512 512">
                             <path
                                 d="M152.1 38.2c9.9 8.9 10.7 24 1.8 33.9l-72 80c-4.4 4.9-10.6 7.8-17.2 7.9s-12.9-2.4-17.6-7L7 113C-2.3 103.6-2.3 88.4 7 79s24.6-9.4 33.9 0l22.1 22.1 55.1-61.2c8.9-9.9 24-10.7 33.9-1.8zm0 160c9.9 8.9 10.7 24 1.8 33.9l-72 80c-4.4 4.9-10.6 7.8-17.2 7.9s-12.9-2.4-17.6-7L7 273c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l22.1 22.1 55.1-61.2c8.9-9.9 24-10.7 33.9-1.8zM224 96c0-17.7 14.3-32 32-32H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H256c-17.7 0-32-14.3-32-32zm0 160c0-17.7 14.3-32 32-32H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H256c-17.7 0-32-14.3-32-32zM160 416c0-17.7 14.3-32 32-32H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H192c-17.7 0-32-14.3-32-32zM48 368a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                         </svg>

                         <span class="ml-4">
                             @role('User')
                                 {{ __('messages.task_management') }}
                             @else
                                 {{ __('messages.task_management_users') }}
                             @endrole
                         </span>
                     </span>
                     <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                         <path fill-rule="evenodd"
                             d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                             clip-rule="evenodd"></path>
                     </svg>
                 </button>
                 <template x-if="isPagesMenuOpen8">
                     <ul x-transition:enter="transition-all ease-in-out duration-300"
                         x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                         x-transition:leave="transition-all ease-in-out duration-300"
                         x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                         class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                         aria-label="submenu">


                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full"
                                 @role('User')
            href="{{ route('process-operation-income') }}"
        @else
            href="{{ route('incomes-admin') }}"
        @endrole>
                                 {{ __('messages.task_incomes') }}
                             </a>
                         </li>


                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full"
                                 @role('User')
            href="{{ route('process-operation-expense') }}"
        @else
            href="{{ route('expenses-admin') }}"
        @endrole>
                                 {{ __('messages.task_expenses') }}
                             </a>
                         </li>




                     </ul>
                 </template>
             </li>
             <li class="relative px-6 py-3">
                 <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     href="{{ route('general-report') }}">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path
                             d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                         </path>
                     </svg>
                     <span class="ml-4">{{ __('messages.reports') }}</span>
                 </a>
             </li>

             <li class="relative px-6 py-3">
                 <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     href="{{ route('general-charts') }}">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                         <path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                     </svg>
                     <span class="ml-4">{{ __('messages.charts') }}</span>
                 </a>
             </li>



             <!--<li class="relative px-6 py-3">
                 <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     href="modals.html">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path
                             d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                         </path>
                     </svg>
                     <span class="ml-4">Modals</span>
                 </a>
             </li>-->
             <!--<li class="relative px-6 py-3">
                 <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     href="tables.html">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                     </svg>
                     <span class="ml-4">Tables</span>
                 </a>
             </li>-->
             @can('manage admin')
                 <li class="relative px-6 py-3">
                     <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                         href="{{ route('users') }}">


                         <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="1em"
                             viewBox="0 0 640 512">
                             <path
                                 d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z" />
                         </svg>
                         <span class="ml-4">{{ __('messages.users') }}</span>
                     </a>
                 </li>

                 <li class="relative px-6 py-3">
                     <button
                         class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                         @click="togglePagesMenu6" aria-haspopup="true">
                         <span class="inline-flex items-center">
                             <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                 <path
                                     d="M192 32c0 17.7 14.3 32 32 32c123.7 0 224 100.3 224 224c0 17.7 14.3 32 32 32s32-14.3 32-32C512 128.9 383.1 0 224 0c-17.7 0-32 14.3-32 32zm0 96c0 17.7 14.3 32 32 32c70.7 0 128 57.3 128 128c0 17.7 14.3 32 32 32s32-14.3 32-32c0-106-86-192-192-192c-17.7 0-32 14.3-32 32zM96 144c0-26.5-21.5-48-48-48S0 117.5 0 144V368c0 79.5 64.5 144 144 144s144-64.5 144-144s-64.5-144-144-144H128v96h16c26.5 0 48 21.5 48 48s-21.5 48-48 48s-48-21.5-48-48V144z" />
                             </svg>
                             <span class="ml-4">Blog</span>
                         </span>
                         <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                             <path fill-rule="evenodd"
                                 d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                 clip-rule="evenodd"></path>
                         </svg>
                     </button>
                     <template x-if="isPagesMenuOpen6">
                         <ul x-transition:enter="transition-all ease-in-out duration-300"
                             x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                             x-transition:leave="transition-all ease-in-out duration-300"
                             x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                             class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                             aria-label="submenu">

                             <li
                                 class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                 <a class="w-full" href="{{ route('posts') }}">
                                     Blog
                                 </a>
                             </li>


                         </ul>
                     </template>
                 </li>
             @endcan
         </ul>
         <div class="px-6 my-6">
             <a href="{{ route('support-contact') }}"
                 class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white  bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 duration-500 ease-in-out hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                 {{ __('messages.support_contact') }}
                 <i class="fa-solid fa-envelope-open-text "></i>
             </a>
         </div>
     </div>
 </aside>
 <!-- Mobile sidebar -->
 <!-- Backdrop -->
 <div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"></div>
 <aside class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto bg-white dark:bg-gray-800 md:hidden"
     x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
     x-transition:enter-start="opacity-0 transform -translate-x-20" x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0 transform -translate-x-20" @click.away="closeSideMenu"
     @keydown.escape="closeSideMenu">
     <div class="py-4 text-gray-500 dark:text-gray-400">
         <a class="ml-6 text-lg font-bold text-gray-800 dark:text-gray-200" href="{{ route('dashboard') }}">
             <x-application-mark class="block h-10 w-auto" />
         </a>
         <h1
             class="text-center text-base font-bold leading-none tracking-tighter text-indigo-500 md:text-7xl lg:text-sm">
             Welcome,
             {{ Auth::user()->username }} </h1>
         <ul class="mt-6">
             <li class="relative px-6 py-3">
                 <span class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                     aria-hidden="true"></span>
                 <a class="inline-flex items-center w-full text-sm font-semibold text-gray-800 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200 dark:text-gray-100"
                     href="{{ route('dashboard') }}">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path
                             d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                         </path>
                     </svg>
                     <span class="ml-4">Dashboard</span>
                 </a>
             </li>
         </ul>
         <ul>
             <!-- <li class="relative px-6 py-3">
                 <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     href="{{ route('categories') }}">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path
                             d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                         </path>
                     </svg>
                     <span class="ml-4">Categories</span>
                 </a>
             </li>-->
             <li class="relative px-6 py-3">
                 <button
                     class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     @click="togglePagesMenu5" aria-haspopup="true">
                     <span class="inline-flex items-center">


                         <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="1em"
                             viewBox="0 0 640 512">
                             <path
                                 d="M326.7 403.7c-22.1 8-45.9 12.3-70.7 12.3s-48.7-4.4-70.7-12.3c-.3-.1-.5-.2-.8-.3c-30-11-56.8-28.7-78.6-51.4C70 314.6 48 263.9 48 208C48 93.1 141.1 0 256 0S464 93.1 464 208c0 55.9-22 106.6-57.9 144c-1 1-2 2.1-3 3.1c-21.4 21.4-47.4 38.1-76.3 48.6zM256 91.9c-11.1 0-20.1 9-20.1 20.1v6c-5.6 1.2-10.9 2.9-15.9 5.1c-15 6.8-27.9 19.4-31.1 37.7c-1.8 10.2-.8 20 3.4 29c4.2 8.8 10.7 15 17.3 19.5c11.6 7.9 26.9 12.5 38.6 16l2.2 .7c13.9 4.2 23.4 7.4 29.3 11.7c2.5 1.8 3.4 3.2 3.7 4c.3 .8 .9 2.6 .2 6.7c-.6 3.5-2.5 6.4-8 8.8c-6.1 2.6-16 3.9-28.8 1.9c-6-1-16.7-4.6-26.2-7.9l0 0 0 0 0 0c-2.2-.7-4.3-1.5-6.4-2.1c-10.5-3.5-21.8 2.2-25.3 12.7s2.2 21.8 12.7 25.3c1.2 .4 2.7 .9 4.4 1.5c7.9 2.7 20.3 6.9 29.8 9.1V304c0 11.1 9 20.1 20.1 20.1s20.1-9 20.1-20.1v-5.5c5.3-1 10.5-2.5 15.4-4.6c15.7-6.7 28.4-19.7 31.6-38.7c1.8-10.4 1-20.3-3-29.4c-3.9-9-10.2-15.6-16.9-20.5c-12.2-8.8-28.3-13.7-40.4-17.4l-.8-.2c-14.2-4.3-23.8-7.3-29.9-11.4c-2.6-1.8-3.4-3-3.6-3.5c-.2-.3-.7-1.6-.1-5c.3-1.9 1.9-5.2 8.2-8.1c6.4-2.9 16.4-4.5 28.6-2.6c4.3 .7 17.9 3.3 21.7 4.3c10.7 2.8 21.6-3.5 24.5-14.2s-3.5-21.6-14.2-24.5c-4.4-1.2-14.4-3.2-21-4.4V112c0-11.1-9-20.1-20.1-20.1zM48 352H64c19.5 25.9 44 47.7 72.2 64H64v32H256 448V416H375.8c28.2-16.3 52.8-38.1 72.2-64h16c26.5 0 48 21.5 48 48v64c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V400c0-26.5 21.5-48 48-48z" />
                         </svg>
                         <span class="ml-4">{{ __('messages.rates_coins') }}</span>
                     </span>
                     <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                         <path fill-rule="evenodd"
                             d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                             clip-rule="evenodd"></path>
                     </svg>
                 </button>
                 <template x-if="isPagesMenuOpen5">
                     <ul x-transition:enter="transition-all ease-in-out duration-300"
                         x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                         x-transition:leave="transition-all ease-in-out duration-300"
                         x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                         class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                         aria-label="submenu">
                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full"
                                 href="{{ route('currency') }}">{{ __('messages.currency_exchange_rates') }}</a>
                         </li>
                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full" href="{{ route('calculator') }}">
                                 {{ __('messages.calculator') }}
                             </a>
                         </li>


                     </ul>
                 </template>
             </li>
             <li class="relative px-6 py-3">
                 <button
                     class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     @click="togglePagesMenu" aria-haspopup="true">
                     <span class="inline-flex items-center">
                         <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                             stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                             <path
                                 d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                             </path>
                         </svg>
                         <span class="ml-4">{{ __('messages.menu_categories') }}</span>
                     </span>
                     <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                         <path fill-rule="evenodd"
                             d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                             clip-rule="evenodd"></path>
                     </svg>
                 </button>
                 <template x-if="isPagesMenuOpen">
                     <ul x-transition:enter="transition-all ease-in-out duration-300"
                         x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                         x-transition:leave="transition-all ease-in-out duration-300"
                         x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                         class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                         aria-label="submenu">
                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full"
                                 href="{{ route('income-categories') }}">{{ __('messages.income') }}</a>
                         </li>
                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full" href="{{ route('expenses-categories') }}">
                                 {{ __('messages.expenses') }}
                             </a>
                         </li>
                         @can('manage admin')
                             <li
                                 class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                 <a class="w-full" href="{{ route('options-categories') }}">
                                     {{ __('messages.options_categories') }}
                                 </a>
                             </li>
                         @endcan

                     </ul>
                 </template>
             </li>
             <li class="relative px-6 py-3">
                 <button
                     class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     @click="togglePagesMenu2" aria-haspopup="true">
                     <span class="inline-flex items-center">


                         <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                             stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                             <path
                                 d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122">
                             </path>
                         </svg>
                         <span class="ml-4"> @role('User')
                                 {{ __('messages.management') }}
                             @else
                                 {{ __('messages.management_users') }}
                             @endrole
                         </span>
                     </span>
                     <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                         <path fill-rule="evenodd"
                             d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                             clip-rule="evenodd"></path>
                     </svg>
                 </button>
                 <template x-if="isPagesMenuOpen2">
                     <ul x-transition:enter="transition-all ease-in-out duration-300"
                         x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                         x-transition:leave="transition-all ease-in-out duration-300"
                         x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                         class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                         aria-label="submenu">
                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full" href="{{ route('budgets') }}">
                                 {{ __('messages.budget') }}
                             </a>
                         </li>

                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full"
                                 @role('User')
            href="{{ route('incomes') }}"
        @else
            href="{{ route('incomes-admin') }}"
        @endrole>
                                 {{ __('messages.income') }}
                             </a>
                         </li>


                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full"
                                 @role('User')
            href="{{ route('expense') }}"
        @else
            href="{{ route('expenses-admin') }}"
        @endrole>
                                 {{ __('messages.expenses') }}
                             </a>
                         </li>


                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full" href="{{ route('emails') }}">
                                 {{ __('messages.email_send_reports') }}
                             </a>
                         </li>
                         @can('manage admin')
                             <li
                                 class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                 <a class="w-full" href="{{ route('admin-email-support') }}">
                                     {{ __('messages.email_admin_support') }}
                                 </a>
                             </li>
                         @endcan
                     </ul>
                 </template>
             </li>
             <li class="relative px-6 py-3">
                 <button
                     class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     @click="togglePagesMenu9" aria-haspopup="true">
                     <span class="inline-flex items-center">
                         <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 512 512">
                             <path
                                 d="M152.1 38.2c9.9 8.9 10.7 24 1.8 33.9l-72 80c-4.4 4.9-10.6 7.8-17.2 7.9s-12.9-2.4-17.6-7L7 113C-2.3 103.6-2.3 88.4 7 79s24.6-9.4 33.9 0l22.1 22.1 55.1-61.2c8.9-9.9 24-10.7 33.9-1.8zm0 160c9.9 8.9 10.7 24 1.8 33.9l-72 80c-4.4 4.9-10.6 7.8-17.2 7.9s-12.9-2.4-17.6-7L7 273c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l22.1 22.1 55.1-61.2c8.9-9.9 24-10.7 33.9-1.8zM224 96c0-17.7 14.3-32 32-32H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H256c-17.7 0-32-14.3-32-32zm0 160c0-17.7 14.3-32 32-32H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H256c-17.7 0-32-14.3-32-32zM160 416c0-17.7 14.3-32 32-32H480c17.7 0 32 14.3 32 32s-14.3 32-32 32H192c-17.7 0-32-14.3-32-32zM48 368a48 48 0 1 1 0 96 48 48 0 1 1 0-96z" />
                         </svg>

                         <span class="ml-4">
                             @role('User')
                                 {{ __('messages.task_management') }}
                             @else
                                 {{ __('messages.task_management_users') }}
                             @endrole
                         </span>
                     </span>
                     <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                         <path fill-rule="evenodd"
                             d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                             clip-rule="evenodd"></path>
                     </svg>
                 </button>
                 <template x-if="isPagesMenuOpen9">
                     <ul x-transition:enter="transition-all ease-in-out duration-300"
                         x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                         x-transition:leave="transition-all ease-in-out duration-300"
                         x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                         class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                         aria-label="submenu">


                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full"
                                 @role('User')
            href="{{ route('process-operation-income') }}"
        @else
            href="{{ route('incomes-admin') }}"
        @endrole>
                                 {{ __('messages.task_incomes') }}
                             </a>
                         </li>


                         <li
                             class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                             <a class="w-full"
                                 @role('User')
            href="{{ route('process-operation-expense') }}"
        @else
            href="{{ route('expenses-admin') }}"
        @endrole>
                                 {{ __('messages.task_expenses') }}
                             </a>
                         </li>




                     </ul>
                 </template>
             </li>
             <li class="relative px-6 py-3">
                 <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     href="{{ route('general-report') }}">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path
                             d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                         </path>
                     </svg>
                     <span class="ml-4">{{ __('messages.reports') }}</span>
                 </a>
             </li>


             <li class="relative px-6 py-3">
                 <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     href="{{ route('general-charts') }}">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                         <path d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                     </svg>
                     <span class="ml-4">{{ __('messages.charts') }}</span>
                 </a>
             </li>


             <!--<li class="relative px-6 py-3">
                 <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     href="modals.html">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path
                             d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                         </path>
                     </svg>
                     <span class="ml-4">Modals</span>
                 </a>
             </li>-->
             <!--<li class="relative px-6 py-3">
                 <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                     href="tables.html">
                     <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round"
                         stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                         <path d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                     </svg>
                     <span class="ml-4">Tables</span>
                 </a>
             </li>-->
             @can('manage admin')
                 <li class="relative px-6 py-3">
                     <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                         href="{{ route('users') }}">
                         <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="1em"
                             viewBox="0 0 640 512">
                             <path
                                 d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z" />
                         </svg>
                         <span class="ml-4">{{ __('messages.users') }}</span>
                     </a>
                 </li>

                 <li class="relative px-6 py-3">
                     <button
                         class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200"
                         @click="togglePagesMenu7" aria-haspopup="true">
                         <span class="inline-flex items-center">
                             <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                 <path
                                     d="M192 32c0 17.7 14.3 32 32 32c123.7 0 224 100.3 224 224c0 17.7 14.3 32 32 32s32-14.3 32-32C512 128.9 383.1 0 224 0c-17.7 0-32 14.3-32 32zm0 96c0 17.7 14.3 32 32 32c70.7 0 128 57.3 128 128c0 17.7 14.3 32 32 32s32-14.3 32-32c0-106-86-192-192-192c-17.7 0-32 14.3-32 32zM96 144c0-26.5-21.5-48-48-48S0 117.5 0 144V368c0 79.5 64.5 144 144 144s144-64.5 144-144s-64.5-144-144-144H128v96h16c26.5 0 48 21.5 48 48s-21.5 48-48 48s-48-21.5-48-48V144z" />
                             </svg>
                             <span class="ml-4">Blog</span>
                         </span>
                         <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                             <path fill-rule="evenodd"
                                 d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                 clip-rule="evenodd"></path>
                         </svg>
                     </button>
                     <template x-if="isPagesMenuOpen7">
                         <ul x-transition:enter="transition-all ease-in-out duration-300"
                             x-transition:enter-start="opacity-25 max-h-0" x-transition:enter-end="opacity-100 max-h-xl"
                             x-transition:leave="transition-all ease-in-out duration-300"
                             x-transition:leave-start="opacity-100 max-h-xl" x-transition:leave-end="opacity-0 max-h-0"
                             class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium text-gray-500 rounded-md shadow-inner bg-gray-50 dark:text-gray-400 dark:bg-gray-900"
                             aria-label="submenu">

                             <li
                                 class="px-2 py-1 transition-colors duration-150 hover:text-gray-800 dark:hover:text-gray-200">
                                 <a class="w-full" href="{{ route('posts') }}">
                                     Blog
                                 </a>
                             </li>


                         </ul>
                     </template>
                 </li>
             @endcan
         </ul>
         <div class="px-6 my-6">
             <a href="{{ route('support-contact') }}"
                 class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white  bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 duration-500 ease-in-out hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                 {{ __('messages.support_contact') }}
                 <i class="fa-solid fa-envelope-open-text "></i>
             </a>
         </div>
     </div>
 </aside>
