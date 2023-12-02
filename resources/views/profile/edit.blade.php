<x-user-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
        <div class="max-w-3xl mx-auto mt-10 space-y-8">

            <div class="bg-green-500 border-8 border-black p-8 rounded-md">
                <div class="mb-4 text-2xl font-bold">
                    Modifier votre profil
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="bg-green-500 border-8 border-black p-8 rounded-md">
                <div class="mb-4 text-2xl font-bold">
                    Changer votre avatar
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-avatar-form')
                </div>
            </div>

            <div class="bg-green-500 border-8 border-black p-8 rounded-md">
                <div class="mb-4 text-2xl font-bold">
                    Modifier votre mot de passe
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="bg-green-500 border-8 border-black p-8 rounded-md">
                <div class="mb-4 text-2xl font-bold">
                    Supprimer votre compte
                </div>

                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-user-layout>
