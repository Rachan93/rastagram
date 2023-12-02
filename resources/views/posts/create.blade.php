<x-user-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-12">
        <div class="max-w-3xl mx-auto mt-10 bg-green-500 border-8 border-black p-8 rounded-md">

            <div class="mb-4 text-2xl font-bold">
                Créer un post
            </div>

            <form method="POST" action="{{ route('posts.store') }}" class="flex flex-col space-y-4 text-gray-500" enctype="multipart/form-data">
                @csrf
                <div>
                    <x-input-label for="image" :value="__('Image*')" />
                    <x-text-input id="image" class="block mt-1 w-2xl" type="file" name="image" />
                    <x-input-error :messages="$errors->get('image')" class="mt-2" />
                </div>
                
                <div>
                    <x-input-label for="description" :value="__('Description*')" />
                    <x-text-input id="description" class="block mt-1 w-full  focus:border-red-600 focus:border-2 focus:ring-0" type="text" name="description"
                        :value="old('description')" autofocus />
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="localisation" :value="__('Localisation')" />
                    <x-text-input id="localisation" class="block mt-1 w-full focus:border-red-600 focus:border-2 focus:ring-0" type="text" name="localisation" :value="old('localisation')" />
                    <x-input-error :messages="$errors->get('localisation')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="date" :value="__('Date')" />
                    <x-text-input id="date" class="block mt-1 w-full focus:border-red-600 focus:border-2 focus:ring-0" type="date" name="date"
                        :value="old('date')" />
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>

                <div class="flex justify-between">

                <a href="{{ route('posts.index') }}" class="bg-green-700 hover:bg-green-800 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        Annuler
                    </a>
                    <x-primary-button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold">
                        {{ __('Créer') }}
                    </x-primary-button>
                </div>
            </form>

        </div>
    </div>
</x-user-layout>
