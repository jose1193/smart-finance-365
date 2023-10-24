 <!-- COMPONENTS.WELCOME.BLADE.PHP -->
 <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">

     <!-- Card -->
     <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
         <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full dark:text-green-100 dark:bg-green-500">
             <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                 <path fill-rule="evenodd"
                     d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"
                     clip-rule="evenodd"></path>
             </svg>
         </div>
         <div>
             <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                 General Balance
             </p>
             <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                 $ {{ $formatted_amount = number_format($account_balance, 0, '.', ',') }}
             </p>
         </div>
     </div>
     <!-- Card -->
     <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
         <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full dark:text-blue-100 dark:bg-blue-500">
             <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 448 512">
                 <path
                     d="M448 80v48c0 44.2-100.3 80-224 80S0 172.2 0 128V80C0 35.8 100.3 0 224 0S448 35.8 448 80zM393.2 214.7c20.8-7.4 39.9-16.9 54.8-28.6V288c0 44.2-100.3 80-224 80S0 332.2 0 288V186.1c14.9 11.8 34 21.2 54.8 28.6C99.7 230.7 159.5 240 224 240s124.3-9.3 169.2-25.3zM0 346.1c14.9 11.8 34 21.2 54.8 28.6C99.7 390.7 159.5 400 224 400s124.3-9.3 169.2-25.3c20.8-7.4 39.9-16.9 54.8-28.6V432c0 44.2-100.3 80-224 80S0 476.2 0 432V346.1z" />
             </svg>


         </div>
         <div>
             <p class="mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">
                 Total Operations
             </p>
             <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
                 {{ $total_users_operations }}
             </p>
         </div>
     </div>
     <!-- Card -->
     <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
         <div class="p-3 mr-4 text-orange-500 bg-orange-100 rounded-full dark:text-orange-100 dark:bg-orange-500">
             <svg class="w-5 h-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="1em"
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
                 $ {{ $formatted_amount = number_format($unpaid, 0, '.', ',') }}
             </p>
         </div>
     </div>
     <!-- Card -->
     <div class="flex items-center p-4 bg-white rounded-lg shadow-xs dark:bg-gray-800">
         <div class="p-3 mr-4 text-teal-500 bg-teal-100 rounded-full dark:text-teal-100 dark:bg-teal-500">
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
                 $ {{ $formatted_amount = number_format($paid, 0, '.', ',') }}
             </p>
         </div>
     </div>
 </div>
