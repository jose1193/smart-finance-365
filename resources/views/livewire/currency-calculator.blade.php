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
                            <i class="fa-solid fa-circle-dollar-to-slot mr-3"></i>

                            <x-slot name="title">
                                {{ __('messages.currency_exchange_rates') }}
                            </x-slot>
                            <a href="{{ route('currency') }}">
                                <span>{{ __('messages.currency_exchange_rates') }}</span></a>
                        </div>

                    </div>

                    <!-- Tables -->
                    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
                        <div class="w-full overflow-x-auto">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="text-xs font-bold tracking-wide text-left text-gray-600 uppercase border-b dark:border-gray-700 bg-gray-100 dark:text-gray-400 dark:bg-gray-800">

                                        <th class="px-4 py-3">Item</th>
                                        <th class="px-4 py-3">Compra</th>
                                        <th class="px-4 py-3">Venta</th>

                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">

                                    <tr class="text-gray-700  uppercase dark:text-gray-400">

                                        <td class="px-4 py-3 text-xs">
                                            Dolar Oficial
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            $ {{ $data['oficial']['value_buy'] }}
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            $ {{ $data['oficial']['value_sell'] }}

                                        </td>

                                    </tr>

                                    <tr class="text-gray-700  uppercase dark:text-gray-400">

                                        <td class="px-4 py-3 text-xs">
                                            Dolar Blue
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            $
                                            {{ $formatted_amount = number_format($data['blue']['value_buy'], 0, '.', ',') }}

                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            $
                                            {{ $formatted_amount = number_format($data['blue']['value_sell'], 0, '.', ',') }}


                                        </td>

                                    </tr>
                                    <tr class="text-gray-700  uppercase dark:text-gray-400">

                                        <td class="px-4 py-3 text-xs">
                                            Euro Oficial
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            $
                                            {{ $formatted_amount = number_format($data['oficial_euro']['value_buy'], 0, '.', ',') }}

                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            $
                                            {{ $formatted_amount = number_format($data['oficial_euro']['value_sell'], 0, '.', ',') }}

                                        </td>

                                    </tr>
                                    <tr class="text-gray-700  uppercase dark:text-gray-400">

                                        <td class="px-4 py-3 text-xs">
                                            Euro Blue
                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            $
                                            {{ $formatted_amount = number_format($data['blue_euro']['value_buy'], 0, '.', ',') }}


                                        </td>
                                        <td class="px-4 py-3 text-xs">
                                            $
                                            {{ $formatted_amount = number_format($data['blue_euro']['value_sell'], 0, '.', ',') }}

                                        </td>

                                    </tr>

                                    <tr class="text-center">
                                        <td colspan="4">
                                            <div class="grid justify-items-center w-full mt-5">
                                                <div class="text-center bg-indigo-100 rounded-lg  w-full px-6 mb-4 text-base text-indigo-700 dark:text-gray-400 dark:bg-gray-800"
                                                    role="alert">
                                                    <div class="flex items-center justify-center mb-3 mt-5">
                                                        <img src="https://creazilla-store.fra1.digitaloceanspaces.com/emojis/62260/argentina-flag-emoji-clipart-md.png"
                                                            class="w-8 rounded-lg" alt="Avatar" />
                                                        <span class="ml-2">
                                                            {{ ucfirst(\Carbon\Carbon::parse($data['last_update'])->translatedFormat('l j \d\e F, H:i:s')) }}</span>
                                                    </div>

                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                    </div>


                </div>
            </main>


            <!-- END PANEL MAIN CATEGORIES -->

        </div>
    </div>


</div>
