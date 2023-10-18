<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#2563eb" />
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon/favicon-16x16.png">
    <link rel="manifest" href="img/favicon/site.webmanifest">

    <title>Smart Finance - Web App</title>

    <!-- Fonts -->
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
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
</head>

<body class="antialiased" class="bg-no-repeat bg-cover bg-center" style="background-image: url({{ 'img/hero.jpg' }})">


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
                    <span class="hidden lg:block mb-8 text-xs font-bold tracking-widest text-blue-600 uppercase"> Web
                        Application
                    </span>


                    <h1
                        class="hidden lg:block mb-8 text-4xl font-bold leading-none tracking-tighter text-neutral-600 md:text-7xl lg:text-5xl">
                        Get valuable insights from your stats with our app.</h1>
                    <p class=" hidden lg:block mb-8 text-base leading-relaxed text-left text-gray-400">Explore your
                        metrics and trends efficiently with our advanced features. Unlock the Power of Your Data with
                        our Software</p>

                    @if (Route::has('login'))
                        @auth
                            <a class=" capitalize w-full lg:w-auto lg:inline-block py-3 px-6 bg-blue-600  transition duration-500 ease-in-out hover:bg-blue-700 text-sm text-white font-bold rounded-lg"
                                href="{{ route('dashboard') }}">dashboard</a>
                        @else
                            <a class=" hidden lg:block capitalize w-full lg:w-auto  py-3 px-6 bg-blue-600  transition duration-500 ease-in-out hover:bg-blue-700 text-sm text-white font-bold rounded-lg"
                                href="{{ route('register') }}">Let's Get Started</a>
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
                                                Welcome back!</p>

                                            <form method="POST" id="form" action="{{ route('login') }}" class=" "
                                                autocomplete="off">
                                                @csrf
                                                <x-validation-errors class="mb-1 mx-auto ml-7 mt-2" />

                                                @if (session('status'))
                                                    <div class="mb-4 font-medium text-sm text-center text-indigo-600">
                                                        {{ session('status') }}
                                                    </div>
                                                @endif


                                                <div class="flex flex-col gap-4 p-4 md:p-8">
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

                                                            Continue with Google
                                                        </a>
                                                        <div class="relative flex items-center justify-center my-8">
                                                            <span class="absolute inset-x-0 h-px bg-gray-300"></span>
                                                            <span class="relative bg-white px-4 text-sm text-gray-400">Or
                                                                Login
                                                                With</span>
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
                                                                    required autocomplete="current-password" autofocus
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
                                                                    class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                                                            </label>
                                                        </div>
                                                        <div class="flex items-center justify-end ">
                                                            @if (Route::has('password.request'))
                                                                <a class="no-underline text-sm text-gray-600 hover:text-indigo-700 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                                    href="{{ route('password.request') }}">
                                                                    {{ __('Forgot Password?') }}
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <button type="submit" id="SubmitForm"
                                                        class="block rounded-lg bg-blue-600 px-8 py-3 text-center text-sm font-semibold text-white outline-none ring-indigo-300 transition duration-500 ease-in-out hover:bg-blue-800 focus-visible:ring active:bg-blue-600 md:text-base">
                                                        {{ __('Login') }}</button>


                                                </div>

                                                <div class="flex items-center justify-center  ">
                                                    <p class="text-center text-sm text-gray-500">Don't have an account? <a
                                                            href="{{ route('register') }}"
                                                            class="text-indigo-500 transition duration-100 hover:text-indigo-600 active:text-indigo-700">Sign
                                                            Up </a>
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
                "The password must contain at least one uppercase, one lowercase, one number, and one special character"
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
                    },
                },
                messages: {
                    password: {
                        required: "Please provide a password",
                    },
                    identity: {
                        required: "Please provide an Email or Username",
                    },
                },
                submitHandler: function(form) {
                    // Deshabilita el botón, cambia la opacidad y muestra "Initializing..."
                    $("#SubmitForm").prop("disabled", true);
                    $("#SubmitForm").css("opacity", "0.5");
                    $("#SubmitForm").html("Initializing...");

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
