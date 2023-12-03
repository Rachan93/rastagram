<x-guest-layout>

    <div class="max-w-3xl mx-auto mt-10 space-y-8 bg-green-500 border-8 border-black p-8 rounded-md">
        

        <!-- Formulaire de Connexion -->
        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-6">
            @csrf

            <div>
                <x-input-label for="email" :value="__('E-mail')" />
                <x-text-input id="email" class="mt-1 block w-full focus:border-2 focus:border-red-600 focus:ring-0" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" :value="__('Mot de passe')" />
                <x-text-input id="password" class="mt-1 block w-full focus:border-2 focus:border-red-600 focus:ring-0" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="block mt-4 flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
                </label>

                <x-primary-button class="bg-red-600 hover:bg-red-700">
                    {{ __('Connexion') }}
                </x-primary-button>
            </div>
        </form>

       
</x-guest-layout>
