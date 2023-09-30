<div :class="{ 'theme-dark': dark }" x-data="data()" lang="en">

    <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
        <!-- MENU SIDEBAR -->
        <x-menu-sidebar />
        <!-- END MENU SIDEBAR -->
        <div class="flex flex-col flex-1 w-full">

            <!-- HEADER -->
            <x-header-dashboard />
            <!-- END HEADER -->

            <!-- PANEL DASHBOARD WELCOME -->
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
                                {{ __('Dashboard') }}
                            </x-slot>
                            <a href="{{ route('dashboard') }}">
                                <span>Dashboard</span></a>
                        </div>

                    </div>
                    <!-- Cards -->
                    <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">

                        <!-- Card -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                            <div
                                class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Account balance
                                </p>
                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                    $ 46,760.89
                                </p>
                            </div>
                        </div>
                        <!-- Card -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                            <div
                                class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                    New sales
                                </p>
                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                    376
                                </p>
                            </div>
                        </div>
                        <!-- Card -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                            <div
                                class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
                                <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                    height="1em"
                                    viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path
                                        d="M64 0C46.3 0 32 14.3 32 32V96c0 17.7 14.3 32 32 32h80v32H87c-31.6 0-58.5 23.1-63.3 54.4L1.1 364.1C.4 368.8 0 373.6 0 378.4V448c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V378.4c0-4.8-.4-9.6-1.1-14.4L488.2 214.4C483.5 183.1 456.6 160 425 160H208V128h80c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H64zM96 48H256c8.8 0 16 7.2 16 16s-7.2 16-16 16H96c-8.8 0-16-7.2-16-16s7.2-16 16-16zM64 432c0-8.8 7.2-16 16-16H432c8.8 0 16 7.2 16 16s-7.2 16-16 16H80c-8.8 0-16-7.2-16-16zm48-168a24 24 0 1 1 0-48 24 24 0 1 1 0 48zm120-24a24 24 0 1 1 -48 0 24 24 0 1 1 48 0zM160 344a24 24 0 1 1 0-48 24 24 0 1 1 0 48zM328 240a24 24 0 1 1 -48 0 24 24 0 1 1 48 0zM256 344a24 24 0 1 1 0-48 24 24 0 1 1 0 48zM424 240a24 24 0 1 1 -48 0 24 24 0 1 1 48 0zM352 344a24 24 0 1 1 0-48 24 24 0 1 1 0 48z" />
                                </svg>


                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Unpaid
                                </p>
                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                    $ 15,145.18
                                </p>
                            </div>
                        </div>
                        <!-- Card -->
                        <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                            <div
                                class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500">
                                <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 576 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path
                                        d="M0 112.5V422.3c0 18 10.1 35 27 41.3c87 32.5 174 10.3 261-11.9c79.8-20.3 159.6-40.7 239.3-18.9c23 6.3 48.7-9.5 48.7-33.4V89.7c0-18-10.1-35-27-41.3C462 15.9 375 38.1 288 60.3C208.2 80.6 128.4 100.9 48.7 79.1C25.6 72.8 0 88.6 0 112.5zM288 352c-44.2 0-80-43-80-96s35.8-96 80-96s80 43 80 96s-35.8 96-80 96zM64 352c35.3 0 64 28.7 64 64H64V352zm64-208c0 35.3-28.7 64-64 64V144h64zM512 304v64H448c0-35.3 28.7-64 64-64zM448 96h64v64c-35.3 0-64-28.7-64-64z" />
                                </svg>
                            </div>
                            <div>
                                <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                                    Paid
                                </p>
                                <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                                    $ 35,680.13
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- New Table -->
                    <div class="w-full overflow-hidden rounded-lg shadow-xs">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="text-xs font-semibold tracking-wide text-left text-gray-500 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                                        <th class="px-4 py-3">Description</th>
                                        <th class="px-4 py-3">Amount</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center text-sm">
                                                <!-- Avatar with inset shadow -->
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                    <img class="object-cover w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                        alt="" loading="lazy" />
                                                    <div class="absolute inset-0 rounded-full shadow-inner"
                                                        aria-hidden="true">
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-semibold">Horacio</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        Herramientas para PYMES
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            $ 863.45
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span
                                                class="px-2 py-1 capitalize font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                Paid
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            6/10/2024
                                        </td>
                                    </tr>

                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center text-sm">
                                                <!-- Avatar with inset shadow -->
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                    <img class="object-cover w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&facepad=3&fit=facearea&s=707b9c33066bf8808c934c8ab394dff6"
                                                        alt="" loading="lazy" />
                                                    <div class="absolute inset-0 rounded-full shadow-inner"
                                                        aria-hidden="true">
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-semibold">Paula Martinez</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        Clases Personalizadas
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            $ 369.95
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-orange-700 bg-orange-100 rounded-full dark:text-white dark:bg-orange-600">
                                                Unpaid
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            6/10/2024
                                        </td>
                                    </tr>

                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center text-sm">
                                                <!-- Avatar with inset shadow -->
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                    <img class="object-cover w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/photo-1551069613-1904dbdcda11?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                        alt="" loading="lazy" />
                                                    <div class="absolute inset-0 rounded-full shadow-inner"
                                                        aria-hidden="true">
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-semibold">Sarah Curry</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        Designer
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            $ 86.00
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:text-red-100 dark:bg-red-700">
                                                Pending
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            6/10/2020
                                        </td>
                                    </tr>

                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center text-sm">
                                                <!-- Avatar with inset shadow -->
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                    <img class="object-cover w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/photo-1551006917-3b4c078c47c9?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                        alt="" loading="lazy" />
                                                    <div class="absolute inset-0 rounded-full shadow-inner"
                                                        aria-hidden="true">
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-semibold">Maria Cristina</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        Postulacion Para ATA
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            $ 1276.45
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                Paid
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            6/10/2024
                                        </td>
                                    </tr>

                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center text-sm">
                                                <!-- Avatar with inset shadow -->
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                    <img class="object-cover w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/photo-1546456073-6712f79251bb?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                        alt="" loading="lazy" />
                                                    <div class="absolute inset-0 rounded-full shadow-inner"
                                                        aria-hidden="true">
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-semibold">Wenzel Dashington</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        Actor
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            $ 863.45
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:text-gray-100 dark:bg-gray-700">
                                                Unpaid
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            6/10/2020
                                        </td>
                                    </tr>

                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center text-sm">
                                                <!-- Avatar with inset shadow -->
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                    <img class="object-cover w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/photo-1502720705749-871143f0e671?ixlib=rb-0.3.5&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&s=b8377ca9f985d80264279f277f3a67f5"
                                                        alt="" loading="lazy" />
                                                    <div class="absolute inset-0 rounded-full shadow-inner"
                                                        aria-hidden="true">
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-semibold">Dave Li</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        Influencer
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            $ 863.45
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                Paid
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            6/10/2020
                                        </td>
                                    </tr>

                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center text-sm">
                                                <!-- Avatar with inset shadow -->
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                    <img class="object-cover w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                        alt="" loading="lazy" />
                                                    <div class="absolute inset-0 rounded-full shadow-inner"
                                                        aria-hidden="true">
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-semibold">Maria Ramovic</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        Runner
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            $ 863.45
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                Paid
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            6/10/2020
                                        </td>
                                    </tr>

                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center text-sm">
                                                <!-- Avatar with inset shadow -->
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                    <img class="object-cover w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/photo-1566411520896-01e7ca4726af?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                        alt="" loading="lazy" />
                                                    <div class="absolute inset-0 rounded-full shadow-inner"
                                                        aria-hidden="true">
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-semibold">Hitney Wouston</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        Singer
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            $ 863.45
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                Paid
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            6/10/2020
                                        </td>
                                    </tr>

                                    <tr class="text-gray-700 dark:text-gray-400">
                                        <td class="px-4 py-3">
                                            <div class="flex items-center text-sm">
                                                <!-- Avatar with inset shadow -->
                                                <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
                                                    <img class="object-cover w-full h-full rounded-full"
                                                        src="https://images.unsplash.com/flagged/photo-1570612861542-284f4c12e75f?ixlib=rb-1.2.1&q=80&fm=jpg&crop=entropy&cs=tinysrgb&w=200&fit=max&ixid=eyJhcHBfaWQiOjE3Nzg0fQ"
                                                        alt="" loading="lazy" />
                                                    <div class="absolute inset-0 rounded-full shadow-inner"
                                                        aria-hidden="true">
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="font-semibold">Hans Burger</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">
                                                        10x Developer
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            $ 863.45
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                                Paid
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-sm">
                                            6/10/2020
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div
                            class="grid px-4 py-3 text-xs font-semibold tracking-wide text-gray-500 uppercase border-t dark:border-gray-700 bg-gray-50 sm:grid-cols-9 dark:text-gray-400 dark:bg-gray-800">
                            <span class="flex items-center col-span-3">
                                Showing 21-30 of 100
                            </span>
                            <span class="col-span-2"></span>
                            <!-- Pagination -->
                            <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                                <nav aria-label="Table navigation">
                                    <ul class="inline-flex items-center">
                                        <li>
                                            <button
                                                class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple"
                                                aria-label="Previous">
                                                <svg aria-hidden="true" class="w-4 h-4 fill-current"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                        clip-rule="evenodd" fill-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                                1
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                                2
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                class="px-3 py-1 text-white transition-colors duration-150 bg-purple-600 border border-r-0 border-purple-600 rounded-md focus:outline-none focus:shadow-outline-purple">
                                                3
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                                4
                                            </button>
                                        </li>
                                        <li>
                                            <span class="px-3 py-1">...</span>
                                        </li>
                                        <li>
                                            <button
                                                class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                                8
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple">
                                                9
                                            </button>
                                        </li>
                                        <li>
                                            <button
                                                class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-purple"
                                                aria-label="Next">
                                                <svg class="w-4 h-4 fill-current" aria-hidden="true"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                        clip-rule="evenodd" fill-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </li>
                                    </ul>
                                </nav>
                            </span>
                        </div>
                    </div>

                    <!-- Charts -->
                    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
                        Charts
                    </h2>
                    <div class="grid gap-6 mb-8 md:grid-cols-2">
                        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                            <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                                Bars
                            </h4>
                            <canvas id="bars"></canvas>
                            <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                <!-- Chart legend -->
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 mr-1 bg-teal-500 rounded-full"></span>
                                    <span>Income</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                    <span>Expense</span>
                                </div>
                            </div>
                        </div>

                        <div class="min-w-0 p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
                            <h4 class="mb-4 font-semibold text-gray-800 dark:text-gray-300">
                                Lines
                            </h4>
                            <canvas id="line"></canvas>
                            <div class="flex justify-center mt-4 space-x-3 text-sm text-gray-600 dark:text-gray-400">
                                <!-- Chart legend -->
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 mr-1 bg-teal-600 rounded-full"></span>
                                    <span>Income</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="inline-block w-3 h-3 mr-1 bg-purple-600 rounded-full"></span>
                                    <span>Expenses</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!-- END PANEL DASHBOARD WELCOME -->

        </div>
    </div>


</div>
