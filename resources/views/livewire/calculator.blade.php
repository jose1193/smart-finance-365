 <x-app-layout>
     <div :class="{ 'theme-dark': dark }" x-data="data()" lang="en">



         <div class="flex h-screen bg-gray-50 dark:bg-gray-900" :class="{ 'overflow-hidden': isSideMenuOpen }">
             <!-- MENU SIDEBAR -->
             <x-menu-sidebar />
             <!-- END MENU SIDEBAR -->
             <div class="flex flex-col flex-1 w-full">

                 <!-- HEADER -->
                 <x-header-dashboard />
                 <!-- END HEADER -->

                 <!-- PANEL MAIN CALCULATOR -->
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
                                     {{ __('Currency Calculator') }}
                                 </x-slot>
                                 <a href="{{ route('calculator') }}">
                                     <span>Currency Exchange Calculator </span></a>
                             </div>

                         </div>
                         <!-- CURRENCY CALCULATOR -->
                         <div class="mb-5">

                             <div class="mb-4 border-b border-gray-200 dark:border-gray-700">

                                 <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab"
                                     data-tabs-toggle="#myTabContent" role="tablist">
                                     <li class="mr-2" role="presentation">
                                         <button class="inline-block p-4 border-b-2 rounded-t-lg" id="item1-tab"
                                             data-tabs-target="#item1" type="button" role="tab"
                                             aria-controls="item1" aria-selected="false">Dolar Blue</button>
                                     </li>
                                     <li class="mr-2" role="presentation">
                                         <button
                                             class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                             id="item2-tab" data-tabs-target="#item2" type="button" role="tab"
                                             aria-controls="item2" aria-selected="false">Dolar
                                             Oficial</button>
                                     </li>
                                     <li class="mr-2" role="presentation">
                                         <button
                                             class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                             id="item3-tab" data-tabs-target="#item3" type="button" role="tab"
                                             aria-controls="item3" aria-selected="false">Euro
                                             Oficial</button>
                                     </li>
                                     <li role="presentation">
                                         <button
                                             class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                             id="item4-tab" data-tabs-target="#item4" type="button" role="tab"
                                             aria-controls="item4" aria-selected="false">Euro
                                             Blue</button>
                                     </li>
                                 </ul>
                             </div>
                             <div id="myTabContent">
                                 <h5
                                     class="text-base font-bold  text-left text-gray-600 capitalize my-3  dark:text-gray-400 ">
                                     ARS To Currency
                                 </h5>

                                 <div class="hidden py-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="item1"
                                     role="tabpanel" aria-labelledby="profile-tab">

                                     <!--   dollar blue Calculator-->
                                     <div class="flex flex-col md:flex-row lg:space-x-4">
                                         <input id="amount2" autocomplete="off"
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             placeholder="Amount (ARS)" name="amount2" maxlength="30" required />
                                         <input
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             name="dollarchange2" value="${{ $data['blue']['value_sell'] }}" required
                                             readonly />
                                         <input id="totalbudget2" readonly
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             placeholder="Currency Exchange" maxlength="30" name="totalbudget2"
                                             required />
                                     </div>

                                     <script>
                                         // Obtenemos los elementos de los campos de entrada
                                         const amountField = document.getElementById('amount2');
                                         const totalBudgetField = document.getElementById('totalbudget2');
                                         const dollarChangeValue = parseFloat(
                                             {{ $data['blue']['value_sell'] }}); // Obtén el valor del dólar de la base de datos

                                         // Añadimos un event listener para detectar cambios en el campo "amount2"
                                         amountField.addEventListener('input', function() {
                                             const amountValue = parseFloat(amountField.value);
                                             if (!isNaN(amountValue)) {
                                                 const calculatedValue = (amountValue / dollarChangeValue).toFixed(2);
                                                 totalBudgetField.value = '$' + calculatedValue;
                                             }
                                         });
                                     </script>



                                     <!--  end dollar blue Calculator-->
                                 </div>
                                 <div class="hidden py-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="item2"
                                     role="tabpanel" aria-labelledby="item2-tab">
                                     <!--    official dollar Calculator-->
                                     <!-- official dollar Calculator -->
                                     <div class="flex flex-col md:flex-row lg:space-x-4">
                                         <input id="amount3" autocomplete="off"
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             placeholder="Amount (ARS)" name="amount3" maxlength="30" required />
                                         <input
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             name="dollarchange3" value="${{ $data['oficial']['value_sell'] }}" required
                                             readonly />
                                         <input id="totalbudget3" readonly
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             placeholder="Currency Exchange" maxlength="30" name="totalbudget3"
                                             required />
                                     </div>

                                     <script>
                                         // Obtenemos los elementos de los campos de entrada
                                         const amountField3 = document.getElementById('amount3');
                                         const totalBudgetField3 = document.getElementById('totalbudget3');
                                         const dollarChangeValue3 = parseFloat(
                                             {{ $data['oficial']['value_sell'] }}); // Obtén el valor del dólar oficial de la base de datos

                                         // Añadimos un event listener para detectar cambios en el campo "amount3"
                                         amountField3.addEventListener('input', function() {
                                             const amountValue3 = parseFloat(amountField3.value);
                                             if (!isNaN(amountValue3)) {
                                                 const calculatedValue = (amountValue3 / dollarChangeValue3).toFixed(2);
                                                 totalBudgetField3.value = '$' + calculatedValue;
                                             }
                                         });
                                     </script>
                                     <!-- end official dollar Calculator -->


                                     <!--  end  offcial dollar Calculator-->
                                 </div>
                                 <div class="hidden py-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="item3"
                                     role="tabpanel" aria-labelledby="item3-tab">
                                     <!-- euro oficial Calculator -->
                                     <div class="flex flex-col md:flex-row lg:space-x-4">
                                         <input id="amount4" autocomplete="off"
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             placeholder="Amount (ARS)" name="amount4" maxlength="30" required />
                                         <input
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             name="dollarchange4" value="€{{ $data['oficial_euro']['value_sell'] }}"
                                             required readonly />
                                         <input id="totalbudget4" readonly
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             placeholder="Currency Exchange" maxlength="30" name="totalbudget4"
                                             required />
                                     </div>

                                     <script>
                                         // Obtenemos los elementos de los campos de entrada
                                         const amountField4 = document.getElementById('amount4');
                                         const totalBudgetField4 = document.getElementById('totalbudget4');
                                         const euroOficialChangeValue = parseFloat(
                                             {{ $data['oficial_euro']['value_sell'] }});
                                         amountField4.addEventListener('input', function() {
                                             const amountValue4 = parseFloat(amountField4.value);
                                             if (!isNaN(amountValue4)) {
                                                 const calculatedValue = (amountValue4 / euroOficialChangeValue).toFixed(2);
                                                 totalBudgetField4.value = '€' + calculatedValue;
                                             }
                                         });
                                     </script>
                                     <!-- end euro oficial Calculator -->

                                 </div>
                                 <div class="hidden py-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="item4"
                                     role="tabpanel" aria-labelledby="item4-tab">
                                     <!-- euro blue Calculator -->
                                     <div class="flex flex-col md:flex-row lg:space-x-4">
                                         <input id="amount5" autocomplete="off"
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             placeholder="Amount (ARS)" name="amount5" maxlength="30" required />
                                         <input
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             name="dollarchange5" value="€{{ $data['blue_euro']['value_sell'] }}"
                                             required readonly />
                                         <input id="totalbudget5" readonly
                                             class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                             placeholder="Currency Exchange" maxlength="30" name="totalbudget5"
                                             required />
                                     </div>

                                     <script>
                                         // Obtenemos los elementos de los campos de entrada
                                         const amountField5 = document.getElementById('amount5');
                                         const totalBudgetField5 = document.getElementById('totalbudget5');
                                         const euroBlueChangeValue = parseFloat({{ $data['blue_euro']['value_sell'] }});

                                         amountField5.addEventListener('input', function() {
                                             const amountValue5 = parseFloat(amountField5.value); // Cambiado a 'amountValue5'
                                             if (!isNaN(amountValue5)) {
                                                 const calculatedValue = (amountValue5 / euroBlueChangeValue).toFixed(2);
                                                 totalBudgetField5.value = '€' + calculatedValue;
                                             }
                                         });
                                     </script>

                                     <!-- end euro blue Calculator -->
                                 </div>
                             </div>
                         </div>
                         <!-- END CURRENCY CALCULATOR -->

                         <!-- CURRENCY CALCULATOR 2-->

                         <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                             <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="myTab"
                                 data-tabs-toggle="#myTabContent2" role="tablist">
                                 <li class="mr-2" role="presentation">
                                     <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-tab"
                                         data-tabs-target="#tab1" type="button" role="tab"
                                         aria-controls="profile" aria-selected="false">Dolar Blue</button>
                                 </li>
                                 <li class="mr-2" role="presentation">
                                     <button
                                         class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                         id="tab1-tab" data-tabs-target="#tab2" type="button" role="tab"
                                         aria-controls="tab2" aria-selected="false">Dolar Oficial</button>
                                 </li>
                                 <li class="mr-2" role="presentation">
                                     <button
                                         class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                         id="tab3-tab" data-tabs-target="#tab3" type="button" role="tab"
                                         aria-controls="tab3" aria-selected="false">Euro Oficial</button>
                                 </li>
                                 <li role="presentation">
                                     <button
                                         class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                                         id="tab4-tab" data-tabs-target="#tab4" type="button" role="tab"
                                         aria-controls="tab4" aria-selected="false">Euro Blue</button>
                                 </li>
                             </ul>
                         </div>
                         <div id="myTabContent2" class="mb-5">
                             <h5
                                 class="text-base font-bold  text-left text-gray-600 capitalize my-3  dark:text-gray-400 ">
                                 Currency To ARS
                             </h5>
                             <div class="hidden py-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="tab1"
                                 role="tabpanel" aria-labelledby="tab1-tab">
                                 <!--   dollar blue Calculator-->
                                 <div class="flex flex-col md:flex-row lg:space-x-4">
                                     <input id="amount10" autocomplete="off"
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         placeholder="$ Currency Amount" name="amount10" maxlength="30" required />
                                     <input
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         name="dollarchange10" value="${{ $data['blue']['value_sell'] }}" required
                                         readonly />
                                     <input id="totalbudget10" readonly
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         placeholder="ARS Exchange" maxlength="30" name="totalbudget10" required />
                                 </div>

                                 <script>
                                     // Obtenemos los elementos de los campos de entrada
                                     const amountField10 = document.getElementById('amount10');
                                     const totalBudgetField10 = document.getElementById('totalbudget10');
                                     const dollarChangeValue10 = parseFloat(
                                         {{ $data['blue']['value_sell'] }}); // Obtén el valor del dólar de la base de datos

                                     // Añadimos un event listener para detectar cambios en el campo "amount11"
                                     amountField10.addEventListener('input', function() {
                                         const amountValue10 = parseFloat(amountField10.value);
                                         if (!isNaN(amountValue10)) {
                                             const calculatedValue = (amountValue10 * dollarChangeValue10).toLocaleString('en-US', {
                                                 style: 'currency',
                                                 currency: 'USD'
                                             });
                                             totalBudgetField10.value = calculatedValue;
                                         }
                                     });
                                 </script>

                                 <!--  end dollar blue Calculator-->
                             </div>
                             <div class="hidden py-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="tab2"
                                 role="tabpanel" aria-labelledby="tab2-tab">
                                 <!--   dollar oficial Calculator-->
                                 <div class="flex flex-col md:flex-row lg:space-x-4">
                                     <input id="amount11" autocomplete="off"
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         placeholder="$ Currency Amount" name="amount11" maxlength="30" required />
                                     <input
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         name="dollarchange11" value="${{ $data['oficial']['value_sell'] }}" required
                                         readonly />
                                     <input id="totalbudget11" readonly
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         placeholder="ARS Exchange" maxlength="30" name="totalbudget11" required />
                                 </div>

                                 <script>
                                     // Obtenemos los elementos de los campos de entrada
                                     const amountField11 = document.getElementById('amount11');
                                     const totalBudgetField11 = document.getElementById('totalbudget11');
                                     const dollarChangeValue11 = parseFloat(
                                         {{ $data['oficial']['value_sell'] }}); // Obtén el valor del dólar de la base de datos

                                     // Añadimos un event listener para detectar cambios en el campo "amount11"
                                     amountField11.addEventListener('input', function() {
                                         const amountValue11 = parseFloat(amountField11.value);
                                         if (!isNaN(amountValue11)) {
                                             const calculatedValue = (amountValue11 * dollarChangeValue11).toLocaleString('en-US', {
                                                 style: 'currency',
                                                 currency: 'USD'
                                             });
                                             totalBudgetField11.value = calculatedValue;
                                         }
                                     });
                                 </script>


                                 <!--  end dollar oficial Calculator-->
                             </div>
                             <div class="hidden py-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="tab3"
                                 role="tabpanel" aria-labelledby="tab3-tab">
                                 <!--   euro oficial Calculator-->
                                 <div class="flex flex-col md:flex-row lg:space-x-4">
                                     <input id="amount12" autocomplete="off"
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         placeholder="€ Currency Amount" name="amount12" maxlength="30" required />
                                     <input
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         name="dollarchange12" value="€{{ $data['oficial_euro']['value_sell'] }}"
                                         required readonly />
                                     <input id="totalbudget12" readonly
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         placeholder="ARS Exchange" maxlength="30" name="totalbudget12" required />
                                 </div>

                                 <script>
                                     // Obtenemos los elementos de los campos de entrada
                                     const amountField12 = document.getElementById('amount12');
                                     const totalBudgetField12 = document.getElementById('totalbudget12');
                                     const dollarChangeValue12 = parseFloat(
                                         {{ $data['oficial_euro']['value_sell'] }}); // Obtén el valor del euro de la base de datos

                                     // Añadimos un event listener para detectar cambios en el campo "amount12"
                                     amountField12.addEventListener('input', function() {
                                         const amountValue12 = parseFloat(amountField12.value);
                                         if (!isNaN(amountValue12)) {
                                             const calculatedValue = (amountValue12 * dollarChangeValue12).toLocaleString('en-US', {
                                                 style: 'currency',
                                                 currency: 'USD'
                                             });
                                             totalBudgetField12.value = calculatedValue;
                                         }
                                     });
                                 </script>



                                 <!--  end euro oficial Calculator-->
                             </div>
                             <div class="hidden py-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="tab4"
                                 role="tabpanel" aria-labelledby="tab4-tab">
                                 <!--   euro blue Calculator-->
                                 <div class="flex flex-col md:flex-row lg:space-x-4">
                                     <input id="amount13" autocomplete="off"
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         placeholder="€ Currency Amount" name="amount13" maxlength="30" required />
                                     <input
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         name="dollarchange13" value="€{{ $data['blue_euro']['value_sell'] }}"
                                         required readonly />
                                     <input id="totalbudget13" readonly
                                         class="block w-full text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                                         placeholder="ARS Exchange" maxlength="30" name="totalbudget13" required />
                                 </div>

                                 <script>
                                     // Obtenemos los elementos de los campos de entrada
                                     const amountField13 = document.getElementById('amount13');
                                     const totalBudgetField13 = document.getElementById('totalbudget13');
                                     const dollarChangeValue13 = parseFloat(
                                         {{ $data['blue_euro']['value_sell'] }}); // Obtén el valor del euro de la base de datos

                                     // Añadimos un event listener para detectar cambios en el campo "amount13"
                                     amountField13.addEventListener('input', function() {
                                         const amountValue13 = parseFloat(amountField13.value);
                                         if (!isNaN(amountValue13)) {
                                             const calculatedValue = (amountValue13 * dollarChangeValue13).toLocaleString('en-US', {
                                                 style: 'currency',
                                                 currency: 'USD'
                                             });
                                             totalBudgetField13.value = calculatedValue;
                                         }
                                     });
                                 </script>



                                 <!--  end euro blue Calculator-->
                             </div>
                         </div>

                         <!-- END CURRENCY CALCULATOR 2-->
                         <!-- Tables -->
                         <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">

                             <div class="w-full overflow-x-auto">


                                 <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.css"
                                     rel="stylesheet" />
                                 <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>

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
                                                 $ {{ $data['blue']['value_buy'] }}
                                             </td>
                                             <td class="px-4 py-3 text-xs">
                                                 $ {{ $data['blue']['value_sell'] }}

                                             </td>

                                         </tr>
                                         <tr class="text-gray-700  uppercase dark:text-gray-400">

                                             <td class="px-4 py-3 text-xs">
                                                 Euro Oficial
                                             </td>
                                             <td class="px-4 py-3 text-xs">
                                                 $ {{ $data['oficial_euro']['value_buy'] }}
                                             </td>
                                             <td class="px-4 py-3 text-xs">
                                                 $ {{ $data['oficial_euro']['value_sell'] }}

                                             </td>

                                         </tr>
                                         <tr class="text-gray-700  uppercase dark:text-gray-400">

                                             <td class="px-4 py-3 text-xs">
                                                 Euro Blue
                                             </td>
                                             <td class="px-4 py-3 text-xs">
                                                 $ {{ $data['blue_euro']['value_buy'] }}
                                             </td>
                                             <td class="px-4 py-3 text-xs">
                                                 $ {{ $data['blue_euro']['value_sell'] }}

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
                                                                 {{ \Carbon\Carbon::parse($data['last_update'])->format('l j \d\e F, H:i:s') }}</span>
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


                 <!-- END PANEL MAIN CALCULATOR -->

             </div>
         </div>


     </div>
 </x-app-layout>