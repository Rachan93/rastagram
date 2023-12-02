<section>
    <header>
        

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Assurez-vous que votre compte utilise un mot de passe long et aléatoire pour garantir la sécurité.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('Mot de passe actuel')" />
            <x-text-input id="current_password" name="current_password" type="password" class="mt-1 mb-10 block w-full focus:border-2 focus:border-red-600 focus:ring-0" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Nouveau mot de passe')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 mb-10 block w-full focus:border-2 focus:border-red-600 focus:ring-0" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmez votre nouveau mot de passe')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 mb-10 block w-full focus:border-2 focus:border-red-600 focus:ring-0" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-red-600 hover:bg-red-700">{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
