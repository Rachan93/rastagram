<x-user-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between mt-8">
                <div class="text-2xl">
                    Créer un post
                </div>
            </div>

            <form method="POST" action="{{ route('posts.store') }}" class="flex flex-col space-y-4 text-gray-500">

                @csrf

                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <x-text-input id="description" class="block mt-1 w-full" type="text" name="description"
                        :value="old('description')" autofocus />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="localisation" :value="__('Localisation')" />
                    <x-text-input id="localisation" class="block mt-1 w-full" type="text" name="localisation" :value="old('localisation')" />
                    <x-input-error :messages="$errors->get('localisation')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="date" :value="__('Date de publication')" />
                    <x-text-input id="date" class="block mt-1 w-full" type="date" name="date"
                        :value="old('date')" />
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="image_url" :value="__('URL de l\'image')" />
                    <x-text-input id="image_url" class="block mt-1 w-full" type="text" name="image_url"
                        :value="old('image_url')" />
                    <x-input-error :messages="$errors->get('image_url')" class="mt-2" />
                </div>

                <div class="flex justify-end">
                    <x-primary-button type="submit">
                        {{ __('Créer') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-user-layout>
