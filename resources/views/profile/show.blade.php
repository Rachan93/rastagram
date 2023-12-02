<x-user-layout>
    <div class="flex w-full">
        <x-avatar class="h-20 w-20" :user="$user" />
        <div class="ml-4 flex flex-col">
            <div class="text-gray-800 font-bold">{{ $user->name }}</div>
            <div class="text-gray-700 text-sm">{{ $user->email }}</div>
            <div class="text-gray-500 text-xs">
                Membre depuis {{ $user->created_at->diffForHumans() }}
            </div>
            
            <!-- Follow/Unfollow Button -->
            @auth
                @if(auth()->user()->id !== $user->id)
                    @if(auth()->user()->following->contains($user))
                        <form action="{{ route('profile.unfollow', $user) }}" method="post">
                            @csrf
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                Ne plus suivre
                            </button>
                        </form>
                    @else
                        <form action="{{ route('profile.follow', $user) }}" method="post">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">
                                Suivre
                            </button>
                        </form>
                    @endif
                @endif
            @endauth
        </div>
    </div>
    
<!-- Profile Post Gallery -->
<div class="mt-8">
    <h2 class="font-bold text-xl mb-4">Posts de {{ $user->name }}</h2>
    <ul class="grid sm:grid-cols-1 lg:grid-cols-1 2xl:grid-cols-2 gap-4 justify-center">
        @forelse ($posts as $post)
            <li class="w-full max-w-2xl mx-auto mt-10 bg-white p-8 rounded-md shadow-md"> <!-- Adjust the width here -->
                <a class="block bg-white rounded-md shadow-md p-2 hover:shadow-lg hover:scale-105 transition" href="{{ route('posts.show', $post) }}">
                    <div class="relative overflow-hidden rounded-md aspect-w-1 aspect-h-1">
                        <img src="{{ asset('storage/' . $post->image_url) }}" alt="{{ $post->description }}" class="object-cover w-full h-full rounded-md">
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center">
                            <!-- You can customize the avatar component here -->
                            <x-avatar class="h-8 w-8" :user="$post->user" />
                            <span class="text-gray-700 ml-2 text-sm">{{ $post->user->name }}</span>
                        </div>
                        <span class="text-gray-500 text-sm">{{ $post->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-gray-700 mt-1 text-sm">{{ $post->description }}</p>
                    <p class="text-gray-700 text-sm">{{ $post->localisation }}</p>
                    <p class="text-gray-700 text-sm">{{ $post->date }}</p>
                    <!-- Display Comment Count -->
                    <p class="text-gray-700 text-sm">{{ $post->comments_count }} {{ Str::plural('Comment', $post->comments_count) }}</p>
                </a>
            </li>
        @empty
            <div class="text-gray-700">Aucun post</div>
        @endforelse
    </ul>
</div>





    <div class="mt-8">
        <h2 class="font-bold text-xl mb-4">Comments</h2>

        <div class="flex-col space-y-4">
            @forelse ($comments as $comment)
                <div class="flex bg-white rounded-md shadow p-4 space-x-4">
                    <div class="flex justify-start items-start h-full">
                        <x-avatar class="h-10 w-10" :user="$comment->user" />
                    </div>
                    <div class="flex flex-col justify-center w-full space-y-4">
                        <div class="flex justify-between">
                            <div class="flex space-x-4 items-center justify-center">
                                <div class="flex flex-col justify-center">
                                    <div class="text-gray-700">{{ $comment->user->name }}</div>
                                    <div class="text-gray-500 text-sm">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-center">
                                @can('delete', $comment)
                                    <button
                                        x-data="{ id: {{ $comment->id }} }"
                                        x-on:click.prevent="window.selected = id; $dispatch('open-modal', 'confirm-comment-deletion');"
                                        type="submit"
                                        class="font-bold bg-white text-gray-700 px-4 py-2 rounded shadow"
                                    ></button>
                                @endcan
                            </div>
                        </div>
                        <div class="flex flex-col justify-center w-full text-gray-700">
                            <p class="border bg-gray-100 rounded-md p-4">
                                {{ $comment->content }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex bg-white rounded-md shadow p-4 space-x-4">
                    Aucun comment pour l'instant
                </div>
            @endforelse
        </div>
       

        <x-modal name="confirm-comment-deletion" focusable>
            <form
                method="post"
                onsubmit="event.target.action='/articles/{{ $article->id ?? 1 }}/comments/' + window.selected"
                class="p-6"
            >
                @csrf @method('DELETE')
                <h2 class="text-lg font-medium text-gray-900">
                    Êtes-vous sûr de vouloir supprimer ce comment ?
                </h2>
                <p class="mt-1 text-sm text-gray-600 ">
                    Cette action est irréversible. Toutes les données seront supprimées.
                </p>
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        Annuler
                    </x-secondary-button>
                    <x-danger-button class="ml-3" type="submit">
                        Supprimer
                    </x-danger-button>
                </div>
            </form>
        </x-modal>
    </div>
</x-user-layout>
