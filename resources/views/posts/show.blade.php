<x-user-layout>

    <div class="max-w-2xl mx-auto mt-10 bg-white p-8 rounded-md shadow-md">
        <h2 class="text-2xl font-semibold mb-4">{{ $post->description }}</h2>

        <div class="mb-4">
            <img src="{{ $post->image_url }}" alt="{{ $post->description }}" class="w-full h-auto rounded-md">
        </div>

        <p class="text-gray-700">{{ $post->user->name }}</p>
        <p class="text-gray-500">{{ $post->created_at->diffForHumans() }}</p>

        {{-- Add other details you want to display about the post --}}

        <div class="mt-8">
            <a href="{{ route('posts.index') }}" class="text-blue-500 hover:underline">Back to Posts</a>
        </div>
    </div>

    <!-- Comment form -->
    @auth
        <form action="{{ route('posts.comments.add', $post->id) }}" method="POST" class="flex bg-white rounded-md shadow p-4 mt-8">
            @csrf
            <div class="flex justify-start items-start h-full mr-4">
                <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}'s Avatar" class="h-10 w-10 rounded-full">
            </div>

            @php
                $maxCharacters = 255; // You can set this dynamically based on your requirement
            @endphp

            <div class="flex flex-col justify-center w-full" x-data="{ maxCharacters: {{ $maxCharacters }}, remainingCharacters: {{ $maxCharacters }} - '{{ strlen(old('content')) }}', content: '' }">
                <div class="text-gray-700">{{ auth()->user()->name }}</div>
                <div class="text-gray-500 text-sm">{{ auth()->user()->email }}</div>
                <div class="text-gray-700">
                    <textarea
                        name="content"
                        id="content"
                        x-on:input="remainingCharacters = maxCharacters - $event.target.value.length"
                        x-bind:maxlength="maxCharacters"
                        x-model="content"
                        placeholder="Votre commentaire"
                        class="w-full rounded-md shadow-sm border-gray-300 bg-gray-100 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 mt-4"
                    ></textarea>
                </div>
                <div class="text-gray-700 mt-2 flex justify-end">
                    <p class="text-sm text-gray-600">{{ __('Caractères restants: ') }}<span x-text="remainingCharacters"></span></p>
                    <x-input-error :messages="$errors->get('content')" class="ml-2" />
                    <x-primary-button type="submit" class="ml-2">
                        Ajouter un commentaire
                    </x-primary-button>
                </div>
            </div>
        </form>
    @else
        <div class="flex bg-white rounded-md shadow p-4 text-gray-700 justify-between items-center">
            <span> Vous devez être connecté pour ajouter un commentaire </span>
            <a
                href="{{ route('login') }}"
                class="font-bold bg-white text-gray-700 px-4 py-2 rounded shadow-md"
            >Se connecter</a>
        </div>
    @endauth

    <!-- Comments section -->
    <div class="mt-8">
        <h2 class="font-bold text-xl mb-4">Commentaires</h2>

        <!-- Comments loop -->
        <div class="flex-col space-y-4">
            @forelse ($post->commentaires as $comment)
                <div class="flex bg-white rounded-md shadow p-4 space-x-4">
                    <div class="flex justify-start items-start h-full">
                        <img src="{{ asset($comment->user->profile_photo) }}" alt="{{ $comment->user->name }}'s profile photo" class="w-6 h-6 rounded-full">
                    </div>
                    <div class="flex flex-col justify-center">
                        <div class="text-gray-700">
                            {{ $comment->user->name }}
                        </div>
                        <div class="text-gray-500">
                            {{ $comment->created_at->diffForHumans() }}
                        </div>
                        <div class="text-gray-700 whitespace-normal overflow-hidden max-h-40 break-all">
                            {{ $comment->content }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex bg-white rounded-md shadow p-4 space-x-4">
                    Aucun commentaire pour l'instant
                </div>
            @endforelse
        </div>
    </div>

</x-user-layout>
