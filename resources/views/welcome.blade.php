<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2563eb" />
    <meta name="description" content="
        {{ __('messages.metadescription') }}">
    <meta name="keywords" content="
        {{ __('messages.metakeywords') }}">
    <meta name="author" content="Smart Finance 365">
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/site.webmanifest">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://www.smart-finance365.com/">
    <meta property="og:title" content="Smart Finance 365 - Web App">
    <meta property="og:description"
        content="Smart Finance 365 - Your solution for easy income and expense management. Record transactions, create budgets, and achieve financial goals effortlessly.">
    <meta property="og:image" content="https://www.smart-finance365.com/img/logo.png">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://www.smart-finance365.com/">
    <meta property="twitter:title" content="Smart Finance 365 - Web App">
    <meta property="twitter:description"
        content="Smart Finance 365 - Your solution for easy income and expense management. Record transactions, create budgets, and achieve financial goals effortlessly.">
    <meta property="twitter:image" content="https://www.smart-finance365.com/img/logo.png">

    <title>Smart Finance 365 - Web App</title>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Styles -->

    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background-color: #e5e7eb;
            border-radius: 9px;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #6b7280;
            border-radius: 7px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #3b82f6;
            border-radius: 7px;
        }
    </style>

    <script type="text/javascript">
        function callbackThen(response) {
            // read Promise object
            response.json().then(function(data) {
                console.log(data);
                if (data.success && data.score >= 0.6) {
                    console.log('valid reCAPTCHA');
                    // Habilitar el envío del formulario si el reCAPTCHA es válido
                    $("#SubmitForm").prop("disabled", false);
                    $("#SubmitForm").css("opacity", "1");
                    $("#SubmitForm").html(translations.login);
                } else {
                    // Deshabilitar el envío del formulario si el reCAPTCHA falla
                    $("#SubmitForm").prop("disabled", true);
                    $("#SubmitForm").css("opacity", "0.5");
                    $("#SubmitForm").html("reCAPTCHA Error");
                    document.getElementById('form').addEventListener('submit', function(event) {
                        event.preventDefault();
                        alert('reCAPTCHA error');
                    });
                }
            });
        }

        function callbackCatch(error) {
            console.error('Error:', error)
        }
    </script>
    {!! htmlScriptTagJsApi([
        'callback_then' => 'callbackThen',
        'callback_catch' => 'callbackCatch',
    ]) !!}
</head>


<body class="antialiased min-h-screen"
    style="background-image: url({{ 'img/hero.jpg' }}); background-size: cover; background-position: center;">

    <section>
        <div class="px-4 py-12 mx-auto 2xl:max-w-7xl sm:px-6 md:px-12 lg:px-30 lg:py-24 2xl:px-12 -mt-10">
            <div class="flex flex-wrap items-center mx-auto 2xl:max-w-7xl">
                <div class="flex flex-col items-start mb-16 text-left lg:flex-grow lg:w-1/2 lg:pr-24 md:mb-0">
                    <div class="justify-center items-center mx-auto">
                        @if (Route::has('login'))
                            @auth
                                <a class="hidden lg:block" href="{{ route('dashboard') }}"> <x-logo /></a>
                            @else
                                <a class="hidden lg:block" href="/"> <x-logo /></a>
                            @endauth
                        @endif
                    </div>
                    <span class="hidden lg:block mb-8 text-xs font-bold tracking-widest text-blue-600 uppercase">
                        {{ __('messages.web_application') }}
                    </span>


                    <h1
                        class="hidden lg:block mb-8 text-4xl font-bold leading-none tracking-tighter text-neutral-600 md:text-7xl lg:text-5xl">
                        {{ __('messages.get_valuable_insights') }}</h1>
                    <p class=" hidden lg:block mb-8 text-base leading-relaxed text-left text-gray-400">
                        {{ __('messages.explore_metrics') }}</p>

                    @if (Route::has('login'))
                        @auth
                            <a class=" capitalize w-full lg:w-auto lg:inline-block py-3 px-6 bg-blue-600  transition duration-500 ease-in-out hover:bg-blue-700 text-sm text-white font-bold rounded-lg"
                                href="{{ route('dashboard') }}">dashboard</a>
                        @else
                            <div class="hidden lg:block">
                                <div class=" flex flex-1 gap-4 hover:cursor-pointer -mt-10">
                                    <img src="{{ asset('img/playstore.svg') }}" width="130" height="110"
                                        alt="" />
                                    <img src="{{ asset('img/ios.svg') }}" width="130" height="110" alt="" />
                                </div>
                            </div>
                        @endauth
                    @endif

                </div>
                <div class="w-full mt-12 lg:w-5/6 lg:max-w-lg xl:mt-0">
                    <div>
                        <div class="relative w-full max-w-full">
                            <div
                                class="absolute top-0 rounded-full bg-violet-300 -left-4 w-72 h-72 mix-blend-multiply filter blur-xl opacity-70 animate-blob">
                            </div>

                            <div
                                class="absolute rounded-full bg-fuchsia-300 -bottom-24 right-20 w-72 h-72 mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000">
                            </div>
                            <div class="relative">
                                @if (Route::has('login'))
                                    @auth
                                        <img class="object-cover object-center mx-auto shadow-2xl rounded-2xl "
                                            alt="hero" src="img/rectangle4.png">
                                    @else
                                        <div class="w-full p-10 bg-white rounded-2xl shadow-lg shadow-indigo-200 ">

                                            <a href="/" class="block lg:hidden">
                                                <x-logo />
                                            </a>
                                            <p class="text-xl font-bold text-blue-500 text-center ">
                                                {{ __('messages.welcome_back') }}</p>

                                            <form method="POST" id="form" action="{{ route('login') }}"
                                                class=" " autocomplete="off">
                                                @csrf
                                                <x-validation-errors class="mb-1 mx-auto ml-7 mt-2" />

                                                @if (session('status'))
                                                    <div class="mb-4 font-medium text-sm text-center text-indigo-600">
                                                        {{ session('status') }}
                                                    </div>
                                                @endif


                                                <div class="flex flex-col gap-4  md:p-2">
                                                    <div>
                                                        <a href="/google-auth/redirect"
                                                            class="flex items-center my-5 justify-center gap-2  rounded-lg shadow-md border border-gray-300 bg-white px-8 py-3 text-center text-sm font-semibold text-gray-800 outline-none ring-gray-300 transition duration-100 hover:bg-gray-100 focus-visible:ring active:bg-gray-200 md:text-base">
                                                            <svg class="h-5 w-5 shrink-0" width="24" height="24"
                                                                viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M23.7449 12.27C23.7449 11.48 23.6749 10.73 23.5549 10H12.2549V14.51H18.7249C18.4349 15.99 17.5849 17.24 16.3249 18.09V21.09H20.1849C22.4449 19 23.7449 15.92 23.7449 12.27Z"
                                                                    fill="#4285F4" />
                                                                <path
                                                                    d="M12.2549 24C15.4949 24 18.2049 22.92 20.1849 21.09L16.3249 18.09C15.2449 18.81 13.8749 19.25 12.2549 19.25C9.12492 19.25 6.47492 17.14 5.52492 14.29H1.54492V17.38C3.51492 21.3 7.56492 24 12.2549 24Z"
                                                                    fill="#34A853" />
                                                                <path
                                                                    d="M5.52488 14.29C5.27488 13.57 5.14488 12.8 5.14488 12C5.14488 11.2 5.28488 10.43 5.52488 9.71V6.62H1.54488C0.724882 8.24 0.254883 10.06 0.254883 12C0.254883 13.94 0.724882 15.76 1.54488 17.38L5.52488 14.29Z"
                                                                    fill="#FBBC05" />
                                                                <path
                                                                    d="M12.2549 4.75C14.0249 4.75 15.6049 5.36 16.8549 6.55L20.2749 3.13C18.2049 1.19 15.4949 0 12.2549 0C7.56492 0 3.51492 2.7 1.54492 6.62L5.52492 9.71C6.47492 6.86 9.12492 4.75 12.2549 4.75Z"
                                                                    fill="#EA4335" />
                                                            </svg>

                                                            {{ __('messages.continue_with_google') }}
                                                        </a>
                                                        <div class="relative flex items-center justify-center my-8">
                                                            <span class="absolute inset-x-0 h-px bg-gray-300"></span>
                                                            <span
                                                                class="relative bg-white px-4 text-sm text-gray-400">{{ __('messages.or_login_with') }}</span>
                                                        </div>

                                                        <div class="relative mb-9">
                                                            <div class="input-container">
                                                                <div
                                                                    class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">

                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                        aria-hidden="true"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="currentColor"
                                                                        viewBox="0 0 512 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                                        <path
                                                                            d="M399 384.2C376.9 345.8 335.4 320 288 320H224c-47.4 0-88.9 25.8-111 64.2c35.2 39.2 86.2 63.8 143 63.8s107.8-24.7 143-63.8zM0 256a256 256 0 1 1 512 0A256 256 0 1 1 0 256zm256 16a72 72 0 1 0 0-144 72 72 0 1 0 0 144z" />
                                                                    </svg>

                                                                </div>
                                                                <input type="text" name="identity" id="identity"
                                                                    :value="old('email')" required autofocus
                                                                    autocomplete="username"
                                                                    class="bg-gray-50 border border-gray-300 px-5 py-3 mt-2 mb-2 text-gray-900 text-sm rounded-lg focus:ring-indigo-500
                                 focus:border-indigo-500 block w-full pl-10  p-2.5  dark:border-gray-700  dark:focus:border-indigo-400 focus:outline-none focus:ring focus:ring-opacity-40"
                                                                    placeholder="Email or Username">
                                                            </div>
                                                        </div>

                                                        <div class="relative mb-2">
                                                            <div class="input-container">
                                                                <div
                                                                    class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">


                                                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400"
                                                                        aria-hidden="true"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="currentColor"
                                                                        viewBox="0 0 448 512"><!--! Font Awesome Free 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                                                        <path
                                                                            d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z" />
                                                                    </svg>

                                                                </div>

                                                                <input type="password" id="password" name="password"
                                                                    required autocomplete="off" autofocus
                                                                    autocomplete="off" id="input-group-1"
                                                                    class="bg-gray-50 border border-gray-300 px-5 py-3 mt-2 mb-2 text-gray-900 text-sm rounded-lg focus:ring-indigo-500
                                 focus:border-indigo-500 block w-full pl-10  p-2.5  dark:border-gray-700  dark:focus:border-indigo-400 focus:outline-none focus:ring focus:ring-opacity-40"
                                                                    placeholder="Password">
                                                                <span id="toggle-password" class="password-toggle"
                                                                    onclick="togglePasswordVisibility()">
                                                                    <i class=" text-gray-500 fa-regular fa-eye"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>



                                                    <div class="flex justify-between items-center mb-8">
                                                        <div class="block">
                                                            <label for="remember_me" class="flex items-center">
                                                                <x-checkbox id="remember_me" name="remember" />
                                                                <span
                                                                    class="ml-2 text-sm text-gray-600">{{ __('messages.remember_me') }}</span>
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center justify-end ">
                                                            @if (Route::has('password.request'))
                                                                <a class="no-underline text-sm text-gray-600 hover:text-indigo-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                                    href="{{ route('password.request') }}">
                                                                    {{ __('messages.forgot_password') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <button type="submit" id="SubmitForm"
                                                        class="block rounded-lg bg-blue-600 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-500 ease-in-out hover:bg-blue-800 focus-visible:ring active:bg-blue-600 md:text-base">
                                                        {{ __('messages.login') }}</button>


                                                </div>

                                                <div class="flex items-center justify-center  ">
                                                    <p class="text-center text-sm text-gray-500 mt-5">
                                                        {{ __('messages.dont_have_account') }}
                                                        <a href="{{ route('register') }}"
                                                            class="text-indigo-500 transition duration-100 hover:text-indigo-600 active:text-indigo-700">{{ __('messages.sign_up') }}
                                                        </a>
                                                    </p>
                                                </div>
                                            </form>

                                        </div>

                                    @endauth
                                @endif



                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>





        <footer class="flex flex-col space-y-10 justify-center ">
            <div class="block lg:hidden">
                <div class=" flex justify-center gap-4 hover:cursor-pointer -mt-10">
                    <img src="{{ asset('img/playstore.svg') }}" width="130" height="110" alt="" />
                    <img src="{{ asset('img/ios.svg') }}" width="130" height="110" alt="" />
                </div>
            </div>
            <nav class="flex justify-center flex-wrap gap-6 text-gray-400 ">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard') }}" class="hover:text-blue-600 ">{{ __('messages.home') }}</a>
                    @else
                        <a class="hover:text-blue-600 " href="/">{{ __('messages.home') }}</a>
                    @endauth
                @endif


                <a class="hover:text-blue-600" href="#">{{ __('messages.about') }}</a>
                <a class="hover:text-blue-600" href="#">{{ __('messages.product') }}</a>
                <a class="hover:text-blue-600" href="#">{{ __('messages.help_center') }}</a>
                <a class="hover:text-blue-600" href="#">{{ __('messages.pricing') }}</a>
                <a class="hover:text-blue-600" href="#">{{ __('messages.terms_of_service') }}</a>
                <a class="hover:text-blue-600" href="#">
                    {{ __('messages.privacy_policy') }}</a>
                <a class="hover:text-blue-600" href="#">
                    {{ __('messages.cookies_policy') }}</a>
                <a class="hover:text-blue-600" href="{{ route('latest-posts') }}">
                    Blog</a>
                <a class="hover:text-blue-600" href="#">{{ __('messages.contact') }}</a>
            </nav>

            <div class="flex justify-center space-x-5">
                <a href=""> <img src="https://www.svgrepo.com/show/303114/facebook-3-logo.svg" width="30"
                        height="30" alt="fb" /></a>
                <a href="javascript:;"
                    class="relative w-8 h-8 rounded-full transition-all duration-500 flex justify-center items-center bg-[linear-gradient(45deg,#FEE411_6.9%,#FEDB16_10.98%,#FEC125_17.77%,#FE983D_26.42%,#FE5F5E_36.5%,#FE2181_46.24%,#9000DC_85.57%)]  hover:bg-gradient-to-b from-gray-900 to-gray-900  
                        ">
                    <svg class="w-[1.25rem] h-[1.125rem] text-white" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.63434 7.99747C5.63434 6.69062 6.6941 5.63093 8.00173 5.63093C9.30936 5.63093 10.3697 6.69062 10.3697 7.99747C10.3697 9.30431 9.30936 10.364 8.00173 10.364C6.6941 10.364 5.63434 9.30431 5.63434 7.99747ZM4.35427 7.99747C4.35427 10.0108 5.98723 11.6427 8.00173 11.6427C10.0162 11.6427 11.6492 10.0108 11.6492 7.99747C11.6492 5.98418 10.0162 4.3522 8.00173 4.3522C5.98723 4.3522 4.35427 5.98418 4.35427 7.99747ZM10.9412 4.20766C10.9411 4.37615 10.991 4.54087 11.0846 4.681C11.1783 4.82113 11.3113 4.93037 11.4671 4.99491C11.6228 5.05945 11.7942 5.07639 11.9595 5.04359C12.1249 5.01078 12.2768 4.92971 12.3961 4.81062C12.5153 4.69153 12.5966 4.53977 12.6295 4.37453C12.6625 4.2093 12.6457 4.03801 12.5812 3.88232C12.5168 3.72663 12.4076 3.59354 12.2674 3.49988C12.1273 3.40622 11.9625 3.35619 11.7939 3.35612H11.7936C11.5676 3.35623 11.3509 3.44597 11.1911 3.60563C11.0313 3.76529 10.9414 3.98182 10.9412 4.20766ZM5.132 13.7759C4.43946 13.7444 4.06304 13.6291 3.81289 13.5317C3.48125 13.4027 3.24463 13.249 2.99584 13.0007C2.74705 12.7524 2.59305 12.5161 2.46451 12.1847C2.367 11.9348 2.25164 11.5585 2.22016 10.8664C2.18572 10.1181 2.17885 9.89331 2.17885 7.99752C2.17885 6.10174 2.18629 5.87758 2.22016 5.12866C2.2517 4.43654 2.36791 4.06097 2.46451 3.81035C2.59362 3.47891 2.7474 3.24242 2.99584 2.99379C3.24428 2.74515 3.48068 2.59124 3.81289 2.46278C4.06292 2.36532 4.43946 2.25004 5.132 2.21857C5.88074 2.18416 6.10566 2.17729 8.00173 2.17729C9.89779 2.17729 10.1229 2.18472 10.8723 2.21857C11.5648 2.25009 11.9406 2.36623 12.1914 2.46278C12.5231 2.59124 12.7597 2.74549 13.0085 2.99379C13.2573 3.24208 13.4107 3.47891 13.5398 3.81035C13.6373 4.06023 13.7527 4.43654 13.7841 5.12866C13.8186 5.87758 13.8255 6.10174 13.8255 7.99752C13.8255 9.89331 13.8186 10.1175 13.7841 10.8664C13.7526 11.5585 13.6367 11.9347 13.5398 12.1847C13.4107 12.5161 13.2569 12.7526 13.0085 13.0007C12.76 13.2488 12.5231 13.4027 12.1914 13.5317C11.9414 13.6292 11.5648 13.7444 10.8723 13.7759C10.1236 13.8103 9.89865 13.8172 8.00173 13.8172C6.10481 13.8172 5.88051 13.8103 5.132 13.7759ZM5.07318 0.941429C4.31699 0.975845 3.80027 1.09568 3.34902 1.27116C2.88168 1.45239 2.48605 1.69552 2.09071 2.09C1.69537 2.48447 1.45272 2.88049 1.27139 3.34755C1.0958 3.79882 0.975892 4.31494 0.941455 5.07068C0.90645 5.82761 0.898438 6.0696 0.898438 7.99747C0.898438 9.92534 0.90645 10.1673 0.941455 10.9243C0.975892 11.68 1.0958 12.1961 1.27139 12.6474C1.45272 13.1142 1.69543 13.5106 2.09071 13.9049C2.48599 14.2992 2.88168 14.542 3.34902 14.7238C3.80113 14.8993 4.31699 15.0191 5.07318 15.0535C5.83096 15.0879 6.0727 15.0965 8.00173 15.0965C9.93075 15.0965 10.1729 15.0885 10.9303 15.0535C11.6865 15.0191 12.2029 14.8993 12.6544 14.7238C13.1215 14.542 13.5174 14.2994 13.9127 13.9049C14.3081 13.5105 14.5502 13.1142 14.7321 12.6474C14.9077 12.1961 15.0281 11.68 15.062 10.9243C15.0964 10.1668 15.1044 9.92534 15.1044 7.99747C15.1044 6.0696 15.0964 5.82761 15.062 5.07068C15.0276 4.31489 14.9077 3.79853 14.7321 3.34755C14.5502 2.88077 14.3075 2.4851 13.9127 2.09C13.518 1.69489 13.1215 1.45239 12.655 1.27116C12.2029 1.09568 11.6865 0.975277 10.9308 0.941429C10.1735 0.907013 9.93132 0.898438 8.00229 0.898438C6.07327 0.898438 5.83096 0.906445 5.07318 0.941429Z"
                            fill="white" />
                    </svg>

                </a>
                <a href="javascript:;"
                    class="w-8 h-8 rounded-full transition-all duration-500 flex justify-center items-center bg-[#33CCFF] hover:bg-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                        fill="none">
                        <g id="Social Media">
                            <path id="Vector"
                                d="M11.3214 8.93666L16.4919 3.05566H15.2667L10.7772 8.16205L7.1914 3.05566H3.05566L8.47803 10.7774L3.05566 16.9446H4.28097L9.022 11.552L12.8088 16.9446H16.9446L11.3211 8.93666H11.3214ZM9.64322 10.8455L9.09382 10.0765L4.72246 3.95821H6.60445L10.1322 8.8959L10.6816 9.66481L15.2672 16.083H13.3852L9.64322 10.8458V10.8455Z"
                                fill="white" />
                        </g>
                    </svg>
                </a>

                <a href=""> <img src="https://www.svgrepo.com/show/28145/linkedin.svg" width="30"
                        height="30" alt="in" /></a>
            </div>
            <p class="text-center text-gray-600 ">&copy; {{ date('Y') }} Smart Finance 365.
                {{ __('messages.all_rights_reserved') }}</p>
        </footer>

    </section>




    <style>
        .password-input-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>

    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.querySelector('#password');
            const toggleButton = document.querySelector('#toggle-password');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.innerHTML = '<i class="fa-regular fa-eye-slash"></i>'; // Cambia el icono a ojo tachado
            } else {
                passwordInput.type = 'password';
                toggleButton.innerHTML = '<i class="fa-regular fa-eye"></i>'; // Cambia el icono a ojo
            }
        }
    </script>


    <!-- START JQUERY VALIDATE  -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
    <script>
        $(document).ready(function() {
            // Extiende el plugin jQuery Validation con una regla personalizada
            $.validator.addMethod("customPassword", function(value, element) {
                    // Utiliza una expresión regular para validar la contraseña
                    return /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&+=!]).*$/.test(value);
                },
                "{{ __('messages.form_register_password_customPassword') }}"
            );

            $("#form").validate({
                // Agrega la opción "errorClass" para especificar la clase CSS para los mensajes de error
                errorClass: "error-message",
                rules: {
                    identity: {
                        required: true,
                    },
                    password: {
                        required: true,
                        minlength: 5,
                    },
                },
                messages: {
                    password: {
                        required: "{{ __('messages.form_register_password_required') }}",
                        minlength: "{{ __('messages.form_register_password_minlength') }}",
                    },
                    identity: {
                        required: "{{ __('messages.form_register_email_or_username_required') }}",
                    },
                },
                submitHandler: function(form) {
                    // Deshabilita el botón, cambia la opacidad y muestra "Initializing..."
                    $("#SubmitForm").prop("disabled", true);
                    $("#SubmitForm").css("opacity", "0.5");
                    $("#SubmitForm").html("{{ __('messages.initializing') }}");

                    // Continúa con el envío del formulario
                    form.submit();
                }
            });
        });
    </script>

    <style>
        input.invalid,
        select.invalid,
        textarea.invalid {
            border-color: red;


        }

        input.valid,
        select.valid,
        textarea.valid {
            border-color: green;
            /* Cambia el color de fondo a verde */
        }

        .error-message {
            color: red;
            font-size: 14px;
            padding-bottom: 5px;
            margin-bottom: 5px;

        }



        .input-container {
            position: relative;
            height: 48px;
            /* Ajusta la altura según sea necesario */
        }
    </style>
    <!--END JQUERY VALIDATE -->



</body>

</html>
