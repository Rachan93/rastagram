<x-user-layout>

    <!--posts-->
    <div class="max-w-3xl mx-auto mt-10 bg-green-500 border-8 border-black p-8 rounded-md shadow-md">


        <div class="mb-4">
            <img src="{{ asset('storage/' . $post->image_url) }}" alt="{{ $post->description }}" class="w-full h-auto rounded-md">

        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">

                <a href="{{ route('profile.show', $post->user) }}" class="text-gray-700">
                    <x-avatar class="h-8 w-8" :user="$post->user" />
                    {{ $post->user->name }}
                </a>
            </div>

            <div class="text-gray-500">
                {{ $post->created_at->diffForHumans() }}
            </div>
        </div>
        <p class="text-2xl font-semibold mb-4">{{ $post->description }}</p>
        <p class="text-gray-700">{{ $post->localisation }}</p>
        <p class="text-gray-700">{{ $post->date }}</p>

        <!-- Display Like Count -->
        <p>{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</p>

        <!-- Like/Unlike Buttons -->
        @auth
        @if (!$post->likedBy(auth()->user()))
        <form action="{{ route('posts.like', $post) }}" method="post">
            @csrf
            <button type="submit">Like</button>
        </form>
        @else
        <form action="{{ route('posts.unlike', $post) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit">Unlike</button>
        </form>
        @endif
        @endauth

        <!-- Edit button for the post owner -->
        @auth
        @if(auth()->user()->id == $post->user->id)
        <div class="mt-4">
            <a href="{{ route('posts.edit', $post) }}" class="text-blue-500 hover:underline">Modifier le post</a>
        </div>
        @endif
        @endauth

        <div class="mt-8">
            <a href="{{ route('posts.index') }}" class="text-blue-500 hover:underline">Retour au feed</a>
        </div>
    </div>

    <!-- Comment form -->




    @auth
    <form action="{{ route('posts.comments.add', $post->id) }}" method="POST" class="flex bg-green-500 rounded-md shadow p-4 mt-8 mx-auto max-w-3xl border-8 border-black mb-10">
        @csrf
        <div class="flex justify-start items-start h-full mr-4">
            <a href="{{ route('profile.show', $post->user) }}" class="text-gray-700">

                <x-avatar class="h-8 w-8" :user="auth()->user()" />
            </a>
        </div>

        @php
        $maxCharacters = 255;
        @endphp

        <div class="w-full flex flex-col justify-center " x-data="{ maxCharacters: {{ $maxCharacters }}, remainingCharacters: {{ $maxCharacters }} - '{{ strlen(old('content')) }}', content: '' }">
            <a href="{{ route('profile.show', auth()->user()) }}" class="text-gray-600">
                <div class="font-bold text-black">{{ auth()->user()->name }}</div>
                <div class="text-gray-700 text-sm">{{ auth()->user()->email }}</div>
            </a>
            <div class="text-gray-700">
                <textarea name="content" id="content" x-on:input="remainingCharacters = maxCharacters - $event.target.value.length" x-bind:maxlength="maxCharacters" x-model="content" placeholder="Votre commentaire" class="w-full rounded-md shadow-sm border-gray-300 bg-gray-100 focus:border-black focus:ring focus:ring-black  mt-4"></textarea>
            </div>
            <p class="text-sm text-gray-600">{{ __('Caractères restants: ') }}<span x-text="remainingCharacters"></span></p>
            <div class="text-gray-700 mt-2 flex justify-end">
                
                <x-input-error :messages="$errors->get('content')" class="ml-2" />
                <x-primary-button type="submit" class="ml-2 bg-red-600 hover:bg-red-700">
                    Ajouter un commentaire
                </x-primary-button>
            </div>
        </div>
    </form>
    @else
    <div class="flex bg-white rounded-md shadow p-4 text-gray-700 justify-between items-center">
        <span> Vous devez être connecté pour ajouter un comment </span>
        <a href="{{ route('login') }}" class="font-bold bg-white text-gray-700 px-4 py-2 rounded shadow-md">Se connecter</a>
    </div>
    @endauth

    <!-- Comments section -->


    <!-- Comments loop -->
    <div class="flex-col space-y-4 mb-10">
        @forelse ($post->comments as $comment)
        <div class="flex  rounded-md shadow p-4 space-x-4 mx-auto max-w-3xl bg-green-500 border-8 border-black">
            <a href="{{ route('profile.show', $comment->user) }}" class="flex justify-start items-start h-full">
                <x-avatar class="h-8 w-8" :user="$comment->user" />
            </a>
            <div class="flex flex-col justify-center">
                <div class="text-gray-700 ">
                    <a href="{{ route('profile.show', $comment->user) }}" class="font-bold text-black">{{ $comment->user->name }}</a>
                </div>
                <div class="text-gray-600 text-xs mb-5">
                    {{ $comment->created_at->diffForHumans() }}
                </div>
                <div class="text-black whitespace-normal overflow-hidden max-h-40 break-all">
                    {{ $comment->content }}
                </div>
            </div>
        </div>
        @empty
        <div class="flex bg-white rounded-md shadow p-4 space-x-4">
            Aucun comment pour l'instant
        </div>
        @endforelse
    </div>
    
</x-user-layout>