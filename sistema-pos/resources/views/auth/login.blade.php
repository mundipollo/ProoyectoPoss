<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-white/90" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="'Correo electrónico'" class="text-white/90" />
            <x-text-input id="email" class="block mt-1 w-full bg-white/95 border-white/30 focus:border-white focus:ring-white" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-200" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="'Contraseña'" class="text-white/90" />

            <x-text-input id="password" class="block mt-1 w-full bg-white/95 border-white/30 focus:border-white focus:ring-white"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-200" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-white/80">Recordarme</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-white/85 hover:text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-0 focus:ring-white" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif

            <x-primary-button class="ms-3 !bg-white !text-neutral-900 hover:!bg-neutral-200 focus:!bg-neutral-200 active:!bg-neutral-300">
                Ingresar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
