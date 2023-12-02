<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informations de profil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Changez vos informations de profil et votre adresse e-mail.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nom')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 mb-10 block w-full focus:border-2 focus:border-red-600 focus:ring-0 " :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('E-mail')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 mb-10 block w-full focus:border-2 focus:border-red-600 focus:ring-0 " :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
            
            
        <div class="mb-10" x-data="{ bio: {{ json_encode(old('bio', $user->bio)) }}, remainingCharacters: 255 - {{ strlen(old('bio', $user->bio)) }} }">
                
            <x-input-label for="bio" :value="__('Biographie')" />

                <textarea
                    x-model="bio"
                    x-on:input="remainingCharacters = 255 - bio.length"
                    x-bind:maxlength="255"
                    id="bio"
                    name="bio"
                    class="mt-1 block w-full border rounded-md shadow-sm focus:border-2 focus:border-red-600 focus:ring-0   sm:text-sm"
                    rows="4"
                ></textarea>

                <p class="text-sm text-gray-600 mt-2">{{ __('Caractères restants: ') }}<span x-text="remainingCharacters"></span></p>

                <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>



        
<div class="flex items-center gap-4">
            <x-primary-button class="bg-red-600 hover:bg-red-700">{{ __('Enregistrer') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
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
