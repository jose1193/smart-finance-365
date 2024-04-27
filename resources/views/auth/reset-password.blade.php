<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email"
                    class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border
                                 border-gray-200 rounded-md dark:placeholder-gray-600
                                   focus:border-indigo-400
                                    dark:focus:border-indigo-400 focus:ring-indigo-400 
                                    focus:outline-none focus:ring focus:ring-opacity-40"
                    type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('messages.password') }}" />
                <x-input id="password"
                    class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border
                                 border-gray-200 rounded-md dark:placeholder-gray-600
                                   focus:border-indigo-400
                                    dark:focus:border-indigo-400 focus:ring-indigo-400 
                                    focus:outline-none focus:ring focus:ring-opacity-40"
                    type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('messages.confirm_password') }}" />
                <x-input id="password_confirmation"
                    class="block w-full px-5 py-3 mt-2 text-gray-700 placeholder-gray-400 bg-white border
                                 border-gray-200 rounded-md dark:placeholder-gray-600
                                   focus:border-indigo-400
                                    dark:focus:border-indigo-400 focus:ring-indigo-400 
                                    focus:outline-none focus:ring focus:ring-opacity-40"
                    type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('messages.reset_password') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
